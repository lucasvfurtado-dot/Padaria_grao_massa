<?php
// Define que a resposta será no formato JSON
header('Content-Type: application/json');

// Conecta ao banco de dados
include('conexao.php'); 

// Verifica se o CPF foi enviado na requisição
if (isset($_GET['cpf'])) {
    // Pega os números que vieram do JavaScript
    $cpf = mysqli_real_escape_string($conn, $_GET['cpf']);
    
    // Consulta usando os nomes exatos da sua tabela e ignorando pontos e traços na busca!
    $sql = "SELECT id, nome_razao_social 
            FROM clientes 
            WHERE REPLACE(REPLACE(REPLACE(cpf_cnpj, '.', ''), '-', ''), '/', '') = '$cpf' 
            LIMIT 1";
            
    $result = mysqli_query($conn, $sql);

    // Se encontrou o cliente...
    if ($result && mysqli_num_rows($result) > 0) {
        $cliente = mysqli_fetch_assoc($result);
        
        echo json_encode([
            'sucesso' => true, 
            'nome' => $cliente['nome_razao_social'], 
            'id' => $cliente['id']
        ]);
    } else {
        echo json_encode([
            'sucesso' => false, 
            'mensagem' => 'Cliente não encontrado'
        ]);
    }
} else {
    echo json_encode([
        'sucesso' => false, 
        'mensagem' => 'CPF não informado'
    ]);
}
?>