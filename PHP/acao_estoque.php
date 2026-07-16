<?php
header('Content-Type: application/json');
include('conexao.php');

$json = file_get_contents('php://input');
$dados = json_decode($json, true);

if (!$dados) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Dados inválidos.']);
    exit;
}

$acao = $dados['acao'];
$id = intval($dados['id']);

try {
    if ($acao === 'atualizar') {
        $novo_estoque = intval($dados['estoque']);
        $sql = "UPDATE produtos SET estoque = $novo_estoque WHERE id = $id";
        
        if (mysqli_query($conn, $sql)) {
            echo json_encode(['sucesso' => true, 'mensagem' => 'Estoque atualizado!']);
        } else {
            throw new Exception("Erro ao atualizar estoque.");
        }
        
    } elseif ($acao === 'excluir') {
            
        $sql = "DELETE FROM produtos WHERE id = $id";
        
        if (mysqli_query($conn, $sql)) {
            echo json_encode(['sucesso' => true, 'mensagem' => 'Produto excluído!']);
        } else {
            throw new Exception("Não é possível excluir um produto que já possui vendas registradas. Inative-o no cadastro.");
        }
    }
} catch (Exception $e) {
    echo json_encode(['sucesso' => false, 'mensagem' => $e->getMessage()]);
}
?>