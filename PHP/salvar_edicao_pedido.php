<?php
// Salva a edição de um pedido: substitui os itens antigos pelos novos
// enviados pelo modal e recalcula o valor_total do pedido.
//
// O modal manda o ID do produto (não o nome), pois o <select> de
// "Adicionar Produto" no frontend é populado dinamicamente a partir
// do endpoint listar_edicao_pedido.php, que já retorna o id de cada
// produto. Por isso aqui a gente busca o produto pelo ID diretamente
// no banco, usando prepared statements — isso também serve como uma
// trava de segurança: só é possível salvar produtos que realmente
// existem e estão ativos na tabela `produtos`. Qualquer id que não
// bata com um produto ativo é rejeitado.

header('Content-Type: application/json');

include("conexao.php");

// Lê o corpo da requisição (JSON enviado pelo fetch no JS)
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

// Inicia uma transação: se algo falhar no meio do processo,
// desfazemos tudo (rollback) para não deixar o pedido "pela metade"
mysqli_begin_transaction($conn);

try {
    // 1) Remove todos os itens antigos deste pedido
    $stmtDelete = $conn->prepare("DELETE FROM itens_pedido WHERE pedido_id = ?");
    $stmtDelete->bind_param("i", $pedido_id);
    $stmtDelete->execute();
    $stmtDelete->close();

    // 2) Prepara os statements reutilizados no loop:
    //    - busca preço do produto pelo ID (só aceita produto ativo)
    //    - insere o item já com o produto_id validado
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

        // Ignora linhas inválidas (produto ausente ou quantidade zerada/negativa)
        if ($produto_id <= 0 || $quantidade <= 0) {
            continue;
        }

        // Busca o produto pelo ID exato no banco.
        // Se não encontrar (id não existe ou produto está inativo),
        // rejeita a edição inteira — evita salvar "produto fantasma".
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

    // 3) Atualiza o valor total do pedido
    $stmtUpdate = $conn->prepare("UPDATE pedidos SET valor_total = ? WHERE id = ?");
    $stmtUpdate->bind_param("di", $valorTotal, $pedido_id);
    $stmtUpdate->execute();
    $stmtUpdate->close();

    // Tudo certo: confirma as alterações
    mysqli_commit($conn);

    echo json_encode([
        'sucesso' => true,
        'mensagem' => 'Pedido atualizado com sucesso.',
        'valor_total' => $valorTotal
    ]);

} catch (Exception $e) {
    // Algo deu errado: desfaz tudo que foi feito na transação
    mysqli_rollback($conn);
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro ao salvar edição: ' . $e->getMessage()
    ]);
}

$conn->close();
?>