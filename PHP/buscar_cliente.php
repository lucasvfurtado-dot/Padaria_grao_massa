<?php
// Define que a resposta desta página será no formato JSON
header('Content-Type: application/json');

include('conexao.php'); 

// Verifica se o parâmetro 'cpf' 
if (isset($_GET['cpf'])) {
    
    // Protege o banco de dados contra SQL Injection (limpa o dado recebido)
    $cpf = mysqli_real_escape_string($conn, $_GET['cpf']);
    
    $sql = "SELECT id, nome_razao_social 
            FROM clientes 
            WHERE REPLACE(REPLACE(REPLACE(cpf_cnpj, '.', ''), '-', ''), '/', '') = '$cpf' 
            LIMIT 1"; // LIMIT 1 
            
    // Executa a consulta 
    $result = mysqli_query($conn, $sql);

    // Verifica se a consulta funcionou e se encontrou 
    if ($result && mysqli_num_rows($result) > 0) {
        $cliente = mysqli_fetch_assoc($result);
        
        // Converte os dados encontrados JSON e envia como resposta
        echo json_encode([
            'sucesso' => true, 
            'nome' => $cliente['nome_razao_social'], 
            'id' => $cliente['id']
        ]);
    } else {
        // Resposta caso o banco de dados 
        echo json_encode([
            'sucesso' => false, 
            'mensagem' => 'Cliente não encontrado'
        ]);
    }
} else {
    // Resposta caso o arquivo seja acessado sem o cpf
    echo json_encode([
        'sucesso' => false, 
        'mensagem' => 'CPF não informado'
    ]);
}
?>