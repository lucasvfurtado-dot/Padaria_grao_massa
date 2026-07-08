<?php
header('Content-Type: application/json');
include('conexao.php');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'ID inválido.']);
    exit;
}

mysqli_begin_transaction($conn);

try {
    $sql_itens = "DELETE FROM itens_pedido WHERE pedido_id = $id";
    if (!mysqli_query($conn, $sql_itens)) {
        throw new Exception(mysqli_error($conn));
    }

    $sql_pedido = "DELETE FROM pedidos WHERE id = $id";
    if (!mysqli_query($conn, $sql_pedido)) {
        throw new Exception(mysqli_error($conn));
    }

    mysqli_commit($conn);
    echo json_encode(['sucesso' => true, 'mensagem' => 'Pedido excluído com sucesso.']);

} catch (Exception $e) {
    mysqli_rollback($conn);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao excluir: ' . $e->getMessage()]);
}

mysqli_close($conn);