<?php

header('Content-Type: application/json');


include('conexao.php'); 


if (isset($_GET['cpf'])) {
    
    $cpf = mysqli_real_escape_string($conn, $_GET['cpf']);
    
    
    $sql = "SELECT id, nome_razao_social 
            FROM clientes 
            WHERE REPLACE(REPLACE(REPLACE(cpf_cnpj, '.', ''), '-', ''), '/', '') = '$cpf' 
            LIMIT 1";
            
    $result = mysqli_query($conn, $sql);

    
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