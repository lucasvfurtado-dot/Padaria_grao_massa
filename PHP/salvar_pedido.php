<?php
header('Content-Type: application/json');

include('conexao.php');

// Pega os dados brutos enviados pelo JavaScript (tela do caixa)
$dadosRecebidos = json_decode(file_get_contents('php://input'), true);

if (!$dadosRecebidos) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Nenhum dado recebido do caixa.']);
    exit;
}

// Trata as variáveis para evitar invasões (SQL Injection)
// Se não vier cliente_id, grava como NULL no banco
$cliente_id = !empty($dadosRecebidos['cliente_id']) ? "'" . mysqli_real_escape_string($conn, $dadosRecebidos['cliente_id']) . "'" : "NULL";
$valor_total = floatval($dadosRecebidos['valor_total']);
$itens = $dadosRecebidos['itens'];

// Forçamos o status para Pendente, pois a venda ainda vai para a Gestão
$status = 'Pendente';

// ATENÇÃO: Como o seu banco EXIGE um funcionario_id obrigatório, estou colocando o ID 1 fixo aqui.
// Importante: Você precisa ter pelo menos 1 funcionário cadastrado na tabela `funcionarios` com o ID 1.
// Futuramente, mude isso aqui para pegar o ID do funcionário logado na sessão ($_SESSION['id_usuario']).
$funcionario_id = 1; 

// Iniciamos uma transação. Se der erro nos itens, ele não salva o pedido pela metade.
mysqli_begin_transaction($conn);

try {
    // 1. SALVAR NA TABELA `pedidos`
    // Inserimos o status 'Pendente' e a forma de pagamento vai como NULL por enquanto
    $sql_pedido = "INSERT INTO pedidos (funcionario_id, cliente_id, valor_total, status, forma_pagamento) 
                   VALUES ('$funcionario_id', $cliente_id, '$valor_total', '$status', NULL)";
    
    if (!mysqli_query($conn, $sql_pedido)) {
        throw new Exception("Erro ao salvar o pedido principal: " . mysqli_error($conn));
    }

    // Recupera o ID do pedido que o banco acabou de gerar para usar nos itens abaixo
    $pedido_id = mysqli_insert_id($conn);

    // 2. SALVAR NA TABELA `itens_pedido`
    foreach ($itens as $item) {
        $produto_id = mysqli_real_escape_string($conn, $item['id']);
        $quantidade = intval($item['qty']);
        $preco_unitario = floatval($item['price']);
        
        // O seu banco exige o subtotal preenchido, então fazemos a conta aqui no PHP
        $subtotal = $quantidade * $preco_unitario; 

        $sql_item = "INSERT INTO itens_pedido (pedido_id, produto_id, quantidade, preco_unitario, subtotal) 
                     VALUES ('$pedido_id', '$produto_id', '$quantidade', '$preco_unitario', '$subtotal')";
        
        if (!mysqli_query($conn, $sql_item)) {
            throw new Exception("Erro ao salvar o item (ID Produto: $produto_id): " . mysqli_error($conn));
        }
    }

    // Se tudo deu certo no pedido e nos itens, grava permanentemente no banco
    mysqli_commit($conn);
    
    echo json_encode(['sucesso' => true]);

} catch (Exception $e) {
    // Se deu erro, desfaz tudo que tentou salvar nessa transação
    mysqli_rollback($conn);
    echo json_encode(['sucesso' => false, 'mensagem' => $e->getMessage()]);
} // <-- Agora a chave de fechamento está correta!
?>