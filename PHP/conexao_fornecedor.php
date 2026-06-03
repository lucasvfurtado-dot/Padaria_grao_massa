<?php
// ==========================================
// 1. LIGAÇÃO À BASE DE DADOS
// ==========================================
$servidor = "localhost";
$usuario = "root";
$senha = "";
$banco = "padaria_grao_massa";

$conexao = new mysqli($servidor, $usuario, $senha, $banco);

// Verifica se houve erro na ligação
if ($conexao->connect_error) {
    die("Erro na ligação à base de dados: " . $conexao->connect_error);
}

// Garante que os acentos (ç, ã, á) vão para a base de dados corretamente
$conexao->set_charset("utf8");

// ==========================================
// 2. RECEBER E PROTEGER OS DADOS (Prevenção de SQL Injection)
// ==========================================
// O real_escape_string é essencial para o código não quebrar se o utilizador digitar aspas
$empresa        = $conexao->real_escape_string($_POST["nome_empresa"] ?? '');
$cnpj           = $conexao->real_escape_string($_POST["cnpj"] ?? '');
$numero_celular = $conexao->real_escape_string($_POST["telefone"] ?? '');
$email          = $conexao->real_escape_string($_POST["email"] ?? '');
$categoria      = $conexao->real_escape_string($_POST["categoria"] ?? '');
$cep            = $conexao->real_escape_string($_POST["cep"] ?? '');
$logradouro     = $conexao->real_escape_string($_POST["rua"] ?? '');
$numero         = $conexao->real_escape_string($_POST["numero"] ?? '');
$bairro         = $conexao->real_escape_string($_POST["bairro"] ?? '');
$cidade         = $conexao->real_escape_string($_POST["cidade"] ?? '');
$uf             = $conexao->real_escape_string($_POST["uf"] ?? '');

// ==========================================
// 3. INSERIR NA BASE DE DADOS
// ==========================================
$sql = "INSERT INTO fornecedores 
(empresa, cnpj, numero_celular, email, categoria, cep, logradouro, numero, bairro, cidade, uf)
VALUES
('$empresa', '$cnpj', '$numero_celular', '$email', '$categoria', '$cep', '$logradouro', '$numero', '$bairro', '$cidade', '$uf')";

// ==========================================
// 4. VERIFICAR O RESULTADO E DEVOLVER A MENSAGEM
// ==========================================
if ($conexao->query($sql) === TRUE) {
    // A palavra "sucesso" aqui é essencial para o JavaScript limpar o formulário!
    echo "Fornecedor cadastrado com sucesso!";
} else {
    // Se der erro (ex: CNPJ repetido), mostra exatamente o motivo
    echo "Erro ao cadastrar fornecedor: " . $conexao->error;
}

// Fechar a ligação
$conexao->close();
?>