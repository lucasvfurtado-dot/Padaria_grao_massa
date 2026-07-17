<?php
header('Content-Type: application/json');
include("conexao.php");

$sql = "SELECT id, nome_produto, categoria, preco 
        FROM produtos 
        WHERE produto_ativo = 1 
        ORDER BY nome_produto ASC";

$result = mysqli_query($conn, $sql);

$produtos = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $produtos[] = [
            'id' => (int)$row['id'],
            'nome_produto' => $row['nome_produto'],
            'categoria' => $row['categoria'],
            'preco' => (float)$row['preco']
        ];
    }
    echo json_encode($produtos);
} else {
    echo json_encode(['mensagem' => 'Erro ao consultar produtos: ' . mysqli_error($conn)]);
}

$conn->close();
?>