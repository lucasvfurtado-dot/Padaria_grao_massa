<?php
// Define que a resposta será em formato JSON
header('Content-Type: application/json');

// Inclui a ligação com a base de dados
include('conexao.php'); 

// Recebe os dados em JSON enviados pelo JavaScript (do ficheiro vendas.php)
$json = file_get_contents('php://input');
$dados = json_decode($json, true);

// Verifica se os dados chegaram em condições
if (!$dados) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Nenhum dado recebido.']);
    exit;
}

// Extrai os dados enviados pelo JavaScript
$cliente_id = intval($dados['cliente_id']);
$valor_total = floatval($dados['valor_total']);
$itens = $dados['itens'];

// Pega o status que enviamos do JS. Se por algum motivo falhar, assume 'Pendente' por padrão
$status_pedido = isset($dados['status']) ? $dados['status'] : 'Pendente';

// ==========================================
// CORREÇÃO AQUI: Pega o ID do funcionário que veio do JavaScript
// ==========================================
$funcionario_id = isset($dados['funcionario_id']) ? intval($dados['funcionario_id']) : 0; 

// Trava de segurança: se o funcionário não for identificado, aborta o salvamento
if ($funcionario_id === 0) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro: Funcionário não identificado. Por favor, atualize a página ou faça login novamente.']);
    exit;
}

// INICIA A TRANSAÇÃO: Daqui para baixo, se algo falhar, a base de dados cancela tudo (Rollback)
mysqli_begin_transaction($conn);

try {
    // 1. Salvar o pedido base na tabela `pedidos`
    // NOTA: Se as colunas no seu banco tiverem nomes diferentes (ex: funcionario_responsavel_pela_venda), altere-as aqui abaixo!
    $sql_pedido = "INSERT INTO pedidos (funcionario_id, cliente_id, valor_total, forma_pagamento, status) 
                   VALUES ($funcionario_id, $cliente_id, $valor_total, 'Dinheiro/Cartão', '$status_pedido')";
    
    if (!mysqli_query($conn, $sql_pedido)) {
        throw new Exception("Erro ao registar o pedido: " . mysqli_error($conn));
    }

    // Apanha o ID numérico do pedido que acabou de ser gerado na base de dados
    $pedido_id = mysqli_insert_id($conn); 

    // 2. Fazer um loop (repetição) para processar cada produto que estava no carrinho
    foreach ($itens as $item) {
        $produto_id = intval($item['id']);
        $quantidade = intval($item['qty']);
        $preco_unitario = floatval($item['price']);
        $subtotal = $quantidade * $preco_unitario;

        // A. Insere o produto na tabela `itens_pedido`
        $sql_item = "INSERT INTO itens_pedido (pedido_id, produto_id, quantidade, preco_unitario, subtotal)
                     VALUES ($pedido_id, $produto_id, $quantidade, $preco_unitario, $subtotal)";
                     
        if (!mysqli_query($conn, $sql_item)) {
            throw new Exception("Erro ao salvar os itens do pedido: " . mysqli_error($conn));
        }

        // B. A MÁGICA DO STOCK: Desconta a quantidade vendida do stock atual do produto
        $sql_baixa_estoque = "UPDATE produtos 
                              SET estoque = estoque - $quantidade 
                              WHERE id = $produto_id";
                              
        if (!mysqli_query($conn, $sql_baixa_estoque)) {
            throw new Exception("Erro ao dar baixa no estoque do produto ID: $produto_id");
        }
    }

    // 3. Se chegou até aqui sem dar qualquer erro, CONFIRMA as alterações na base de dados!
    mysqli_commit($conn);
    
    // Retorna a mensagem de sucesso para o front-end (JavaScript do vendas.php)
    echo json_encode(['sucesso' => true, 'mensagem' => 'Venda registrada como pendente e estoque atualizado com sucesso!']);

} catch (Exception $e) {
    // SE DEU ERRO EM QUALQUER PARTE (Rollback): desfaz tudo!
    mysqli_rollback($conn);
    
    // Retorna a mensagem de erro para o ecrã do utilizador
    echo json_encode(['sucesso' => false, 'mensagem' => $e->getMessage()]);
}
?>