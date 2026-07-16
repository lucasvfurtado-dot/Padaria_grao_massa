<?php

header('Content-Type: application/json');


include("php/conexao.php");

$sql = "SELECT 
            p.id AS pedido_id,
            p.status,
            c.nome_razao_social AS cliente,
            c.telefone_whatsapp AS telefone,
            ip.quantidade,
            prod.id AS produto_id,
            prod.nome_produto AS produto,
            prod.categoria,
            prod.preco AS preco_unitario
        FROM pedidos p
        LEFT JOIN clientes c ON p.cliente_id = c.id
        LEFT JOIN itens_pedido ip ON p.id = ip.pedido_id
        LEFT JOIN produtos prod ON ip.produto_id = prod.id
        ORDER BY p.id DESC";

$result = mysqli_query($conn, $sql);

$pedidos_formatados = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $pedido_id = $row['pedido_id'];
        
       
        if (!isset($pedidos_formatados[$pedido_id])) {
            $pedidos_formatados[$pedido_id] = [
               
                'id' => "#" . str_pad($pedido_id, 4, "0", STR_PAD_LEFT), 
                'pedido_id' => $pedido_id,
                'cliente' => $row['cliente'] ?? 'Cliente Padrão/Avulso',
                'telefone' => $row['telefone'] ?? '',
                'status' => $row['status'],
                'itens' => []
            ];
        }
        
       
        if ($row['produto']) {
            $pedidos_formatados[$pedido_id]['itens'][] = [
                'produto_id' => (int)$row['produto_id'],
                'produto' => $row['produto'],
                'quantidade' => (int)$row['quantidade'],
                'categoria' => $row['categoria'],
                'preco_unitario' => (float)$row['preco_unitario']
            ];
        }
    }
} else {
  
    echo json_encode(['mensagem' => 'Erro ao consultar o banco de dados.']);
    exit;
}


echo json_encode(array_values($pedidos_formatados));


$conn->close();
?>