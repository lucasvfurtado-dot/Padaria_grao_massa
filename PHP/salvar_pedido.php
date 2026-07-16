<?php
header('Content-Type: application/json');

include("conexao.php");

$json_recebido = file_get_contents('php://input');
$dados = json_decode($json_recebido, true);


if (!$dados) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Nenhum dado recebido.']);
    exit;
}

$cliente_id = $dados['cliente_id'];
$funcionario_id = $dados['funcionario_id'];
$valor_total = $dados['valor_total'];
$status = $dados['status']; 
$itens = $dados['itens'];

$forma_pagamento = 'A combinar'; 

mysqli_begin_transaction($conn);

try {

    $sql_pedido = "INSERT INTO pedidos (funcionario_id, cliente_id, valor_total, forma_pagamento, status) VALUES (?, ?, ?, ?, ?)";
    $stmt_pedido = $conn->prepare($sql_pedido);
    $stmt_pedido->bind_param("iidss", $funcionario_id, $cliente_id, $valor_total, $forma_pagamento, $status);
    $stmt_pedido->execute();
    
    $pedido_id = $conn->insert_id;

    $sql_item = "INSERT INTO itens_pedido (pedido_id, produto_id, quantidade, preco_unitario, subtotal) VALUES (?, ?, ?, ?, ?)";
    $stmt_item = $conn->prepare($sql_item);

    $sql_estoque = "UPDATE produtos SET estoque = estoque - ? WHERE id = ?";
    $stmt_estoque = $conn->prepare($sql_estoque);

    foreach ($itens as $item) {
        $produto_id = $item['id'];
        $quantidade = $item['qty'];
        $preco_unitario = $item['price'];
        $subtotal = $quantidade * $preco_unitario;

        $stmt_item->bind_param("iiidd", $pedido_id, $produto_id, $quantidade, $preco_unitario, $subtotal);
        $stmt_item->execute();

        $stmt_estoque->bind_param("ii", $quantidade, $produto_id);
        $stmt_estoque->execute();
    }

    mysqli_commit($conn);

    echo json_encode(['sucesso' => true, 'mensagem' => 'Pedido concluído com sucesso e estoque atualizado!']);

} catch (Exception $e) {
    
    mysqli_rollback($conn);
    
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao salvar o pedido: ' . $e->getMessage()]);
}

if (isset($stmt_pedido)) $stmt_pedido->close();
if (isset($stmt_item)) $stmt_item->close();
if (isset($stmt_estoque)) $stmt_estoque->close();
$conn->close();
?>