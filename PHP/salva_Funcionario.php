<?php
// Inclui a conexão que está na mesma pasta
include("conexao.php");

// Pega a opção da URL (I = Inserir, U = Atualizar, D = Deletar)
$opcao = $_GET['opcao'] ?? '';

// ------------------------------------------------------------------
// 1. CADASTRAR FUNCIONÁRIO (INSERT)
// ------------------------------------------------------------------
if ($opcao == 'I') {

    $nome_completo     = $_POST['nNomeCompleto'] ?? '';
    $cpf               = $_POST['nCpf'] ?? '';
    $cargo             = $_POST['nCargo'] ?? '';
    $email             = $_POST['nEmail'] ?? '';
    $telefone_whatsapp = $_POST['nTelefone'] ?? '';
    $senha             = $_POST['nSenha'] ?? '';

    // Endereço
    $cep         = $_POST['nCep'] ?? '';
    $logradouro  = $_POST['nLogradouro'] ?? '';
    $numero      = $_POST['nNumero'] ?? '';
    $complemento = $_POST['nComplemento'] ?? '';
    $bairro      = $_POST['nBairro'] ?? '';
    $cidade      = $_POST['nCidade'] ?? '';
    $uf          = $_POST['nUf'] ?? '';

    // A senha é obrigatória no cadastro (o form já marca como required,
    // mas validamos de novo aqui pra não deixar a coluna NOT NULL sem valor)
    if (trim($senha) === '') {
        header("Location: ../Cadastrar_Funcionario.php?msg=senha_obrigatoria");
        exit;
    }

    // VALIDAÇÃO: impede CPF duplicado (agora com prepared statement)
    $check_cpf = mysqli_prepare($conn, "SELECT id FROM funcionarios WHERE cpf = ?");
    mysqli_stmt_bind_param($check_cpf, "s", $cpf);
    mysqli_stmt_execute($check_cpf);
    mysqli_stmt_store_result($check_cpf);

    if (mysqli_stmt_num_rows($check_cpf) > 0) {
        mysqli_stmt_close($check_cpf);
        header("Location: ../Cadastrar_Funcionario.php?msg=cpf_duplicado");
        exit;
    }
    mysqli_stmt_close($check_cpf);

    // Criptografa a senha usando MD5 conforme solicitado
    $senha_hash = md5($senha);

    $sql = "INSERT INTO funcionarios
            (nome_completo, cpf, email, telefone_whatsapp, cargo, senha, cep, logradouro, numero, complemento, bairro, cidade, uf)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param(
        $stmt,
        "sssssssssssss",
        $nome_completo, $cpf, $email, $telefone_whatsapp, $cargo, $senha_hash,
        $cep, $logradouro, $numero, $complemento, $bairro, $cidade, $uf
    );

    if (mysqli_stmt_execute($stmt)) {
        header("Location: ../Cadastrar_Funcionario.php?msg=sucesso");
        exit;
    } else {
        echo "Erro ao cadastrar: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
}

// ------------------------------------------------------------------
// 2. ALTERAR FUNCIONÁRIO (UPDATE)
// ------------------------------------------------------------------
elseif ($opcao == 'U') {

    $id = (int) ($_GET['id'] ?? 0);

    $nome_completo     = $_POST['nNomeCompleto'] ?? '';
    $cpf               = $_POST['nCpf'] ?? '';
    $cargo             = $_POST['nCargo'] ?? '';
    $email             = $_POST['nEmail'] ?? '';
    $telefone_whatsapp = $_POST['nTelefone'] ?? '';
    $senha             = $_POST['nSenha'] ?? '';

    $cep         = $_POST['nCep'] ?? '';
    $logradouro  = $_POST['nLogradouro'] ?? '';
    $numero      = $_POST['nNumero'] ?? '';
    $complemento = $_POST['nComplemento'] ?? '';
    $bairro      = $_POST['nBairro'] ?? '';
    $cidade      = $_POST['nCidade'] ?? '';
    $uf          = $_POST['nUf'] ?? '';

    // Se o usuário digitou uma senha nova, atualiza o hash também em MD5.
    // Se deixou em branco, mantém a senha que já estava salva no banco.
    if (trim($senha) !== '') {
        $senha_hash = md5($senha);

        $sql = "UPDATE funcionarios SET
                nome_completo = ?, cpf = ?, email = ?, telefone_whatsapp = ?, cargo = ?, senha = ?,
                cep = ?, logradouro = ?, numero = ?, complemento = ?, bairro = ?, cidade = ?, uf = ?
                WHERE id = ?";

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param(
            $stmt,
            "sssssssssssssi",
            $nome_completo, $cpf, $email, $telefone_whatsapp, $cargo, $senha_hash,
            $cep, $logradouro, $numero, $complemento, $bairro, $cidade, $uf, $id
        );
    } else {
        $sql = "UPDATE funcionarios SET
                nome_completo = ?, cpf = ?, email = ?, telefone_whatsapp = ?, cargo = ?,
                cep = ?, logradouro = ?, numero = ?, complemento = ?, bairro = ?, cidade = ?, uf = ?
                WHERE id = ?";

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param(
            $stmt,
            "ssssssssssssi",
            $nome_completo, $cpf, $email, $telefone_whatsapp, $cargo,
            $cep, $logradouro, $numero, $complemento, $bairro, $cidade, $uf, $id
        );
    }

    if (mysqli_stmt_execute($stmt)) {
        header("Location: ../Cadastrar_Funcionario.php?msg=atualizado");
        exit;
    } else {
        echo "Erro ao atualizar: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
}

// ------------------------------------------------------------------
// 3. APAGAR FUNCIONÁRIO (DELETE)
// ------------------------------------------------------------------
elseif ($opcao == 'D') {

    $id = (int) ($_GET['id'] ?? 0);

    $stmt = mysqli_prepare($conn, "DELETE FROM funcionarios WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: ../Cadastrar_Funcionario.php?msg=excluido");
        exit;
    } else {
        echo "Erro ao excluir: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
}
?>