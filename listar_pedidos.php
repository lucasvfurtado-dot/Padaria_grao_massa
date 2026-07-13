<?php
// Define o retorno como JSON para o Javascript ler corretamente
header('Content-Type: application/json');

// Inclui a conexão (ajuste o caminho se a sua pasta php estiver em outro lugar)
include("php/conexao.php");

// Query que junta as tabelas pedidos, clientes, itens_pedido e produtos
$sql = "SELECT 
            p.id AS pedido_id,
            p.status,
            c.nome_razao_social AS cliente,
            c.telefone_whatsapp AS telefone,
            ip.quantidade,
            prod.nome_produto AS produto,
            prod.categoria
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
        
        // Se o pedido ainda não existe no nosso array, criamos a estrutura principal dele
        if (!isset($pedidos_formatados[$pedido_id])) {
            $pedidos_formatados[$pedido_id] = [
                // Formata o ID para ficar bonitinho com zeros. Ex: #0001, #0015
                'id' => "#" . str_pad($pedido_id, 4, "0", STR_PAD_LEFT), 
                'pedido_id' => $pedido_id,
                'cliente' => $row['cliente'] ?? 'Cliente Padrão/Avulso',
                'telefone' => $row['telefone'] ?? '',
                'status' => $row['status'],
                'itens' => []
            ];
        }
        
        // Adiciona os itens comprados dentro do array 'itens' do respectivo pedido
        if ($row['produto']) {
            $pedidos_formatados[$pedido_id]['itens'][] = [
                'produto' => $row['produto'],
                'quantidade' => (int)$row['quantidade'],
                'categoria' => $row['categoria']
            ];
        }
    }
} else {
    // Se der erro na query, envia um JSON informando
    echo json_encode(['mensagem' => 'Erro ao consultar o banco de dados.']);
    exit;
}

// O Frontend espera um array de objetos limpo, então usamos array_values para reindexar
echo json_encode(array_values($pedidos_formatados));

// Fecha a conexão
$conn->close();
?>