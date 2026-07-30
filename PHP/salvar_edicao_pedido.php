<?php

header('Content-Type: application/json');

include("conexao.php");

$dados = json_decode(file_get_contents('php://input'), true);

$pedido_id = isset($dados['pedido_id']) ? (int)$dados['pedido_id'] : 0;
$itens = (isset($dados['itens']) && is_array($dados['itens'])) ? $dados['itens'] : [];

if ($pedido_id <= 0 || empty($itens)) {
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Dados inválidos: pedido ou lista de itens ausente/vazia.'
    ]);
    exit;
}

// Normaliza a lista recebida: soma quantidades caso o mesmo produto_id apareça mais de uma vez
$itensNovos = []; // produto_id => quantidade
foreach ($itens as $item) {
    $produto_id = isset($item['produto_id']) ? (int)$item['produto_id'] : 0;
    $quantidade = isset($item['quantidade']) ? (int)$item['quantidade'] : 0;

    if ($produto_id <= 0 || $quantidade <= 0) {
        continue;
    }

    if (isset($itensNovos[$produto_id])) {
        $itensNovos[$produto_id] += $quantidade;
    } else {
        $itensNovos[$produto_id] = $quantidade;
    }
}

if (empty($itensNovos)) {
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Nenhum item válido foi enviado.'
    ]);
    exit;
}

mysqli_begin_transaction($conn);

try {

    // 1) Pega as quantidades ANTIGAS de cada produto nesse pedido, antes de qualquer alteração
    $itensAntigos = []; // produto_id => quantidade
    $stmtAntigos = $conn->prepare("SELECT produto_id, quantidade FROM itens_pedido WHERE pedido_id = ?");
    $stmtAntigos->bind_param("i", $pedido_id);
    $stmtAntigos->execute();
    $resAntigos = $stmtAntigos->get_result();
    while ($row = $resAntigos->fetch_assoc()) {
        $pid = (int)$row['produto_id'];
        $itensAntigos[$pid] = ($itensAntigos[$pid] ?? 0) + (int)$row['quantidade'];
    }
    $stmtAntigos->close();

    // 2) Monta a lista de todos os produtos envolvidos (novos + antigos) e calcula a diferença de cada um
    //    delta > 0  => precisa RETIRAR mais do estoque
    //    delta < 0  => precisa DEVOLVER ao estoque
    $produtosEnvolvidos = array_unique(array_merge(array_keys($itensAntigos), array_keys($itensNovos)));

    $deltas = []; // produto_id => delta (quantidade a subtrair do estoque; pode ser negativo)
    foreach ($produtosEnvolvidos as $pid) {
        $antiga = $itensAntigos[$pid] ?? 0;
        $nova   = $itensNovos[$pid]   ?? 0;
        $delta  = $nova - $antiga;
        if ($delta !== 0) {
            $deltas[$pid] = $delta;
        }
    }

    // 3) Para cada produto com delta, trava a linha (FOR UPDATE) e verifica/aplica o estoque
    $stmtLock = $conn->prepare("SELECT estoque, preco, produto_ativo FROM produtos WHERE id = ? FOR UPDATE");
    $stmtUpdateEstoque = $conn->prepare("UPDATE produtos SET estoque = estoque - ? WHERE id = ?");

    foreach ($deltas as $produto_id => $delta) {
        $stmtLock->bind_param("i", $produto_id);
        $stmtLock->execute();
        $resLock = $stmtLock->get_result();
        $rowProduto = $resLock->fetch_assoc();

        if (!$rowProduto) {
            throw new Exception("Produto de id {$produto_id} não encontrado.");
        }

        $estoqueAtual = (int)$rowProduto['estoque'];

        // Só precisa checar disponibilidade quando o delta é positivo (quer retirar mais do estoque)
        if ($delta > 0 && $estoqueAtual < $delta) {
            throw new Exception(
                "Estoque insuficiente para o produto de id {$produto_id}. " .
                "Disponível: {$estoqueAtual}, solicitado a mais: {$delta}."
            );
        }

        $stmtUpdateEstoque->bind_param("ii", $delta, $produto_id);
        $stmtUpdateEstoque->execute();
    }

    $stmtLock->close();
    $stmtUpdateEstoque->close();

    // 4) Agora que o estoque foi validado e ajustado, recria os itens do pedido
    $stmtDelete = $conn->prepare("DELETE FROM itens_pedido WHERE pedido_id = ?");
    $stmtDelete->bind_param("i", $pedido_id);
    $stmtDelete->execute();
    $stmtDelete->close();

    $stmtProduto = $conn->prepare(
        "SELECT id, preco FROM produtos WHERE id = ? LIMIT 1"
    );
    $stmtInsert = $conn->prepare(
        "INSERT INTO itens_pedido (pedido_id, produto_id, quantidade, preco_unitario, subtotal) 
         VALUES (?, ?, ?, ?, ?)"
    );

    $valorTotal = 0.0;
    $itensInseridos = 0;

    foreach ($itensNovos as $produto_id => $quantidade) {
        $stmtProduto->bind_param("i", $produto_id);
        $stmtProduto->execute();
        $resProduto = $stmtProduto->get_result();
        $rowProduto = $resProduto->fetch_assoc();

        if (!$rowProduto) {
            throw new Exception("Produto de id {$produto_id} não encontrado.");
        }

        $preco_unitario = (float)$rowProduto['preco'];
        $subtotal       = $preco_unitario * $quantidade;
        $valorTotal    += $subtotal;

        $stmtInsert->bind_param(
            "iiidd",
            $pedido_id,
            $produto_id,
            $quantidade,
            $preco_unitario,
            $subtotal
        );
        $stmtInsert->execute();
        $itensInseridos++;
    }

    $stmtProduto->close();
    $stmtInsert->close();

    if ($itensInseridos === 0) {
        throw new Exception("Nenhum item válido foi enviado.");
    }

    $stmtUpdate = $conn->prepare("UPDATE pedidos SET valor_total = ? WHERE id = ?");
    $stmtUpdate->bind_param("di", $valorTotal, $pedido_id);
    $stmtUpdate->execute();
    $stmtUpdate->close();

    mysqli_commit($conn);

    echo json_encode([
        'sucesso' => true,
        'mensagem' => 'Pedido atualizado com sucesso.',
        'valor_total' => $valorTotal
    ]);

} catch (Exception $e) {

    mysqli_rollback($conn);
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro ao salvar edição: ' . $e->getMessage()
    ]);
}

$conn->close();
?>