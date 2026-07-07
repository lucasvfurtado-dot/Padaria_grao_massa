<?php
// ==========================================
// 1. ATIVA O RELATÓRIO DE ERROS (DEBUG)
// ==========================================
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ==========================================
// 2. CONEXÃO COM O BANCO DE DADOS (PDO)
// ==========================================
$host = 'localhost';
$db   = 'padaria_grao_massa';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Erro de conexão com o banco de dados: " . $e->getMessage());
}

// ==========================================
// 3. BUSCANDO OS DADOS
// ==========================================
try {
    // --- Vendas por mês (últimos 12 meses) ---
    $stmt_meses = $pdo->query("
        SELECT 
            DATE_FORMAT(data_pedido, '%Y-%m') as mes,
            COUNT(*) as total_vendas,
            SUM(valor_total) as faturamento
        FROM pedidos 
        WHERE data_pedido >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
        GROUP BY DATE_FORMAT(data_pedido, '%Y-%m')
        ORDER BY mes ASC
    ");
    $vendas_por_mes = $stmt_meses->fetchAll();

    $labels = [];
    $valores_vendas = [];
    $valores_faturamento = [];

    foreach ($vendas_por_mes as $row) {
        $data = DateTime::createFromFormat('Y-m', $row['mes']);
        $labels[] = $data ? $data->format('M/y') : $row['mes'];
        $valores_vendas[] = (int)$row['total_vendas'];
        $valores_faturamento[] = (float)$row['faturamento'];
    }

    // ==========================================
    // PRODUTOS MAIS VENDIDOS (CORRIGIDO)
    // ==========================================
    try {
        // Busca produtos mais vendidos (sem filtro de data_criacao)
        $stmt_produtos = $pdo->query("
            SELECT 
                p.nome_produto,
                SUM(ip.quantidade) as total_vendido,
                SUM(ip.quantidade * ip.preco_unitario) as receita_total
            FROM itens_pedido ip
            JOIN produtos p ON ip.produto_id = p.id
            GROUP BY p.id
            ORDER BY total_vendido DESC
            LIMIT 10
        ");
        $produtos_mais_vendidos = $stmt_produtos->fetchAll();
        
    } catch (\PDOException $e) {
        $produtos_mais_vendidos = [];
        // Mostra o erro apenas no HTML comentado
        echo "<!-- Erro produtos: " . $e->getMessage() . " -->";
    }

    // --- Resumo geral do mês atual ---
    $stmt_resumo = $pdo->query("
        SELECT 
            COUNT(DISTINCT id) as total_pedidos,
            COALESCE(SUM(valor_total), 0) as faturamento_total,
            COALESCE(AVG(valor_total), 0) as ticket_medio,
            COUNT(DISTINCT cliente_id) as clientes_unicos
        FROM pedidos 
        WHERE MONTH(data_pedido) = MONTH(CURDATE()) 
        AND YEAR(data_pedido) = YEAR(CURDATE())
    ");
    $resumo_mes = $stmt_resumo->fetch();

    // --- Produto mais comprado do mês ---
    $stmt_top_produto = $pdo->query("
        SELECT 
            p.nome_produto,
            SUM(ip.quantidade) as total_vendido
        FROM itens_pedido ip
        JOIN produtos p ON ip.produto_id = p.id
        GROUP BY p.id
        ORDER BY total_vendido DESC
        LIMIT 1
    ");
    $produto_top = $stmt_top_produto->fetch();

} catch (\PDOException $e) {
    // Se der erro, define dados vazios
    $vendas_por_mes = [];
    $labels = ['Sem dados'];
    $valores_vendas = [0];
    $valores_faturamento = [0];
    $produtos_mais_vendidos = [];
    $resumo_mes = ['total_pedidos' => 0, 'faturamento_total' => 0, 'ticket_medio' => 0, 'clientes_unicos' => 0];
    $produto_top = null;
    
    echo "<!-- Erro SQL: " . $e->getMessage() . " -->";
}
?>
<!DOCTYPE html>
<html lang="pt-BR" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios - Grão & Massa</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=DM+Serif+Display&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="CSS/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <style>
.chart-wrapper {
    background: var(--card-bg, #fff);
    border: 1px solid var(--border-color, #e2e8f0);
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 24px;
    transition: background 0.3s, border-color 0.3s;
}

/* Tema escuro para os wrappers */
[data-theme="dark"] .chart-wrapper {
    background: #1e293b;
    border-color: #334155;
}

[data-theme="dark"] .chart-wrapper h4 {
    color: #e5e7eb !important;
}
        .relatorio-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }
        .relatorio-card {
            background: var(--card-bg, #fff);
            border: 1px solid var(--border-color, #e2e8f0);
            border-radius: 12px;
            padding: 20px 16px;
            text-align: center;
            transition: transform 0.2s;
        }
        .relatorio-card .numero {
            font-size: 28px;
            font-weight: 700;
            color: var(--ink, #1e293b);
        }
        .relatorio-card .rotulo {
            font-size: 13px;
            color: var(--ash, #94a3b8);
            margin-top: 6px;
        }
        .relatorio-card .destaque {
            color: var(--brand, #d97706);
        }
        .chart-wrapper {
            background: var(--card-bg, #fff);
            border: 1px solid var(--border-color, #e2e8f0);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
        }
        .chart-wrapper canvas {
            max-height: 280px;
            max-width: 100%;
        }
        .chart-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
        }
        .btn-relatorio {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 28px;
            background: var(--brand, #d97706);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-relatorio:hover {
            background: #b85e00;
        }
        .btn-relatorio svg {
            width: 20px;
            height: 20px;
        }
        .top-produto-box {
            background: var(--surface, #f8fafc);
            border-radius: 12px;
            padding: 16px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border: 1px solid var(--border-color, #e2e8f0);
            margin-bottom: 24px;
        }
        .top-produto-box .nome {
            font-size: 18px;
            font-weight: 700;
            color: var(--ink, #1e293b);
        }
        .top-produto-box .qtd {
            font-size: 24px;
            font-weight: 700;
            color: var(--brand, #d97706);
        }
        .page-header-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        @media (max-width: 1024px) {
            .relatorio-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 768px) {
            .chart-row { grid-template-columns: 1fr; }
            .relatorio-grid { grid-template-columns: 1fr; }
            .page-header { flex-direction: column; align-items: flex-start; gap: 12px; }
            .page-header-right { width: 100%; }
            .btn-relatorio { width: 100%; justify-content: center; }
        }
        @media print {
            .no-print { display: none !important; }
        }
        
/* ==========================================
TEMA ESCURO PARA OS CARDS E CONTAINERS
========================================== */

/* Cards de resumo */
[data-theme="dark"] .relatorio-card {
    background: #1e293b !important;
    border-color: #334155 !important;
}

[data-theme="dark"] .relatorio-card .numero {
    color: #e5e7eb !important;
}

[data-theme="dark"] .relatorio-card .rotulo {
    color: #94a3b8 !important;
}

[data-theme="dark"] .relatorio-card .destaque {
    color: #fbbf24 !important;
}

/* Wrappers dos gráficos */
[data-theme="dark"] .chart-wrapper {
    background: #1e293b !important;
    border-color: #334155 !important;
}

[data-theme="dark"] .chart-wrapper h4 {
    color: #e5e7eb !important;
}

/* Top Produto Box */
[data-theme="dark"] .top-produto-box {
    background: #1e293b !important;
    border-color: #334155 !important;
}

[data-theme="dark"] .top-produto-box .nome {
    color: #e5e7eb !important;
}

[data-theme="dark"] .top-produto-box .qtd {
    color: #fbbf24 !important;
}

[data-theme="dark"] .top-produto-box span {
    color: #94a3b8 !important;
}

/* Page Header */
[data-theme="dark"] .page-title {
    color: #e5e7eb !important;
}

[data-theme="dark"] .page-desc {
    color: #94a3b8 !important;
}

/* Debug info */
[data-theme="dark"] .debug-info {
    background: #1e293b !important;
    border-color: #334155 !important;
    color: #e5e7eb !important;
}

[data-theme="dark"] .debug-info strong {
    color: #fbbf24 !important;
}
    </style>
</head>
<body>

<!-- ===== SIDEBAR ===== -->
<nav class="sb">
    <div class="sb-brand">
        <div class="sb-icon"><svg fill="none" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
        <div class="sb-name">Grão &amp; Massa<span>Padaria &amp; Café</span></div>
    </div>

    <p class="sb-label">Menu</p>
    <ul class="sb-nav">
        <li><a href="index.php" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>Dashboard</a></li>
        <li><a href="vendas.php" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 001.97-1.67L23 6H6"/></svg>Caixa / Vendas</a></li>
        <li><a href="#" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>Estoque</a></li>
        <li><a href="relatorios.php" class="sb-link on"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>Relatórios<span class="sb-dot"></span></a></li>
    </ul>

    <p class="sb-label">Cadastros</p>
    <ul class="sb-nav">
        <li><a href="Cadastrar_Cliente.php" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>Clientes</a></li>
        <li><a href="Cadastrar_Funcionario.php" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>Funcionários</a></li>
        <li><a href="Cadastrar_Fornecedor.php" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/></svg>Fornecedores</a></li>
        <li><a href="Cadastrar_Produto.php" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>Produtos</a></li>
    </ul>

    <div class="sb-foot">
        <div class="sb-user">
            <div class="sb-av">AS</div>
            <div>
                <div class="sb-uname">Admin</div>
                <div class="sb-urole">Administrador</div>
            </div>
        </div>
    </div>
</nav>

<!-- ===== MAIN CONTENT ===== -->
<div class="main">

    <header class="top">
        <div class="top-l">
            <div class="badge-pg">
                <div class="badge-icon"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg></div>
                Relatórios
            </div>
            <span class="sep">•</span>
            <span class="date-chip" id="date"></span>
        </div>
        <div class="top-r">
            <button class="tb-btn no-print" onclick="toggleTheme()">
                <svg class="icon-moon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
                <svg class="icon-sun" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
            </button>
        </div>
    </header>

    <div class="content">
        <main class="dash-main">

            <div class="page-header">
                <div>
                    <h3 class="page-title">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                        Relatórios & Análises
                    </h3>
                    <span class="page-desc">Acompanhe as vendas e desempenho da sua padaria.</span>
                </div>
                <div class="page-header-right no-print">
                    <button class="btn-relatorio" onclick="gerarPDF()">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                        Gerar Relatório do Mês
                    </button>
                </div>
            </div>

            <!-- ===== CARDS DE RESUMO ===== -->
            <div class="relatorio-grid">
                <div class="relatorio-card">
                    <div class="numero"><?php echo number_format($resumo_mes['total_pedidos'] ?? 0, 0, ',', '.'); ?></div>
                    <div class="rotulo">📋 Pedidos no Mês</div>
                </div>
                <div class="relatorio-card">
                    <div class="numero destaque">R$ <?php echo number_format($resumo_mes['faturamento_total'] ?? 0, 2, ',', '.'); ?></div>
                    <div class="rotulo">💰 Faturamento Mensal</div>
                </div>
                <div class="relatorio-card">
                    <div class="numero">R$ <?php echo number_format($resumo_mes['ticket_medio'] ?? 0, 2, ',', '.'); ?></div>
                    <div class="rotulo">🎫 Ticket Médio</div>
                </div>
                <div class="relatorio-card">
                    <div class="numero"><?php echo number_format($resumo_mes['clientes_unicos'] ?? 0, 0, ',', '.'); ?></div>
                    <div class="rotulo">👥 Clientes Atendidos</div>
                </div>
            </div>

            <?php if ($produto_top): ?>
            <div class="top-produto-box">
                <div>
                    <span style="font-size: 13px; color: var(--ash); font-weight: 500;">🏆 Produto Mais Vendido do Mês</span>
                    <div class="nome"><?php echo htmlspecialchars($produto_top['nome_produto']); ?></div>
                </div>
                <div class="qtd"><?php echo $produto_top['total_vendido']; ?> unidades</div>
            </div>
            <?php endif; ?>

            <!-- ===== GRÁFICOS ===== -->
            <div id="relatorio-content">
                <div class="chart-row">
                    <div class="chart-wrapper">
                        <h4 style="margin-bottom: 16px; font-size: 15px; font-weight: 600; color: var(--ink);">📊 Vendas por Mês</h4>
                        <canvas id="graficoVendas"></canvas>
                    </div>
                    <div class="chart-wrapper">
                        <h4 style="margin-bottom: 16px; font-size: 15px; font-weight: 600; color: var(--ink);">📈 Faturamento por Mês</h4>
                        <canvas id="graficoFaturamento"></canvas>
                    </div>
                </div>

                <div class="chart-wrapper">
                    <h4 style="margin-bottom: 16px; font-size: 15px; font-weight: 600; color: var(--ink);">🥐 Produtos Mais Vendidos</h4>
                    <canvas id="graficoProdutos"></canvas>
                </div>
            </div>

        </main>
    </div>
</div>

<script>
// ==========================================
// 1. DATA E TEMA
// ==========================================
document.getElementById('date').textContent = new Date().toLocaleDateString('pt-BR',{weekday:'long',day:'numeric',month:'long'}).replace(/^\w/,c=>c.toUpperCase());

function toggleTheme(){ 
    const d = document.documentElement; 
    const t = d.getAttribute('data-theme') === 'dark' ? 'light' : 'dark'; 
    d.setAttribute('data-theme', t); 
    localStorage.setItem('theme', t); 
    setTimeout(criarGraficos, 200);
}
(()=>{ 
    const s = localStorage.getItem('theme'); 
    if(s === 'dark' || (!s && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.setAttribute('data-theme','dark'); 
    }
})();

// ==========================================
// 2. CORES PARA OS GRÁFICOS
// ==========================================
function getThemeColors() {
    const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
    return {
        text: isDark ? '#e5e7eb' : '#374151',
        grid: isDark ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.08)',
        primary: isDark ? '#f59e0b' : '#d97706',
        secondary: isDark ? '#fbbf24' : '#f59e0b',
        success: isDark ? '#34d399' : '#10b981',
        danger: isDark ? '#f87171' : '#ef4444',
        purple: isDark ? '#a78bfa' : '#8b5cf6',
        pink: isDark ? '#f472b6' : '#ec4899',
        cyan: isDark ? '#22d3ee' : '#06b6d4'
    };
}

// ==========================================
// 3. DADOS DO PHP PARA JS
// ==========================================
const labels = <?php echo json_encode($labels); ?>;
const vendasData = <?php echo json_encode($valores_vendas); ?>;
const faturamentoData = <?php echo json_encode($valores_faturamento); ?>;
const produtosNomes = <?php echo json_encode(array_column($produtos_mais_vendidos, 'nome_produto')); ?>;
const produtosQuantidades = <?php echo json_encode(array_column($produtos_mais_vendidos, 'total_vendido')); ?>;

// ==========================================
// 4. PLUGIN DE FUNDO PARA OS GRÁFICOS
// ==========================================
const pluginFundo = {
    id: 'fundoEscuro',
    beforeDraw: function(chart) {
        const ctx = chart.ctx;
        const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
        const corFundo = isDark ? '#1e293b' : '#ffffff';
        
        ctx.save();
        ctx.fillStyle = corFundo;
        ctx.fillRect(0, 0, chart.width, chart.height);
        ctx.restore();
    }
};

// ==========================================
// 5. FUNÇÃO PARA CRIAR GRÁFICOS
// ==========================================
let graficoVendas, graficoFaturamento, graficoProdutos;

function criarGraficos() {
    const colors = getThemeColors();
    
    if (graficoVendas) graficoVendas.destroy();
    if (graficoFaturamento) graficoFaturamento.destroy();
    if (graficoProdutos) graficoProdutos.destroy();

    const ctxVendas = document.getElementById('graficoVendas').getContext('2d');
    const ctxFaturamento = document.getElementById('graficoFaturamento').getContext('2d');
    const ctxProdutos = document.getElementById('graficoProdutos').getContext('2d');

    const hasData = labels.length > 0 && labels[0] !== 'Sem dados' && vendasData.some(v => v > 0);
    const dadosVendas = hasData ? vendasData : [0];
    const labelsVendas = hasData ? labels : ['Sem dados'];

    // === GRÁFICO 1: VENDAS ===
    graficoVendas = new Chart(ctxVendas, {
        type: 'bar',
        data: {
            labels: labelsVendas,
            datasets: [{
                label: 'Vendas',
                data: dadosVendas,
                backgroundColor: colors.primary + '80',
                borderColor: colors.primary,
                borderWidth: 2,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { 
                    beginAtZero: true, 
                    ticks: { color: colors.text, stepSize: 1 } 
                },
                x: { 
                    ticks: { color: colors.text } 
                }
            }
        },
        plugins: [pluginFundo]  // <-- SÓ ADICIONA O FUNDO
    });

    // === GRÁFICO 2: FATURAMENTO ===
    const dadosFaturamento = hasData ? faturamentoData : [0];

    graficoFaturamento = new Chart(ctxFaturamento, {
        type: 'line',
        data: {
            labels: labelsVendas,
            datasets: [{
                label: 'Faturamento (R$)',
                data: dadosFaturamento,
                backgroundColor: colors.success + '30',
                borderColor: colors.success,
                borderWidth: 3,
                fill: true,
                tension: 0.3,
                pointBackgroundColor: colors.success,
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { 
                    beginAtZero: true, 
                    ticks: { 
                        color: colors.text, 
                        callback: function(value) { return 'R$ ' + value.toFixed(0); } 
                    } 
                },
                x: { 
                    ticks: { color: colors.text } 
                }
            }
        },
        plugins: [pluginFundo]  // <-- SÓ ADICIONA O FUNDO
    });

    // === GRÁFICO 3: PRODUTOS ===
    const coresProdutos = [
        colors.primary, colors.secondary, colors.success, 
        colors.danger, colors.purple, colors.pink, colors.cyan,
        '#f97316', '#14b8a6', '#8b5cf6'
    ];
    const dadosProdutos = produtosQuantidades.length ? produtosQuantidades : [0];
    const nomesProdutos = produtosNomes.length ? produtosNomes : ['Sem dados'];

    graficoProdutos = new Chart(ctxProdutos, {
        type: 'bar',
        data: {
            labels: nomesProdutos,
            datasets: [{
                label: 'Quantidade Vendida',
                data: dadosProdutos,
                backgroundColor: coresProdutos.slice(0, dadosProdutos.length),
                borderColor: coresProdutos.slice(0, dadosProdutos.length),
                borderWidth: 1,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { 
                    beginAtZero: true, 
                    ticks: { color: colors.text, stepSize: 1 } 
                },
                x: { 
                    ticks: { color: colors.text } 
                }
            }
        },
        plugins: [pluginFundo]  // <-- SÓ ADICIONA O FUNDO
    });
}

// ==========================================
// 5. GERAR PDF
// ==========================================
function gerarPDF() {
    const btn = document.querySelector('.btn-relatorio');
    const originalText = btn.innerHTML;
    btn.innerHTML = '⏳ Gerando...';
    btn.disabled = true;

    const element = document.getElementById('relatorio-content');
    
    const opt = {
        margin:        [10, 10, 10, 10],
        filename:     'relatorio_vendas_grao_massa.pdf',
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { scale: 2, useCORS: true, logging: false },
        jsPDF:        { unit: 'mm', format: 'a4', orientation: 'landscape' }
    };

    html2pdf().set(opt).from(element).save().then(function() {
        btn.innerHTML = originalText;
        btn.disabled = false;
    }).catch(function(err) {
        console.error('Erro ao gerar PDF:', err);
        btn.innerHTML = originalText;
        btn.disabled = false;
        alert('Erro ao gerar PDF. Tente novamente.');
    });
}

// ==========================================
// 6. INICIALIZAR GRÁFICOS
// ==========================================
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(criarGraficos, 300);
});
if (document.readyState === 'complete' || document.readyState === 'interactive') {
    setTimeout(criarGraficos, 300);
}
</script>

</body>
</html>