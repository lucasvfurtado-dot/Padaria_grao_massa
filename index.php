<?php
// ==========================================
// 0. VERIFICAÇÃO DE SESSÃO (LOGIN)
// ==========================================
session_start();

// Se não tiver um usuário logado, manda de volta pro login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Resgata os dados da sessão
$nome_usuario = $_SESSION['usuario_nome'] ?? 'Usuário';
$cargo_usuario = $_SESSION['usuario_cargo'] ?? 'Funcionário';

// Lógica para pegar as iniciais do nome para o Avatar (Ex: Ana Luiza -> AL)
$partes_nome = explode(' ', trim($nome_usuario));
$iniciais = strtoupper(substr($partes_nome[0], 0, 1));
if (count($partes_nome) > 1) {
    $iniciais .= strtoupper(substr(end($partes_nome), 0, 1));
}

// 1. CONEXÃO COM O BANCO DE DADOS (PDO)

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

// 2. BUSCANDO AS MÉTRICAS PRINCIPAIS
$stmt_vendas = $pdo->query("SELECT COUNT(id) as total_vendas FROM pedidos WHERE DATE(data_pedido) = CURDATE()");
$vendas_hoje = $stmt_vendas->fetch()['total_vendas'] ?? 0;

$stmt_faturamento = $pdo->query("SELECT SUM(valor_total) as faturamento FROM pedidos WHERE DATE(data_pedido) = CURDATE()");
$faturamento = $stmt_faturamento->fetch()['faturamento'] ?? 0;

$ticket_medio = ($vendas_hoje > 0) ? ($faturamento / $vendas_hoje) : 0;

$stmt_visitantes = $pdo->query("SELECT COUNT(DISTINCT cliente_id) as clientes_hoje FROM pedidos WHERE DATE(data_pedido) = CURDATE() AND cliente_id IS NOT NULL");
$visitantes = $stmt_visitantes->fetch()['clientes_hoje'] ?? 0;


// 3. BUSCANDO ÚLTIMAS ATIVIDADES
$stmt_atividades = $pdo->query("
    SELECT 
        id, 
        forma_pagamento, 
        valor_total, 
        TIMESTAMPDIFF(MINUTE, data_pedido, NOW()) as minutos_atras 
    FROM pedidos 
    ORDER BY data_pedido DESC 
    LIMIT 3
");
$ultimas_atividades = $stmt_atividades->fetchAll();


// 4. BUSCANDO FATURAMENTO SEMANAL (ÚLTIMOS 7 DIAS)
$stmt_semana = $pdo->query("
    SELECT 
        DATE(data_pedido) as data_venda,
        SUM(valor_total) as faturamento
    FROM pedidos 
    WHERE data_pedido >= CURDATE() - INTERVAL 6 DAY
    GROUP BY DATE(data_pedido)
    ORDER BY data_venda ASC
");
$faturamento_semanal_db = $stmt_semana->fetchAll();

// Tradução dos dias da semana para exibição no gráfico
$dias_semana_pt = [
    'Sun' => 'Dom', 'Mon' => 'Seg', 'Tue' => 'Ter', 'Wed' => 'Qua',
    'Thu' => 'Qui', 'Fri' => 'Sex', 'Sat' => 'Sáb'
];

$dias_grafico = [];
$valores_semana = [];
$total_semana = 0;

// Prepara os dados preenchendo os últimos 7 dias exatos (incluindo dias sem vendas)
for ($i = 6; $i >= 0; $i--) {
    $data_alvo = date('Y-m-d', strtotime("-$i days"));
    $dia_semana_en = date('D', strtotime("-$i days"));
    
    $dias_grafico[] = $dias_semana_pt[$dia_semana_en];
    
    $encontrou = false;
    foreach ($faturamento_semanal_db as $row) {
        if ($row['data_venda'] == $data_alvo) {
            $valores_semana[] = (float)$row['faturamento'];
            $total_semana += (float)$row['faturamento'];
            $encontrou = true;
            break;
        }
    }
    if (!$encontrou) {
        $valores_semana[] = 0;
    }
}

$max_faturamento_semana = max($valores_semana) > 0 ? max($valores_semana) : 1;
?>
<!DOCTYPE html>
<html lang="pt-BR" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Grão & Massa — Dashboard</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=DM+Serif+Display&display=swap" rel="stylesheet">
<link rel="stylesheet" href="CSS/style.css">
<style>
  /* Estilos para o menu de usuário (Dropdown) */
  .user-dropdown {
    display: none; 
    position: absolute; 
    bottom: calc(100% + 10px); 
    left: 0; 
    width: 100%; 
    background: var(--surface, #fff); 
    border: 1px solid var(--border, #e5e8ed); 
    border-radius: 8px; 
    padding: 6px; 
    box-shadow: 0 4px 15px rgba(0,0,0,0.1); 
    z-index: 100;
  }
  .user-dropdown.show { 
    display: block; 
    animation: fadeIn 0.2s ease; 
  }
  .user-dropdown a {
    display: flex; 
    align-items: center; 
    gap: 8px; 
    color: var(--cr, #b00000); 
    text-decoration: none; 
    padding: 10px; 
    border-radius: 6px; 
    font-size: 14px; 
    font-weight: 500;
    transition: background 0.2s ease;
  }
  .user-dropdown a:hover { 
    background: var(--cr-bg, rgba(176,0,0,0.1)); 
  }
  [data-theme="dark"] .user-dropdown { 
    background: var(--surface, #161b27); 
    border-color: var(--border, #2a2f3e); 
    box-shadow: 0 4px 15px rgba(0,0,0,0.4); 
  }
  @keyframes fadeIn { 
    from { opacity: 0; transform: translateY(5px); } 
    to { opacity: 1; transform: translateY(0); } 
  }
</style>
</head>
<body>

<nav class="sb">
  <div class="sb-brand">
    <div class="sb-icon"><svg fill="none" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
    <div class="sb-name">Grão &amp; Massa<span>Padaria &amp; Café</span></div>
  </div>

  <p class="sb-label">Menu</p>
  <ul class="sb-nav">
    <li><a href="index.php" class="sb-link on"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>Dashboard<span class="sb-dot"></span></a></li>
    <li><a href="vendas.php" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 001.97-1.67L23 6H6"/></svg>Caixa / Vendas</a></li>
    <li><a href="cadastrar_pedido.php" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>Pedidos</a></li>
    <li><a href="relatorios.php" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>Relatórios</a></li>
  </ul>

  <p class="sb-label">Cadastros</p>
  <ul class="sb-nav">
    <li><a href="Cadastrar_Cliente.php" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>Clientes</a></li>
    <li><a href="Cadastrar_Funcionario.php" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>Funcionários</a></li>
    <li><a href="Cadastrar_Fornecedor.php" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/></svg>Fornecedores</a></li>
    <li><a href="Cadastrar_Produto.php" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>Produtos</a></li>
  </ul>

  <div class="sb-foot">
    <div style="position: relative; width: 100%;">
      
      <div id="userDropdown" class="user-dropdown">
        <a href="login.php">
          <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="width: 18px; height: 18px;">
              <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
              <polyline points="16 17 21 12 16 7"></polyline>
              <line x1="21" y1="12" x2="9" y2="12"></line>
          </svg>
          Sair do Sistema
        </a>
      </div>

      <div class="sb-user" id="userProfileBtn" style="display: flex; align-items: center; width: 100%; gap: 10px; cursor: pointer; padding: 4px; border-radius: 8px; transition: background 0.2s;" onmouseover="this.style.background='var(--border, #e5e8ed)'" onmouseout="this.style.background='transparent'">
        <div class="sb-av"><?php echo $iniciais; ?></div>
        <div style="flex: 1; min-width: 0;">
          <div class="sb-uname" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?php echo htmlspecialchars($nome_usuario); ?>">
              <?php echo htmlspecialchars($nome_usuario); ?>
          </div>
          <div class="sb-urole" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: var(--ash);">
              <?php echo htmlspecialchars($cargo_usuario); ?>
          </div>
        </div>
        <svg fill="none" stroke="var(--ash)" stroke-width="2" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
            <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
      </div>

    </div>
  </div>
</nav>

<div class="main">

  <header class="top">
    <div class="top-l">
      <div class="badge-pg">
        <div class="badge-icon"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg></div>
        Dashboard
      </div>
      <span class="sep">•</span>
      <span class="date-chip" id="date"></span>
    </div>
    
    <div class="top-r" style="display: flex; gap: 8px; align-items: center;">
      <button class="tb-btn" onclick="toggleTheme()" style="position: relative;">
        <svg class="icon-moon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
        <svg class="icon-sun" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
      </button>
    </div>
  </header>

  <div class="content">
    <main class="dash-main">
      <div class="grid-4">
        <div class="stat-card">
          <div class="stat-title">Vendas Hoje</div>
          <div class="stat-val"><?php echo $vendas_hoje; ?></div>
          <div class="stat-cap">Via DB</div>
        </div>
        <div class="stat-card">
          <div class="stat-title">Faturamento</div>
          <div class="stat-val brand">R$ <?php echo number_format($faturamento, 2, ',', '.'); ?></div>
          <div class="stat-cap">Via DB</div>
        </div>
        <div class="stat-card">
          <div class="stat-title">Ticket Médio</div>
          <div class="stat-val">R$ <?php echo number_format($ticket_medio, 2, ',', '.'); ?></div>
          <div class="stat-cap">Calculado</div>
        </div>
        <div class="stat-card">
          <div class="stat-title">Clientes Atendidos</div>
          <div class="stat-val"><?php echo $visitantes; ?></div>
          <div class="stat-cap">Via DB</div>
        </div>
      </div>

      <div class="sec-title">
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg>
        Telas de Cadastro
        <hr>
      </div>

      <div class="grid-4">
        <a href="Cadastrar_Cliente.php" class="action-card">
          <div class="action-card-img"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg></div>
          <span class="action-card-name">Clientes</span>
        </a>
        <a href="Cadastrar_Produto.php" class="action-card">
          <div class="action-card-img"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg></div>
          <span class="action-card-name">Produtos</span>
        </a>
        <a href="Cadastrar_Funcionario.php" class="action-card">
          <div class="action-card-img"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>
          <span class="action-card-name">Funcionários</span>
        </a>
        <a href="Cadastrar_Fornecedor.php" class="action-card">
          <div class="action-card-img"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg></div>
          <span class="action-card-name">Fornecedores</span>
        </a>
      </div>

      <div class="sec-title" style="margin-top: 8px;">
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
        Faturamento Semanal (Últimos 7 dias)
        <hr>
      </div>

      <div class="stat-card" style="padding: 20px;">
        <div class="chart-container" style="height: 220px; display: flex; align-items: flex-end; gap: 12px; padding: 0 8px;">
            <?php 
            $max_exibicao_semana = max($max_faturamento_semana, 100); 
            for ($i = 0; $i < count($dias_grafico); $i++): 
                $altura = round(($valores_semana[$i] / $max_exibicao_semana) * 100);
                $altura = max($altura, 15); 
                $valor_formatado = number_format($valores_semana[$i], 2, ',', '.');
                $cor = ($valores_semana[$i] > 0) ? 'var(--brand, #d97706)' : 'var(--border-color, #e2e8f0)';
                $opacidade = ($valores_semana[$i] > 0) ? '1' : '0.4';
            ?>
                <div class="chart-col" style="flex: 1; display: flex; flex-direction: column; align-items: center; height: 100%; justify-content: flex-end;">
                    <div class="bar" style="height: <?php echo $altura; ?>%; width: 70%; max-width: 50px; background: <?php echo $cor; ?>; opacity: <?php echo $opacidade; ?>; min-height: 12px; border-radius: 6px 6px 0 0; transition: all 0.3s ease;" title="R$ <?php echo $valor_formatado; ?>"></div>
                    <span class="chart-label" style="font-size: 13px; color: var(--ash); margin-top: 8px; font-weight: 500;"><?php echo $dias_grafico[$i]; ?></span>
                </div>
            <?php endfor; ?>
        </div>
        <div style="text-align: center; font-size: 14px; color: var(--ash); margin-top: 14px; font-weight: 500; border-top: 1px solid var(--border-color, #e2e8f0); padding-top: 12px;">
            Total faturado nos últimos 7 dias: <strong style="color: var(--brand, #d97706); font-size: 16px;">R$ <?php echo number_format($total_semana, 2, ',', '.'); ?></strong>
            <?php if($total_semana == 0): ?>
                <span style="display: block; font-size: 12px; color: var(--ash); margin-top: 4px;">
                    Nenhuma venda registrada nesta semana.
                </span>
            <?php endif; ?>
        </div>
      </div>
      </main>

    <aside class="dash-aside">
      <a href="vendas.php" class="btn-nv">
        <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        NOVA VENDA (F2)
      </a>

      <div class="act-title">Últimas Atividades</div>
      <div class="act-list">
        <?php if(count($ultimas_atividades) > 0): ?>
            <?php foreach($ultimas_atividades as $atividade): ?>
            <div class="act-item">
              <div class="act-icon"><svg fill="none" stroke-width="2" viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 001.97-1.67L23 6H6"/></svg></div>
              <div class="act-info">
                <div class="act-name">Pedido #<?php echo $atividade['id']; ?></div>
                <div class="act-time">Há <?php echo $atividade['minutos_atras']; ?> min via <?php echo $atividade['forma_pagamento'] ?: 'N/I'; ?></div>
              </div>
              <div class="act-val">R$ <?php echo number_format($atividade['valor_total'], 2, ',', '.'); ?></div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="act-item">
              <div class="act-info">
                <div class="act-name">Nenhuma venda hoje.</div>
              </div>
            </div>
        <?php endif; ?>
      </div>
    </aside>
  </div>
</div>

<script>
  // Exibir data atual no cabeçalho
  document.getElementById('date').textContent = new Date().toLocaleDateString('pt-BR',{weekday:'long',day:'numeric',month:'long'}).replace(/^\w/,c=>c.toUpperCase());

  // Controle de Tema Claro/Escuro
  function toggleTheme(){ 
    const d = document.documentElement; 
    const t = d.getAttribute('data-theme') === 'dark' ? 'light' : 'dark'; 
    d.setAttribute('data-theme', t); 
    localStorage.setItem('theme', t); 
  }
  (()=>{ 
    const s = localStorage.getItem('theme'); 
    if(s === 'dark' || (!s && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.setAttribute('data-theme','dark'); 
    }
  })();

  // Atalho F2 para Vendas
  document.addEventListener('keydown', e => { 
    if(e.key === 'F2'){ 
        e.preventDefault(); 
        window.location.href = 'vendas.php'; 
    } 
  });

  // Toggle do menu de Usuário (Sair)
  const userProfileBtn = document.getElementById('userProfileBtn');
  const userDropdown = document.getElementById('userDropdown');

  userProfileBtn.addEventListener('click', (e) => {
      e.stopPropagation(); // Evita que o clique feche imediatamente
      userDropdown.classList.toggle('show');
  });

  // Fecha o menu de usuário se clicar fora dele
  document.addEventListener('click', (e) => {
      if (!userDropdown.contains(e.target) && !userProfileBtn.contains(e.target)) {
          userDropdown.classList.remove('show');
      }
  });
</script>
</body>
</html>