<?php
// CONECTAR AO BANCO DE DADOS
$conn = mysqli_connect("localhost",
                        "root",
                        "", 
                        "padaria_grao_massa");

if (!$conn) {
    die(json_encode([
        'sucesso' => false, 
        'mensagem' => 'Erro crítico: Falha na conexão com o banco de dados.'
    ]));
}