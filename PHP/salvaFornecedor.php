<?php
include("conexao.php");

$opcao = $_GET['opcao'] ?? '';

// Pega os dados do formulário
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

// 🔥 REMOVE TODOS OS CARACTERES ESPECIAIS (só números)
$cnpj_limpo = preg_replace('/[^0-9]/', '', $cnpj);
$telefone_limpo = preg_replace('/[^0-9]/', '', $telefone);
$cep_limpo = preg_replace('/[^0-9]/', '', $cep);

// 🔥 VERIFICA SE O CNPJ TEM 14 DÍGITOS
if (strlen($cnpj_limpo) != 14) {
    // Se não tiver 14 dígitos, exibe erro
    header("Location: ../Cadastrar_Fornecedor.php?msg=cnpj_invalido");
    exit();
}

if ($opcao == 'I') { // INSERIR
    // 🔥 GARANTE QUE O CNPJ VAI COMO STRING (com aspas)
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
        header("Location: ../Cadastrar_Fornecedor.php?msg=sucesso");
        exit();
    } else {
        echo "Erro ao cadastrar: " . $conn->error;
    }
    
} elseif ($opcao == 'E') { // EDITAR
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
        header("Location: ../Cadastrar_Fornecedor.php?msg=atualizado");
        exit();
    } else {
        echo "Erro ao atualizar: " . $conn->error;
    }
    
} elseif ($opcao == 'D') { // DELETAR
    $id = $_GET['id'] ?? 0;
    $sql = "DELETE FROM fornecedores WHERE id = $id";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: ../Cadastrar_Fornecedor.php?msg=excluido");
        exit();
    } else {
        echo "Erro ao excluir: " . $conn->error;
    }
}
?>