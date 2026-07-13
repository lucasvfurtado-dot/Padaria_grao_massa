<?php
// Define o tipo de retorno como JSON
header('Content-Type: application/json');

// Inclui a conexão com o banco de dados
include("conexao.php");

// Recebe os dados em formato JSON enviados pelo JavaScript no vendas.php
$json_recebido = file_get_contents('php://input');
$dados = json_decode($json_recebido, true);

// Verifica se os dados foram recebidos corretamente
if (!$dados) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Nenhum dado recebido.']);
    exit;
}

// Extrai as informações do payload
$cliente_id = $dados['cliente_id'];
$funcionario_id = $dados['funcionario_id'];
$valor_total = $dados['valor_total'];
$status = $dados['status']; // Será recebido como 'Pendente'
$itens = $dados['itens'];

// Forma de pagamento padrão por enquanto (já que o frontend ainda não envia isso)
$forma_pagamento = 'A combinar'; 

// Inicia a transação SQL (Garante que tudo seja salvo junto, ou tudo seja desfeito em caso de erro)
mysqli_begin_transaction($conn);

try {
    // 1. CADASTRAR O PEDIDO PRINCIPAL NA TABELA 'pedidos'
    $sql_pedido = "INSERT INTO pedidos (funcionario_id, cliente_id, valor_total, forma_pagamento, status) VALUES (?, ?, ?, ?, ?)";
    $stmt_pedido = $conn->prepare($sql_pedido);
    $stmt_pedido->bind_param("iidss", $funcionario_id, $cliente_id, $valor_total, $forma_pagamento, $status);
    $stmt_pedido->execute();
    
    // Recupera o ID gerado para este novo pedido
    $pedido_id = $conn->insert_id;

    // Prepara os comandos para os itens e para o estoque (isso melhora o desempenho no loop)
    $sql_item = "INSERT INTO itens_pedido (pedido_id, produto_id, quantidade, preco_unitario, subtotal) VALUES (?, ?, ?, ?, ?)";
    $stmt_item = $conn->prepare($sql_item);

    $sql_estoque = "UPDATE produtos SET estoque = estoque - ? WHERE id = ?";
    $stmt_estoque = $conn->prepare($sql_estoque);

    // 2. CADASTRAR OS ITENS E ATUALIZAR O ESTOQUE
    foreach ($itens as $item) {
        $produto_id = $item['id'];
        $quantidade = $item['qty'];
        $preco_unitario = $item['price'];
        $subtotal = $quantidade * $preco_unitario;

        // Insere o item na tabela 'itens_pedido'
        $stmt_item->bind_param("iiidd", $pedido_id, $produto_id, $quantidade, $preco_unitario, $subtotal);
        $stmt_item->execute();

        // Subtrai a quantidade vendida da tabela 'produtos'
        $stmt_estoque->bind_param("ii", $quantidade, $produto_id);
        $stmt_estoque->execute();
    }

    // Se tudo deu certo, confirma as alterações no banco de dados
    mysqli_commit($conn);

    // Retorna a mensagem de sucesso para o frontend
    echo json_encode(['sucesso' => true, 'mensagem' => 'Pedido concluído com sucesso e estoque atualizado!']);

} catch (Exception $e) {
    // Se ocorreu qualquer erro, desfaz tudo que foi tentado até agora
    mysqli_rollback($conn);
    
    // Retorna a mensagem de erro
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao salvar o pedido: ' . $e->getMessage()]);
}

// Fecha os statements e a conexão
if (isset($stmt_pedido)) $stmt_pedido->close();
if (isset($stmt_item)) $stmt_item->close();
if (isset($stmt_estoque)) $stmt_estoque->close();
$conn->close();
?>