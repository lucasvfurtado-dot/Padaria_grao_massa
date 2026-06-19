<?php
    $opcao = $_GET['opcao'] ?? ''; 
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    
    // Pegando os dados do formulário
    $nome_razao_social = $_POST['nNome'] ?? '';
    $cpf_cnpj          = $_POST['nCpfCnpj'] ?? '';
    $email             = $_POST['nEmail'] ?? '';
    $telefone_whatsapp = $_POST['nTelefone'] ?? '';
    $cep               = $_POST['nCep'] ?? '';
    $logradouro        = $_POST['nLogradouro'] ?? '';
    $numero            = $_POST['nNumero'] ?? '';
    $complemento       = $_POST['nComplemento'] ?? '';
    $bairro            = $_POST['nBairro'] ?? '';
    $cidade            = $_POST['nCidade'] ?? '';
    $uf                = $_POST['nUf'] ?? '';

    // montar sql
    if($opcao == 'I'){
        $sql = "INSERT INTO clientes (nome_razao_social, cpf_cnpj, email, telefone_whatsapp, cep, logradouro, numero, complemento, bairro, cidade, uf)
                VALUES ('$nome_razao_social', '$cpf_cnpj', '$email', '$telefone_whatsapp', '$cep', '$logradouro', '$numero', '$complemento', '$bairro', '$cidade', '$uf');";
                
    } elseif ($opcao == 'U') {
        // AQUI ESTÁ A NOVIDADE: O UPDATE!
        $sql = "UPDATE clientes SET 
                nome_razao_social = '$nome_razao_social',
                cpf_cnpj = '$cpf_cnpj',
                email = '$email',
                telefone_whatsapp = '$telefone_whatsapp',
                cep = '$cep',
                logradouro = '$logradouro',
                numero = '$numero',
                complemento = '$complemento',
                bairro = '$bairro',
                cidade = '$cidade',
                uf = '$uf'
                WHERE id = $id;";
        
    } elseif($opcao == 'D'){
        $sql = "DELETE FROM clientes WHERE id = $id;";
    }

    include ("conexao.php");
    if(isset($sql) && $sql != "") {
        mysqli_query($conn, $sql);
    }
    mysqli_close($conn);

    header ('location: ../Cadastrar_Cliente.php');
?>