<?php
include("conexao.php");

$opcao = $_GET['opcao'] ?? '';

$nome_empresa = $_POST['nNomeEmpresa'] ?? '';
$cnpj = $_POST['nCnpj'] ?? '';
$email = $_POST['nEmail'] ?? '';
$telefone = $_POST['nTelefone'] ?? '';
$categoria = $_POST['nCategoria'] ?? '';
$cep = $_POST['nCep'] ?? '';
$logradouro = $_POST['nLogradouro'] ?? '';
$numero = $_POST['nNumero'] ?? '';
$complemento = $_POST['nComplemento'] ?? '';
$bairro = $_POST['nBairro'] ?? '';
$cidade = $_POST['nCidade'] ?? '';
$uf = $_POST['nUf'] ?? '';

$cnpj_limpo = preg_replace('/[^0-9]/', '', $cnpj);
$telefone_limpo = preg_replace('/[^0-9]/', '', $telefone);
$cep_limpo = preg_replace('/[^0-9]/', '', $cep);

if ($opcao == 'I') { 
    $sql = "INSERT INTO fornecedores (nome_empresa, cnpj, email, telefone, categoria, cep, logradouro, numero, complemento, bairro, cidade, uf) 
            VALUES (
                '$nome_empresa', 
                '$cnpj_limpo', 
                '$email', 
                '$telefone_limpo', 
                '$categoria', 
                '$cep_limpo', 
                '$logradouro', 
                '$numero', 
                '$complemento', 
                '$bairro', 
                '$cidade', 
                '$uf'
            )";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: ../Cadastrar_Fornecedor.php?");
        exit();
    } else {
        echo "Erro ao cadastrar: " . $conn->error;
    }
    
} elseif ($opcao == 'E') { 
    $id = $_GET['id'] ?? 0;
    
    $sql = "UPDATE fornecedores SET 
            nome_empresa = '$nome_empresa',
            cnpj = '$cnpj_limpo',
            email = '$email',
            telefone = '$telefone_limpo',
            categoria = '$categoria',
            cep = '$cep_limpo',
            logradouro = '$logradouro',
            numero = '$numero',
            complemento = '$complemento',
            bairro = '$bairro',
            cidade = '$cidade',
            uf = '$uf'
            WHERE id = $id";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: ../Cadastrar_Fornecedor.php?");
        exit();
    } else {
        echo "Erro ao atualizar: " . $conn->error;
    }
    
} elseif ($opcao == 'D') { 
    $id = $_GET['id'] ?? 0;
    $sql = "DELETE FROM fornecedores WHERE id = $id";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: ../Cadastrar_Fornecedor.php?");
        exit();
    } else {
        echo "Erro ao excluir: " . $conn->error;
    }
}
?>