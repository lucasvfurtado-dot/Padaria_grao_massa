<?php
// Inclui a conexão que está na mesma pasta
include("conexao.php"); 

// Pega a opção da URL (I = Inserir, U = Atualizar, D = Deletar)
$opcao = $_GET['opcao'] ?? '';

// ------------------------------------------------------------------
// 1. CADASTRAR FUNCIONÁRIO (INSERT)
// ------------------------------------------------------------------
if ($opcao == 'I') {
    
    // Recebendo os dados do formulário
    $nome_completo = $_POST['nNomeCompleto'] ?? '';
    $cpf = $_POST['nCpf'] ?? '';
    $cargo = $_POST['nCargo'] ?? '';
    $email = $_POST['nEmail'] ?? '';
    $telefone_whatsapp = $_POST['nTelefone'] ?? '';
    
    // Endereço
    $cep = $_POST['nCep'] ?? '';
    $logradouro = $_POST['nLogradouro'] ?? '';
    $numero = $_POST['nNumero'] ?? '';
    $complemento = $_POST['nComplemento'] ?? '';
    $bairro = $_POST['nBairro'] ?? '';
    $cidade = $_POST['nCidade'] ?? '';
    $uf = $_POST['nUf'] ?? '';

    // VALIDAÇÃO: Impede o erro Fatal Error de duplicidade (image_5b61a2.png)
    $check_cpf = "SELECT id FROM funcionarios WHERE cpf = '$cpf'";
    $res_check = mysqli_query($conn, $check_cpf);

    if (mysqli_num_rows($res_check) > 0) {
        // Volta 1 pasta usando "../" porque Cadastrar_Funcionario.php está fora!
        header("Location: ../Cadastrar_Funcionario.php?msg=cpf_duplicado");
        exit;
    }

    // Montando o SQL com as colunas do banco
    $sql = "INSERT INTO funcionarios 
            (nome_completo, cpf, email, telefone_whatsapp, cargo, cep, logradouro, numero, complemento, bairro, cidade, uf) 
            VALUES 
            ('$nome_completo', '$cpf', '$email', '$telefone_whatsapp', '$cargo', '$cep', '$logradouro', '$numero', '$complemento', '$bairro', '$cidade', '$uf')";

    if (mysqli_query($conn, $sql)) {
        header("Location: ../Cadastrar_Funcionario.php?msg=sucesso");
        exit;
    } else {
        echo "Erro ao cadastrar: " . mysqli_error($conn);
    }
}

// ------------------------------------------------------------------
// 2. ALTERAR FUNCIONÁRIO (UPDATE)
// ------------------------------------------------------------------
elseif ($opcao == 'U') {
    
    $id = $_GET['id'] ?? 0;

    $nome_completo = $_POST['nNomeCompleto'] ?? '';
    $cpf = $_POST['nCpf'] ?? '';
    $cargo = $_POST['nCargo'] ?? '';
    $email = $_POST['nEmail'] ?? '';
    $telefone_whatsapp = $_POST['nTelefone'] ?? '';
    
    $cep = $_POST['nCep'] ?? '';
    $logradouro = $_POST['nLogradouro'] ?? '';
    $numero = $_POST['nNumero'] ?? '';
    $complemento = $_POST['nComplemento'] ?? '';
    $bairro = $_POST['nBairro'] ?? '';
    $cidade = $_POST['nCidade'] ?? '';
    $uf = $_POST['nUf'] ?? '';

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
            WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        header("Location: ../Cadastrar_Funcionario.php?msg=atualizado");
        exit;
    } else {
        echo "Erro ao atualizar: " . mysqli_error($conn);
    }
}

// ------------------------------------------------------------------
// 3. APAGAR FUNCIONÁRIO (DELETE)
// ------------------------------------------------------------------
elseif ($opcao == 'D') {
    
    $id = $_GET['id'] ?? 0;

    $sql = "DELETE FROM funcionarios WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        header("Location: ../Cadastrar_Funcionario.php?msg=excluido");
        exit;
    } else {
        echo "Erro ao excluir: " . mysqli_error($conn);
    }
}
?>