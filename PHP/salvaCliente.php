<?php
    $opcao = $_GET['opcao']; /* recebe na url */
    
    // O id pode não vir no Insert, então usamos isset para evitar erros
    $id = isset($_GET['id']) ? $_GET['id'] : 0; 
    
    // Dados do formulário
    $nome_razao_social = $_POST['nNome'];
    $cpf_cnpj          = $_POST['nCpfCnpj'];
    $email             = $_POST['nEmail'];
    $telefone_whatsapp = $_POST['nTelefone'];
    $cep               = $_POST['nCep'];
    $logradouro        = $_POST['nLogradouro'];
    $numero            = $_POST['nNumero'];
    $complemento       = $_POST['nComplemento'];
    $bairro            = $_POST['nBairro'];
    $cidade            = $_POST['nCidade'];
    $uf                = $_POST['nUf'];

    // montar sql
    if($opcao == 'I'){
        // echo "VAI RODAR UM INSERT";
        $sql = "INSERT INTO clientes (nome_razao_social, cpf_cnpj, email, telefone_whatsapp, cep, logradouro, numero, complemento, bairro, cidade, uf)
                VALUES ('$nome_razao_social', '$cpf_cnpj', '$email', '$telefone_whatsapp', '$cep', '$logradouro', '$numero', '$complemento', '$bairro', '$cidade', '$uf');";
                
    } elseif ($opcao == 'U') {
        // echo "VAI RODAR UM UPDATE";
        $sql = ""; 
        
    } elseif($opcao == 'D'){
        // echo " VAI RODAR UM DELETE";
        $sql = "DELETE FROM clientes WHERE id = $id;";
    }

    // conecta
    include ("conexao.php");

    // executa
    $result = mysqli_query($conn, $sql);

    // fecha conexão
    mysqli_close($conn);

    // trata o retorno (volta para a sua tela de cadastro)
    header ('location: ../Cadastrar_Cliente.php');
?>