<?php
// Retorna, em JSON, todos os pedidos já feitos, com os dados do cliente
// e a lista de itens (produtos) comprados em cada pedido.
header('Content-Type: application/json');
include('conexao.php');

$sql = "SELECT 
            p.id            AS pedido_id,
            p.status        AS status,
            c.id            AS cliente_id,
            c.nome_razao_social AS cliente,
            c.telefone_whatsapp AS telefone,
            ip.quantidade   AS quantidade,
            ip.preco_unitario AS preco_unitario,
            pr.nome_produto AS produto,
            pr.categoria    AS categoria
        FROM pedidos p
        INNER JOIN clientes c      ON c.id = p.cliente_id
        INNER JOIN itens_pedido ip ON ip.pedido_id = p.id
        INNER JOIN produtos pr     ON pr.id = ip.produto_id
        ORDER BY p.id DESC";

$result = mysqli_query($conn, $sql);

if (!$result) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao consultar pedidos: ' . mysqli_error($conn)]);
    exit;
}

// Agrupa as linhas (uma por item) dentro de cada pedido
$pedidosAgrupados = [];

while ($linha = mysqli_fetch_assoc($result)) {
    $pid = $linha['pedido_id'];

    if (!isset($pedidosAgrupados[$pid])) {
        $pedidosAgrupados[$pid] = [
            'id'        => 'P-' . str_pad($pid, 3, '0', STR_PAD_LEFT),
            'pedido_id' => (int) $pid,
            'cliente_id'=> (int) $linha['cliente_id'],
            'cliente'   => $linha['cliente'],
            'telefone'  => $linha['telefone'],
            'status'    => $linha['status'],
            'itens'     => []
        ];
    }

    $pedidosAgrupados[$pid]['itens'][] = [
        'produto'        => $linha['produto'],
        'categoria'      => $linha['categoria'],
        'quantidade'     => (int) $linha['quantidade'],
        'preco_unitario' => (float) $linha['preco_unitario']
    ];
}

mysqli_close($conn);

// re-indexa como lista simples (array sem chaves de string)
echo json_encode(array_values($pedidosAgrupados));