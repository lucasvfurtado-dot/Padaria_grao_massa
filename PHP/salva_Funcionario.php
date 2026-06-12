<?php
    $opcao = $_GET['opcao'] ?? ''; 
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0; 
    
    // Pegando os dados do formulário
    $nome_completo     = $_POST['nNome'] ?? '';
    $cpf               = $_POST['nCpf'] ?? '';
    $cargo             = $_POST['nCargo'] ?? ''; 
    $email             = $_POST['nEmail'] ?? '';
    $telefone_whatsapp = $_POST['nTelefone'] ?? '';
    $cep               = $_POST['nCep'] ?? '';
    $logradouro        = $_POST['nLogradouro'] ?? '';
    $numero            = $_POST['nNumero'] ?? '';
    $complemento       = $_POST['nComplemento'] ?? '';
    $bairro            = $_POST['nBairro'] ?? '';
    $cidade            = $_POST['nCidade'] ?? '';
    $uf                = $_POST['nUf'] ?? '';

    // montar sql com as colunas EXATAS do seu banco de dados
    if($opcao == 'I'){
        $sql = "INSERT INTO funcionarios (nome_completo, cpf, email, telefone_whatsapp, cargo, cep, logradouro, numero, complemento, bairro, cidade, uf)
                VALUES ('$nome_completo', '$cpf', '$email', '$telefone_whatsapp', '$cargo', '$cep', '$logradouro', '$numero', '$complemento', '$bairro', '$cidade', '$uf');";
                
    } elseif ($opcao == 'U') {
        $sql = "UPDATE funcionarios SET 
                nome_completo = '$nome_completo',
                cpf = '$cpf',
                email = '$email',
                telefone_whatsapp = '$telefone_whatsapp',
                cargo = '$cargo',
                cep = '$cep',
                logradouro = '$logradouro',
                numero = '$numero',
                complemento = '$complemento',
                bairro = '$bairro',
                cidade = '$cidade',
                uf = '$uf'
                WHERE id = $id;";
        
    } elseif($opcao == 'D'){
        $sql = "DELETE FROM funcionarios WHERE id = $id;";
    }

    include ("conexao.php");
    if(isset($sql) && $sql != "") {
        mysqli_query($conn, $sql);
    }
    mysqli_close($conn);

    header ('location: ../Cadastrar_Funcionario.php');
?>