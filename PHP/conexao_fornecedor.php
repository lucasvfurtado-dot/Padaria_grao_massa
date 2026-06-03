<?php

// CONEXÃO COM O BANCO
$servidor = "localhost";
$usuario = "root";
$senha = "";
$banco = "padaria_grao_massa";

$conexao = new mysqli($servidor, $usuario, $senha, $banco);

if ($conexao->connect_error) {
    die("Erro na conexão com o banco: " . $conexao->connect_error);
}

$conexao->set_charset("utf8");


// PEGANDO OS DADOS DO FORMULÁRIO
$empresa = $_POST["nome_empresa"];
$cnpj = $_POST["cnpj"];
$numero_celular = $_POST["telefone"];
$email = $_POST["email"];
$categoria = $_POST["categoria"];
$cep = $_POST["cep"];
$logradouro = $_POST["rua"];
$numero = $_POST["numero"];
$bairro = $_POST["bairro"];
$cidade = $_POST["cidade"];
$uf = $_POST["uf"];


// INSERINDO NO BANCO
$sql = "INSERT INTO fornecedores 
(
    empresa,
    cnpj,
    numero_celular,
    email,
    categoria,
    cep,
    logradouro,
    numero,
    bairro,
    cidade,
    uf
)
VALUES
(
    '$empresa',
    '$cnpj',
    '$numero_celular',
    '$email',
    '$categoria',
    '$cep',
    '$logradouro',
    '$numero',
    '$bairro',
    '$cidade',
    '$uf'
)";


// VERIFICA SE DEU CERTO
if ($conexao->query($sql) === TRUE) {
    echo "Fornecedor cadastrado com sucesso!";
} else {
    echo "Erro ao cadastrar fornecedor: " . $conexao->error;
}

$conexao->close();

?>

