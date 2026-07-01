<?php
// Inclui conexão
include("conexao.php");

// Define o cabeçalho para responder em JSON
header('Content-Type: application/json');

// Recebe os dados brutos enviados pelo Fetch API do JavaScript
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Validações básicas
if (!$data || empty($data['itens'])) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'O carrinho está vazio.']);
    exit;
}

$valor_total = $data['valor_total'];
$forma_pagamento = 'Dinheiro'; // Você pode implementar a escolha no front-end depois

// ATENÇÃO: Substituir por um ID real ou pegar via SESSION quando houver login.
$funcionario_id = 1; 

// Inicia uma transação (Garante que pedidos e itens sejam salvos juntos)
mysqli_begin_transaction($conn);

try {
    // 1. Inserir o registro do Pedido na tabela 'pedidos'
    $sql_pedido = "INSERT INTO pedidos (funcionario_id, valor_total, forma_pagamento, status) VALUES (?, ?, ?, 'Concluído')";
    $stmt_pedido = $conn->prepare($sql_pedido);
    $stmt_pedido->bind_param("ids", $funcionario_id, $valor_total, $forma_pagamento);
    $stmt_pedido->execute();
    
    // Recupera o ID gerado para este pedido
    $pedido_id = $stmt_pedido->insert_id;

    // Prepara as Queries que serão rodadas em loop (Performance melhorada)
    $stmt_item = $conn->prepare("INSERT INTO itens_pedido (pedido_id, produto_id, quantidade, preco_unitario, subtotal) VALUES (?, ?, ?, ?, ?)");
    
    // Bônus: Baixar o estoque automaticamente na tabela produtos
    $stmt_estoque = $conn->prepare("UPDATE produtos SET estoque = estoque - ? WHERE id = ?");

    // 2. Inserir cada item do carrinho na tabela 'itens_pedido'
    foreach ($data['itens'] as $item) {
        $produto_id = $item['id'];
        $quantidade = $item['qty'];
        $preco_unitario = $item['price'];
        $subtotal = $quantidade * $preco_unitario;

        // Grava o Item
        $stmt_item->bind_param("iiidd", $pedido_id, $produto_id, $quantidade, $preco_unitario, $subtotal);
        $stmt_item->execute();

        // Atualiza o Estoque
        $stmt_estoque->bind_param("ii", $quantidade, $produto_id);
        $stmt_estoque->execute();
    }

    // Se tudo der certo, consolida as alterações no banco de dados
    mysqli_commit($conn);
    
    echo json_encode(['sucesso' => true, 'mensagem' => 'Pedido salvo com sucesso!']);

} catch (Exception $e) {
    // Se der qualquer erro em qualquer etapa, desfaz tudo
    mysqli_rollback($conn);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro interno ao salvar pedido: ' . $e->getMessage()]);
}

// Fechando as conexões preparadas
if (isset($stmt_pedido)) $stmt_pedido->close();
if (isset($stmt_item)) $stmt_item->close();
if (isset($stmt_estoque)) $stmt_estoque->close();
$conn->close();
?>