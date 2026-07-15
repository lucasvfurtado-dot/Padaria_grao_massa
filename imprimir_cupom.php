<?php
// Inclui a sua conexão com o banco
require 'php/conexao.php';

// Pega o ID do pedido que veio pela URL
$pedido_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($pedido_id === 0) {
    die("Pedido não informado.");
}

// Busca os dados principais do pedido e cliente
$sqlPedido = "SELECT p.id, p.forma_pagamento, p.data_criacao, c.nome_razao_social AS cliente 
              FROM pedidos p 
              LEFT JOIN clientes c ON p.cliente_id = c.id 
              WHERE p.id = $pedido_id";
$resPedido = mysqli_query($conn, $sqlPedido);
$pedido = mysqli_fetch_assoc($resPedido);

if (!$pedido) {
    die("Pedido não encontrado.");
}

// Busca os itens do pedido
$sqlItens = "SELECT ip.quantidade, prod.nome_produto 
             FROM itens_pedido ip 
             JOIN produtos prod ON ip.produto_id = prod.id 
             WHERE ip.pedido_id = $pedido_id";
$resItens = mysqli_query($conn, $sqlItens);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cupom - Pedido #<?php echo $pedido_id; ?></title>
    <style>
        /* CSS ESPECIAL PARA IMPRESSORA TÉRMICA */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Courier New', Courier, monospace; }
        body { width: 300px; margin: 0 auto; padding: 10px; font-size: 12px; color: #000; }
        .center { text-align: center; }
        .bold { font-weight: bold; }
        .divisor { border-top: 1px dashed #000; margin: 8px 0; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
        th, td { text-align: left; padding: 2px 0; }
        .col-qtd { width: 15%; text-align: center; }
        .col-item { width: 85%; }

        /* Esconde o botão na hora de imprimir o papel */
        @media print {
            .no-print { display: none; }
            body { width: 100%; } /* Usa a largura total do papel na impressora */
        }
        
        .btn-imprimir { width: 100%; padding: 10px; margin-top: 15px; background: #000; color: #fff; border: none; cursor: pointer; font-weight: bold; border-radius: 4px; }
    </style>
</head>
<body>

    <div class="center">
        <h2 class="bold">GRÃO & MASSA</h2>
        <p>Padaria & Café</p>
        <p>CNPJ: 00.000.000/0001-00</p>
    </div>

    <div class="divisor"></div>

    <p><strong>Pedido Nº:</strong> <?php echo str_pad($pedido['id'], 4, "0", STR_PAD_LEFT); ?></p>
    <p><strong>Cliente:</strong> <?php echo $pedido['cliente'] ? $pedido['cliente'] : 'Consumidor Final'; ?></p>
    <p><strong>Data:</strong> <?php echo date('d/m/Y H:i', strtotime($pedido['data_criacao'] ?? 'now')); ?></p>
    
    <div class="divisor"></div>
    <p class="center bold">CUPOM NÃO FISCAL</p>
    <div class="divisor"></div>

    <table>
        <thead>
            <tr>
                <th class="col-qtd">Qtd</th>
                <th class="col-item">Descrição do Item</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($item = mysqli_fetch_assoc($resItens)): ?>
            <tr>
                <td class="col-qtd"><?php echo $item['quantidade']; ?>x</td>
                <td class="col-item"><?php echo htmlspecialchars($item['nome_produto']); ?></td>
            </tr>
            <?php endline; ?>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div class="divisor"></div>
    
    <p><strong>Forma de Pagamento:</strong> <?php echo $pedido['forma_pagamento'] ?? 'Não informada'; ?></p>

    <div class="divisor"></div>
    <div class="center">
        <p>Agradecemos a preferência!</p>
        <p>Volte Sempre</p>
    </div>

    <button class="btn-imprimir no-print" onclick="window.print()">🖨️ Imprimir Cupom</button>

    <!-- SCRIPT QUE ABRE A TELA DE IMPRESSÃO SOZINHO -->
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>