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

mysqli_begin_transaction($conn);

try {
    
    $stmtDelete = $conn->prepare("DELETE FROM itens_pedido WHERE pedido_id = ?");
    $stmtDelete->bind_param("i", $pedido_id);
    $stmtDelete->execute();
    $stmtDelete->close();

    $stmtProduto = $conn->prepare(
        "SELECT id, preco FROM produtos WHERE id = ? AND produto_ativo = 1 LIMIT 1"
    );
    $stmtInsert = $conn->prepare(
        "INSERT INTO itens_pedido (pedido_id, produto_id, quantidade, preco_unitario, subtotal) 
         VALUES (?, ?, ?, ?, ?)"
    );

    $valorTotal = 0.0;
    $itensInseridos = 0;

    foreach ($itens as $item) {
        $produto_id = isset($item['produto_id']) ? (int)$item['produto_id'] : 0;
        $quantidade = isset($item['quantidade']) ? (int)$item['quantidade'] : 0;

        if ($produto_id <= 0 || $quantidade <= 0) {
            continue;
        }

        $stmtProduto->bind_param("i", $produto_id);
        $stmtProduto->execute();
        $resProduto = $stmtProduto->get_result();
        $rowProduto = $resProduto->fetch_assoc();

        if (!$rowProduto) {
            throw new Exception("Produto de id {$produto_id} não encontrado ou inativo.");
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