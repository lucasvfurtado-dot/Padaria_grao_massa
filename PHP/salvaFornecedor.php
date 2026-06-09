<?php
include("conexao.php");

$opcao = $_GET['opcao'];

$nome_empresa = $_POST['nNomeEmpresa'];
$cnpj = $_POST['nCnpj'];
$email = $_POST['nEmail'];
$telefone = $_POST['nTelefone'];
$categoria = $_POST['nCategoria'];
$cep = $_POST['nCep'];
$logradouro = $_POST['nLogradouro'];
$numero = $_POST['nNumero'];
$complemento = $_POST['nComplemento'];
$bairro = $_POST['nBairro'];
$cidade = $_POST['nCidade'];
$uf = $_POST['nUf'];

if($opcao == 'I') {
    // INSERIR novo fornecedor
    $sql = "INSERT INTO fornecedores (nome_empresa, cnpj, email, telefone, categoria, cep, logradouro, numero, complemento, bairro, cidade, uf) 
            VALUES ('$nome_empresa', '$cnpj', '$email', '$telefone', '$categoria', '$cep', '$logradouro', '$numero', '$complemento', '$bairro', '$cidade', '$uf')";
    
    if($conn->query($sql) === TRUE) {
        header("Location: ../Cadastrar_Fornecedor.php?msg=sucesso");
    } else {
        echo "Erro ao inserir: " . $conn->error;
    }
} 
elseif($opcao == 'E') {
    // EDITAR fornecedor existente
    $id = $_GET['id'];
    
    $sql = "UPDATE fornecedores SET 
            nome_empresa = '$nome_empresa',
            cnpj = '$cnpj',
            email = '$email',
            telefone = '$telefone',
            categoria = '$categoria',
            cep = '$cep',
            logradouro = '$logradouro',
            numero = '$numero',
            complemento = '$complemento',
            bairro = '$bairro',
            cidade = '$cidade',
            uf = '$uf'
            WHERE id = $id";
    
    if($conn->query($sql) === TRUE) {
        header("Location: ../Cadastrar_Fornecedor.php?msg=atualizado");
    } else {
        echo "Erro ao atualizar: " . $conn->error;
    }
}

$conn->close();
?>