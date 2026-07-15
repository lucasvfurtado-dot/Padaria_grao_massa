<?php
// 1. Força o PHP a responder EXCLUSIVAMENTE em formato JSON
header('Content-Type: application/json');

// 2. Caminho correto do banco, igual ao do seu listar_pedidos.php
$arquivo_banco = 'php/conexao.php'; 

if (!file_exists($arquivo_banco)) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Arquivo de conexão não encontrado.']);
    exit;
}
require $arquivo_banco;

// 3. Captura os dados que foram enviados pelo JavaScript
$jsonRecebido = file_get_contents('php://input');
$dados = json_decode($jsonRecebido, true);

// 4. Verifica se o ID do pedido e a forma de pagamento realmente chegaram
if (!isset($dados['pedido_id']) || !isset($dados['forma_pagamento'])) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Dados incompletos enviados para o servidor.']);
    exit;
}

$pedido_id = $dados['pedido_id'];
$forma_pagamento = $dados['forma_pagamento'];

try {
    // Status que o pedido vai receber após o pagamento
    $novo_status = 'Entregue'; 
    
    // Atualiza a tabela pedidos (usando 'id' conforme seu banco e não pedido_id)
    $query = "UPDATE pedidos SET status = ?, forma_pagamento = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    
    // Vincula os parâmetros: 's' para string (status), 's' para string (forma de pagamento), 'i' para inteiro (id)
    $stmt->bind_param("ssi", $novo_status, $forma_pagamento, $pedido_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(['sucesso' => true]);
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Pedido não encontrado ou não houve alteração no status.']);
    }
    
    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro no banco de dados: ' . $e->getMessage()]);
}
?>