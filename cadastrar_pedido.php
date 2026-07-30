<?php

session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: site.html");
    exit();
}

$nome_usuario  = $_SESSION['usuario_nome']  ?? 'Usuário';
$cargo_usuario = $_SESSION['usuario_cargo'] ?? 'Funcionário';

$partes_nome = explode(' ', trim($nome_usuario));
$iniciais = strtoupper(substr($partes_nome[0], 0, 1));
if (count($partes_nome) > 1) {
    $iniciais .= strtoupper(substr(end($partes_nome), 0, 1));
}

$permissoes = [
    'admin'   => ['dashboard', 'vendas', 'pedidos', 'estoque', 'relatorios', 'clientes', 'funcionarios', 'fornecedores', 'produtos'],
    'padeiro' => ['estoque', 'produtos'],
    'caixa'   => ['vendas', 'pedidos', 'clientes'],
];

$cargo_normalizado = strtolower(trim($cargo_usuario));

function podeAcessar($tela, $permissoes, $cargo) {
    return isset($permissoes[$cargo]) && in_array($tela, $permissoes[$cargo]);
}

// Bloqueia o acesso de quem não tem permissão para a tela de Pedidos
if (!podeAcessar('pedidos', $permissoes, $cargo_normalizado)) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Grão & Massa — Pedidos</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=DM+Serif+Display&display=swap" rel="stylesheet">
<link rel="stylesheet" href="CSS/style.css">

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<style>
 
  .page-title { font-size: 20px; font-weight: 700; margin-bottom: 4px; color: var(--ink); }
  .page-sub { font-size: 13px; color: var(--ash); margin-bottom: 20px; }
  
  .content-pad { flex: 1; padding: 24px; overflow: hidden; display: flex; flex-direction: column; }
  
  .split { display: grid; grid-template-columns: 1fr 320px; gap: 20px; flex: 1; overflow: hidden; }
  
  .panel { background: var(--white); border-radius: var(--r2); border: 1px solid var(--border); display: flex; flex-direction: column; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.02); }
  .panel-header { padding: 16px 20px; border-bottom: 1px solid var(--border); font-size: 14px; font-weight: 600; color: var(--ink); display: flex; align-items: center; justify-content: space-between; }
  .panel-body { flex: 1; overflow-y: auto; }
  
  .pedidos-wrap { flex: 1; overflow: auto; }
  table { width: 100%; border-collapse: collapse; font-size: 13.5px; }
  thead th { background: var(--surface); padding: 12px 20px; text-align: left; font-size: 11.5px; font-weight: 600; text-transform: uppercase; color: var(--ash); letter-spacing: .5px; white-space: nowrap; border-bottom: 1px solid var(--border); position: sticky; top: 0; z-index: 10; }
  tbody tr { border-bottom: 1px solid var(--border); cursor: pointer; transition: background var(--t); }
  tbody tr:hover { background: var(--surface); }
  tbody tr.selected-row { background: var(--cr-bg); }
  tbody td { padding: 14px 20px; color: var(--ink); vertical-align: middle; }
  
  .badge { display: inline-flex; align-items: center; gap: 4px; font-size: 11.5px; font-weight: 600; padding: 4px 10px; border-radius: 20px; }
  
  .badge-doce { background: rgba(190, 24, 93, 0.1); color: #be185d; }
  .badge-salgado { background: rgba(146, 64, 14, 0.1); color: #92400e; }
  .badge-bebida { background: rgba(29, 78, 216, 0.1); color: #1d4ed8; }
  .badge-misto { background: rgba(6, 95, 70, 0.1); color: #065f46; }
  
  .badge-pendente { background: rgba(146, 64, 14, 0.1); color: #92400e; }
  .badge-producao { background: rgba(30, 64, 175, 0.1); color: #1e40af; }
  .badge-pronto { background: rgba(6, 95, 70, 0.1); color: #065f46; }
  .badge-entregue { background: var(--surface); color: var(--ash); border: 1px solid var(--border); }
  
  [data-theme=dark] .badge-doce { color: #f9a8d4; }
  [data-theme=dark] .badge-salgado { color: #fcd34d; }
  [data-theme=dark] .badge-bebida { color: #93c5fd; }
  [data-theme=dark] .badge-misto { color: #6ee7b7; }
  [data-theme=dark] .badge-pendente { color: #fcd34d; }
  [data-theme=dark] .badge-producao { color: #93c5fd; }
  [data-theme=dark] .badge-pronto { color: #6ee7b7; }
  
  .empty { display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; min-height: 250px; gap: 12px; color: var(--ash); padding: 40px; }
  .empty svg { width: 50px; height: 50px; stroke: var(--ash); stroke-width: 1.5; }
  .empty-text { font-size: 14px; text-align: center; line-height: 1.6; }
  
  .acoes-bar { padding: 14px 20px; border-top: 1px solid var(--border); display: flex; flex-direction: column; gap: 12px; background: var(--white); margin-top: auto; }
  .btn-acao { font-family: inherit; font-size: 13px; font-weight: 600; padding: 10px 18px; border-radius: 8px; border: 1px solid transparent; cursor: pointer; transition: all var(--t); display: flex; align-items: center; gap: 6px; justify-content: center; width: 100%;}
  .btn-acao svg { width: 16px; height: 16px; }
  
  .btn-novo { background: var(--cr); color: #fff; }
  .btn-novo:hover { background: var(--cr2); }
  .btn-edit { background: var(--surface); color: var(--ink); border-color: var(--border); }
  .btn-edit:hover:not(:disabled) { background: var(--border); }
  .btn-excluir { background: rgba(190, 24, 93, 0.1); color: #be185d; border-color: rgba(190, 24, 93, 0.2); }
  .btn-excluir:hover:not(:disabled) { background: rgba(190, 24, 93, 0.18); }
  [data-theme=dark] .btn-excluir { color: #f9a8d4; }
  .btn-acao:disabled { opacity: 0.4; cursor: not-allowed; }

  .checkout-info { padding: 20px; display: flex; flex-direction: column; gap: 16px; }
  .checkout-item { display: flex; justify-content: space-between; font-size: 13.5px; padding-bottom: 12px; border-bottom: 1px dashed var(--border); }
  
  .form-group { display: flex; flex-direction: column; gap: 6px; }
  .form-group label { font-size: 12px; font-weight: 600; color: var(--ink); }
  .form-group select { width: 100%; font-family: inherit; font-size: 14px; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; background: var(--surface); color: var(--ink); outline: none; transition: border-color var(--t); }
  .form-group select:focus { border-color: var(--cr); }
  .form-group select:disabled { opacity: 0.5; cursor: not-allowed; }
  
  .toast { position: fixed; bottom: 24px; right: 24px; background: var(--night); color: #fff; font-size: 14px; font-weight: 500; padding: 14px 24px; border-radius: var(--r); z-index: 2000; opacity: 0; transform: translateY(15px); transition: all .3s; pointer-events: none; border: 1px solid rgba(255,255,255,0.1); box-shadow: 0 10px 30px rgba(0,0,0,0.2); display: flex; align-items: center; gap: 8px; }
  .toast.show { opacity: 1; transform: translateY(0); }

  .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); display: none; align-items: center; justify-content: center; z-index: 1000; backdrop-filter: blur(2px); }
  .modal-overlay.show { display: flex; }
  .modal-box { background: var(--white); border-radius: var(--r2); width: 100%; max-width: 480px; max-height: 85vh; display: flex; flex-direction: column; overflow: hidden; box-shadow: 0 20px 50px rgba(0,0,0,0.25); position: relative;}
  .modal-header { padding: 18px 22px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
  .modal-header h3 { font-size: 15px; font-weight: 700; color: var(--ink); margin: 0; display: flex; align-items: center; gap: 8px; }
  .modal-close { background: none; border: none; font-size: 22px; line-height: 1; color: var(--ash); cursor: pointer; padding: 0 4px; }
  .modal-close:hover { color: var(--ink); }
  .modal-body { padding: 20px 22px; overflow-y: auto; flex: 1; display: flex; flex-direction: column; gap: 18px; }
  .modal-item-row { display: flex; align-items: center; gap: 10px; padding: 8px 0; border-bottom: 1px dashed var(--border); }
  .modal-item-nome { flex: 1; font-size: 13.5px; color: var(--ink); }
  .modal-item-qtd { width: 56px; font-family: inherit; font-size: 13px; padding: 6px 8px; border: 1px solid var(--border); border-radius: 6px; background: var(--surface); color: var(--ink); text-align: center; }
  .modal-item-remove { background: none; border: none; cursor: pointer; font-size: 15px; padding: 4px 6px; border-radius: 6px; }
  .modal-item-remove:hover { background: rgba(190,24,93,0.1); }
  .modal-empty { font-size: 13px; color: var(--ash); text-align: center; padding: 20px 0; }
  .modal-add-item { display: flex; flex-direction: column; gap: 8px; padding-top: 6px; border-top: 1px solid var(--border); }
  .modal-add-item label { font-size: 12px; font-weight: 600; color: var(--ink); }
  .modal-add-row { display: flex; gap: 8px; }
  .modal-add-row select { flex: 1; font-family: inherit; font-size: 13px; padding: 8px 10px; border: 1px solid var(--border); border-radius: 8px; background: var(--surface); color: var(--ink); }
  .modal-add-row input { width: 60px; font-family: inherit; font-size: 13px; padding: 8px 10px; border: 1px solid var(--border); border-radius: 8px; background: var(--surface); color: var(--ink); text-align: center; }
  .btn-add-item { font-family: inherit; font-size: 12.5px; font-weight: 600; padding: 8px 12px; border-radius: 8px; border: 1px solid var(--border); background: var(--surface); color: var(--ink); cursor: pointer; white-space: nowrap; }
  .btn-add-item:hover { background: var(--border); }
  .modal-footer { padding: 16px 22px; border-top: 1px solid var(--border); display: flex; gap: 10px; }
  .modal-footer .btn-acao { width: auto; flex: 1; }

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
    <div class="sb-icon" style="background: transparent; border: none; padding: 0;">
      <img src="uploads/logo.jpg" alt="Logo Grão & Massa" style="width: 100%; height: 100%; object-fit: contain; border-radius: 6px;">
    </div>
    <div class="sb-name">Grão &amp; Massa<span>Padaria &amp; Café</span></div>
  </div>

  <p class="sb-label">Menu</p>
  <ul class="sb-nav">
    <?php if (podeAcessar('dashboard', $permissoes, $cargo_normalizado)): ?>
    <li><a href="index.php" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>Dashboard</a></li>
    <?php endif; ?>

    <?php if (podeAcessar('vendas', $permissoes, $cargo_normalizado)): ?>
    <li><a href="vendas.php" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 001.97-1.67L23 6H6"/></svg>Caixa / Vendas</a></li>
    <?php endif; ?>

    <?php if (podeAcessar('pedidos', $permissoes, $cargo_normalizado)): ?>
    <li><a href="cadastrar_pedido.php" class="sb-link on"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>Pedidos<span class="sb-dot"></span></a></li>
    <?php endif; ?>

    <?php if (podeAcessar('estoque', $permissoes, $cargo_normalizado)): ?>
    <li><a href="gerenciar_estoque.php" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>Estoque</a></li>
    <?php endif; ?>

    <?php if (podeAcessar('relatorios', $permissoes, $cargo_normalizado)): ?>
    <li><a href="Relatorios.php" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>Relatórios</a></li>
    <?php endif; ?>
  </ul>

  <?php if (podeAcessar('clientes', $permissoes, $cargo_normalizado) || podeAcessar('funcionarios', $permissoes, $cargo_normalizado) || podeAcessar('fornecedores', $permissoes, $cargo_normalizado) || podeAcessar('produtos', $permissoes, $cargo_normalizado)): ?>
  <p class="sb-label">Cadastros</p>
  <ul class="sb-nav">
    <?php if (podeAcessar('clientes', $permissoes, $cargo_normalizado)): ?>
    <li><a href="Cadastrar_Cliente.php" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>Clientes</a></li>
    <?php endif; ?>

    <?php if (podeAcessar('funcionarios', $permissoes, $cargo_normalizado)): ?>
    <li><a href="Cadastrar_Funcionario.php" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>Funcionários</a></li>
    <?php endif; ?>

    <?php if (podeAcessar('fornecedores', $permissoes, $cargo_normalizado)): ?>
    <li><a href="Cadastrar_Fornecedor.php" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/></svg>Fornecedores</a></li>
    <?php endif; ?>

    <?php if (podeAcessar('produtos', $permissoes, $cargo_normalizado)): ?>
    <li><a href="Cadastrar_Produto.php" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>Produtos</a></li>
    <?php endif; ?>
  </ul>
  <?php endif; ?>

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
        <div class="badge-icon"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg></div>
        Fila de Pedidos
      </div>
      <span class="sep">•</span>
      <span class="date-chip" id="dataHoje"></span>
    </div>
    <div class="top-r" style="display: flex; gap: 8px; align-items: center;">

      <a href="vendas.php" class="btn-acao btn-edit" style="text-decoration: none; padding: 8px 14px; margin-right: 10px; width: auto;">
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 16px; height: 16px;"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        Voltar para Vendas
      </a>

      <button class="tb-btn" onclick="toggleTheme()">
        <svg class="icon-moon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
        <svg class="icon-sun" style="display: none;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
      </button>
    </div>
  </header>

  <div class="content">
    <div class="content-pad">
      
      <div style="margin-bottom: 24px;">
        <h1 class="page-title">Pedidos Pendentes</h1>
        <p class="page-sub">Selecione um pedido pendente na tabela para finalizá-lo e escolher a forma de pagamento.</p>
      </div>

      <div class="split">

        <div class="panel">
          <div class="panel-header" id="headerPedidos">
            <span>Todos os Pedidos Pendentes</span>
            <span style="font-size:12px;color:var(--ash);font-weight:500;" id="contadorPendentes">0 registros</span>
          </div>
          
          <div class="pedidos-wrap">
            <div id="tabelaPedidosWrap"></div>
          </div>
        </div>

        <div class="panel">
          <div class="panel-header">
            <span>Finalizar Pedido</span>
          </div>
          <div class="panel-body" id="painelFinalizar">
        
          </div>
          
          <div class="acoes-bar">
            <div class="form-group">
              <label>Forma de Pagamento</label>
              <select id="formaPagamento" disabled>
                <option value="Pix">Pix</option>
                <option value="Cartão de Crédito">Cartão de Crédito</option>
                <option value="Cartão de Débito">Cartão de Débito</option>
                <option value="Dinheiro">Dinheiro</option>
              </select>
            </div>

            <div style="display: flex; gap: 8px;">
              <button class="btn-acao btn-edit" id="btnEditar" onclick="abrirModalEdicao()" disabled style="flex: 1;">
                ✎ Editar
              </button>

              <button class="btn-acao btn-excluir" id="btnExcluir" onclick="abrirModalExcluir()" disabled style="flex: 1;">
                🗑 Excluir
              </button>
            </div>

            <button class="btn-acao btn-novo" id="btnFinalizar" onclick="abrirModalCupom()" disabled>
              ✔ Confirmar Pagamento
            </button>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<div class="toast" id="toast">
  <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 20px; height: 20px;"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
  <span id="toastMsg">Mensagem</span>
</div>

<div class="modal-overlay" id="modalOverlay">
  <div class="modal-box">
    <div class="modal-header">
      <h3>Editar Pedido <span id="modalPedidoId"></span></h3>
      <button class="modal-close" onclick="fecharModalEdicao()">&times;</button>
    </div>
    <div class="modal-body">
      <div id="modalItensLista"></div>

      <div class="modal-add-item">
        <label>Adicionar Produto</label>
        <div class="modal-add-row">
          <select id="modalSelectProduto"></select>
          <input type="number" id="modalQtdNovo" min="1" value="1">
          <button class="btn-add-item" onclick="adicionarItemModal()">+ Adicionar</button>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn-acao btn-edit" onclick="fecharModalEdicao()">Cancelar</button>
      <button class="btn-acao btn-novo" onclick="salvarEdicaoPedido()">💾 Salvar Alterações</button>
    </div>
  </div>
</div>

<div class="modal-overlay" id="modalExcluir">
  <div class="modal-box" style="max-width: 380px;">
    <button class="modal-close" onclick="fecharModalExcluir()" style="position: absolute; top: 16px; right: 16px;">&times;</button>
    <div class="modal-header" style="border-bottom: none; padding-bottom: 0;">
      <h3 style="font-size: 18px;">
        <svg fill="none" stroke="#be185d" stroke-width="2" viewBox="0 0 24 24" style="width:24px; height:24px;"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
        Excluir Pedido?
      </h3>
    </div>
    <div class="modal-body" style="padding-top: 10px; padding-bottom: 0;">
      <p style="font-size: 14px; color: var(--ash); line-height: 1.5; margin: 0;">Tem certeza que deseja excluir o pedido <strong id="modalExcluirId"></strong>? Essa ação não pode ser desfeita.</p>
    </div>
    <div class="modal-footer" style="margin-top: 18px; border-top: none;">
      <button class="btn-acao btn-edit" onclick="fecharModalExcluir()">Cancelar</button>
      <button class="btn-acao btn-excluir" onclick="confirmarExclusao()" style="border: none;">🗑 Sim, excluir</button>
    </div>
  </div>
</div>

<div class="modal-overlay" id="modalCupom">
  <div class="modal-box" style="max-width: 380px;">
    <button class="modal-close" onclick="fecharModalCupom()" style="position: absolute; top: 16px; right: 16px;">&times;</button>
    <div class="modal-header" style="border-bottom: none; padding-bottom: 0;">
      <h3 style="font-size: 18px;">
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:24px; height:24px; color:var(--cr);"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
        Emitir Cupom Fiscal?
      </h3>
    </div>
    <div class="modal-body" style="padding-top: 10px; padding-bottom: 0;">
      <p style="font-size: 14px; color: var(--ash); line-height: 1.5; margin: 0;">O pagamento está pronto para ser confirmado. Você deseja gerar e baixar o PDF do cupom fiscal deste pedido?</p>
    </div>
    <div class="modal-footer" style="margin-top: 18px; border-top: none;">
      <button class="btn-acao btn-edit" onclick="finalizarPedido(false)">Não, apenas finalizar</button>
      <button class="btn-acao btn-novo" onclick="finalizarPedido(true)">Sim, gerar cupom</button>
    </div>
  </div>
</div>

<script>
function toggleTheme(){ 
  const d = document.documentElement; 
  const t = d.getAttribute('data-theme') === 'dark' ? 'light' : 'dark'; 
  d.setAttribute('data-theme', t); 
  localStorage.setItem('theme', t); 
  updateThemeIcon(t);
}

function updateThemeIcon(t) {
  const moon = document.querySelector('.icon-moon');
  const sun = document.querySelector('.icon-sun');
  if (moon && sun) {
    if (t === 'dark') { moon.style.display = 'none'; sun.style.display = 'block'; }
    else { moon.style.display = 'block'; sun.style.display = 'none'; }
  }
}

(()=>{ 
  const s = localStorage.getItem('theme'); 
  const t = (s === 'dark' || (!s && window.matchMedia('(prefers-color-scheme: dark)').matches)) ? 'dark' : 'light';
  document.documentElement.setAttribute('data-theme', t); 
  window.addEventListener('DOMContentLoaded', () => updateThemeIcon(t));
})();

let pedidos = [];               
let pedidoSelecionado = null;
let produtosDisponiveis = [];   
let itensEdicao = [];
let estoquePorProduto = {}; // guarda o estoque de cada produto pelo id (chave string)

const tipoBadge  = { doce:"badge-doce", salgado:"badge-salgado", bebida:"badge-bebida", misto:"badge-misto" };
const tipoLabel  = { doce:"Doce", salgado:"Salgado", bebida:"Bebida", misto:"Misto" };
const statusBadge = { "Pendente":"badge-pendente", "Em Produção":"badge-producao", "Pronto":"badge-pronto", "Entregue":"badge-entregue", "Concluído":"badge-entregue" };
const statusLabel = { "Entregue":"Concluído" };

function categoriaParaTipo(categorias) {
  const unicas = [...new Set(categorias.map(c => (c || '').toLowerCase()))];
  if (unicas.length > 1) return 'misto';
  const c = unicas[0] || '';
  if (c.includes('doce') || c.includes('sobremesa')) return 'doce';
  if (c.includes('salg')) return 'salgado';
  if (c.includes('bebida') || c.includes('suco') || c.includes('caf')) return 'bebida';
  return 'misto';
}

async function carregarPedidos() {
  try {
    const resp = await fetch('PHP/listar_pedidos.php');
    const dados = await resp.json();

    if (!Array.isArray(dados)) {
      showToast(dados.mensagem || 'Erro ao carregar pedidos.');
      pedidos = [];
    } else {
      pedidos = dados.map(p => ({
        id: p.id,
        pedido_id: p.pedido_id,
        cliente: p.cliente,
        telefone: p.telefone,
        status: p.status,
        itens: p.itens,
        tipo: categoriaParaTipo(p.itens.map(i => i.categoria)),
        qtd: p.itens.reduce((soma, i) => soma + i.quantidade, 0)
      }));
    }
  } catch (e) {
    showToast('Não foi possível conectar ao banco de dados.');
    pedidos = [];
  }

  renderPedidosPendentes();
  renderPainelFinalizar();
}

async function carregarProdutosAtivos() {
  try {
    const resp = await fetch('PHP/listar_produtos_ativos.php');
    const dados = await resp.json();
    produtosDisponiveis = Array.isArray(dados) ? dados : [];

    // Monta um mapa rápido de id -> estoque disponível, usado nas validações do modal
    estoquePorProduto = {};
    let faltaCampoEstoque = false;

    produtosDisponiveis.forEach(p => {
      if (p.estoque === undefined || p.estoque === null) {
        faltaCampoEstoque = true;
        // Sem informação de estoque -> não bloqueia (undefined), em vez de tratar como 0
        estoquePorProduto[String(p.id)] = undefined;
      } else {
        estoquePorProduto[String(p.id)] = parseInt(p.estoque, 10) || 0;
      }
    });

    if (faltaCampoEstoque) {
      console.warn(
        'AVISO: o PHP/listar_produtos_ativos.php não está retornando o campo "estoque" para um ou mais produtos. ' +
        'A validação de estoque no modal de edição ficará desativada para esses produtos até isso ser corrigido no backend.'
      );
    }
  } catch (e) {
    produtosDisponiveis = [];
    estoquePorProduto = {};
  }
}

const hoje = new Date();
const dataFormatada = hoje.toLocaleDateString('pt-BR', { weekday: 'long', day: 'numeric', month: 'long' });
document.getElementById("dataHoje").textContent = dataFormatada.charAt(0).toUpperCase() + dataFormatada.slice(1);

function renderPedidosPendentes() {
  const wrap = document.getElementById("tabelaPedidosWrap");
  const lista = pedidos.filter(p => p.status === 'Pendente');
  
  document.getElementById("contadorPendentes").textContent = `${lista.length} pendente${lista.length !== 1 ? 's' : ''}`;

  if (lista.length === 0) {
    wrap.innerHTML = `
      <div class="empty">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
        <div class="empty-text">Nenhum pedido pendente no momento.</div>
      </div>`;
    return;
  }

  wrap.innerHTML = `
    <table>
      <thead>
        <tr>
          <th># ID</th>
          <th>Tipo</th>
          <th>Nome</th>
          <th>Qtd</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        ${lista.map(p => `
          <tr onclick="selecionarPedido('${p.id}')" class="${(pedidoSelecionado && pedidoSelecionado.id === p.id) ? 'selected-row' : ''}">
            <td><strong>${p.id}</strong></td>
            <td><span class="badge ${tipoBadge[p.tipo]}">${tipoLabel[p.tipo]}</span></td>
            <td>${p.cliente}</td>
            <td>${p.qtd} un.</td>
            <td><span class="badge ${statusBadge[p.status] || 'badge-pendente'}">${statusLabel[p.status] || p.status}</span></td>
          </tr>
        `).join('')}
      </tbody>
    </table>`;
}

function selecionarPedido(id) {
  pedidoSelecionado = pedidos.find(p => p.id === id);
  renderPedidosPendentes(); 
  renderPainelFinalizar();  
}

function renderPainelFinalizar() {
  const painel = document.getElementById("painelFinalizar");
  const btn = document.getElementById("btnFinalizar");
  const btnEditar = document.getElementById("btnEditar");
  const btnExcluir = document.getElementById("btnExcluir");
  const selectPgto = document.getElementById("formaPagamento");

  if (!pedidoSelecionado) {
    painel.innerHTML = `
      <div class="empty" style="min-height: 200px; padding: 20px;">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:30px; height:30px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" /></svg>
        <div class="empty-text" style="font-size: 12.5px;">Selecione um pedido ao lado para finalizar.</div>
      </div>`;
    btn.disabled = true;
    btnEditar.disabled = true;
    if (btnExcluir) btnExcluir.disabled = true;
    selectPgto.disabled = true;
    selectPgto.value = "Pix";
    return;
  }

  const itensHtml = pedidoSelecionado.itens.map(i => `
    <div class="checkout-item">
      <span>${i.quantidade}x ${i.produto}</span>
    </div>
  `).join('');

  painel.innerHTML = `
    <div class="checkout-info">
      <div>
        <div style="font-size: 12px; color: var(--ash);">Cliente</div>
        <div style="font-weight: 600; color: var(--ink);">${pedidoSelecionado.cliente}</div>
      </div>
      
      <div style="border-top: 1px solid var(--border); padding-top: 16px;">
        <div style="font-size: 12px; color: var(--ash); margin-bottom: 8px;">Itens do Pedido (${pedidoSelecionado.id})</div>
        ${itensHtml}
      </div>
    </div>
  `;
  
  btn.disabled = false;
  btnEditar.disabled = false;
  if (btnExcluir) btnExcluir.disabled = false;
  selectPgto.disabled = false;
}

function abrirModalEdicao() {
  if (!pedidoSelecionado) return;

  itensEdicao = pedidoSelecionado.itens.map(i => ({ ...i }));

  document.getElementById('modalPedidoId').textContent = pedidoSelecionado.id;
  preencherSelectProdutos();
  renderItensModal();
  document.getElementById('modalOverlay').classList.add('show');
}

function fecharModalEdicao() {
  document.getElementById('modalOverlay').classList.remove('show');
}

function preencherSelectProdutos() {
  const sel = document.getElementById('modalSelectProduto');
  if (produtosDisponiveis.length === 0) {
    sel.innerHTML = `<option value="">Nenhum produto disponível</option>`;
    return;
  }
  sel.innerHTML = produtosDisponiveis.map(p =>
    `<option value="${p.id}" data-preco="${p.preco}" data-estoque="${p.estoque}">${p.nome_produto}</option>`
  ).join('');
}

function renderItensModal() {
  const wrap = document.getElementById('modalItensLista');

  if (itensEdicao.length === 0) {
    wrap.innerHTML = `<div class="modal-empty">Nenhum item no pedido. Adicione algo abaixo.</div>`;
    return;
  }

  wrap.innerHTML = itensEdicao.map((item, idx) => `
    <div class="modal-item-row">
      <span class="modal-item-nome">${item.produto}</span>
      <input
        type="number"
        min="1"
        value="${item.quantidade}"
        class="modal-item-qtd"
        onchange="alterarQtdModal(${idx}, this.value)"
      >
      <button class="modal-item-remove" title="Remover item" onclick="removerItemModal(${idx})">🗑</button>
    </div>
  `).join('');
}

function alterarQtdModal(idx, valor) {
  let q = parseInt(valor, 10);
  if (!q || q < 1) q = 1;

  const item = itensEdicao[idx];
  const estoqueDisponivel = estoquePorProduto[String(item.produto_id)];

  // Se não tivermos a informação de estoque do produto (campo ausente no PHP,
  // ou produto que saiu da lista de ativos), não bloqueia a alteração.
  if (estoqueDisponivel !== undefined && estoqueDisponivel !== null && q > estoqueDisponivel) {
    showToast(`Estoque insuficiente! Disponível: ${estoqueDisponivel} un. de "${item.produto}".`);
    q = estoqueDisponivel > 0 ? estoqueDisponivel : 1;
  }

  item.quantidade = q;
  renderItensModal(); // reforça visualmente o valor corrigido no input
}

function removerItemModal(idx) {
  itensEdicao.splice(idx, 1);
  renderItensModal();
}

function adicionarItemModal() {
  const sel = document.getElementById('modalSelectProduto');
  const qtdInput = document.getElementById('modalQtdNovo');
  const produtoId = sel.value;

  if (!produtoId) {
    showToast('Selecione um produto para adicionar.');
    return;
  }

  const qtd = parseInt(qtdInput.value, 10) || 1;
  const opt = sel.options[sel.selectedIndex];
  const nome = opt.textContent;
  const preco = parseFloat(opt.dataset.preco);

  // Se o atributo data-estoque não existir (PHP não retornou o campo), não bloqueia
  const estoqueRaw = opt.dataset.estoque;
  const temInfoEstoque = estoqueRaw !== undefined && estoqueRaw !== '' && estoqueRaw !== 'undefined';
  const estoqueDisponivel = temInfoEstoque ? (parseInt(estoqueRaw, 10) || 0) : null;

  const existente = itensEdicao.find(i => String(i.produto_id) === String(produtoId));
  const qtdJaNoPedido = existente ? existente.quantidade : 0;
  const qtdTotalDesejada = qtdJaNoPedido + qtd;

  // Só bloqueia se realmente tivermos a informação de estoque do produto
  if (temInfoEstoque && qtdTotalDesejada > estoqueDisponivel) {
    showToast(`Estoque insuficiente! Disponível: ${estoqueDisponivel} un. de "${nome}".`);
    return;
  }

  if (existente) {
    existente.quantidade = qtdTotalDesejada;
  } else {
    itensEdicao.push({
      produto_id: produtoId,
      produto: nome,
      quantidade: qtd,
      preco_unitario: preco,
      categoria: ''
    });
  }

  qtdInput.value = 1;
  renderItensModal();
}

async function salvarEdicaoPedido() {
  if (!pedidoSelecionado) return;

  if (itensEdicao.length === 0) {
    showToast('O pedido precisa ter ao menos um item.');
    return;
  }

  try {
    const response = await fetch('PHP/salvar_edicao_pedido.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        pedido_id: pedidoSelecionado.pedido_id,
        itens: itensEdicao.map(i => ({
          produto_id: i.produto_id,
          quantidade: i.quantidade
        }))
      })
    });

    const textoCru = await response.text();
    const res = JSON.parse(textoCru);

    if (res.sucesso) {
      showToast('Pedido atualizado com sucesso!');
      fecharModalEdicao();

      const pedidoIdAtual = pedidoSelecionado.pedido_id;
      await carregarPedidos();
      pedidoSelecionado = pedidos.find(p => p.pedido_id === pedidoIdAtual) || null;
      renderPedidosPendentes();
      renderPainelFinalizar();
    } else {
      showToast(res.mensagem || 'Erro ao salvar edição do pedido.');
    }
  } catch (erro) {
    showToast('Erro de conexão ao salvar a edição. Veja o console (F12).');
    console.error('Erro ao salvar edição do pedido:', erro);
  }
}

function abrirModalExcluir() {
  if (!pedidoSelecionado) return;
  document.getElementById('modalExcluirId').textContent = pedidoSelecionado.id;
  document.getElementById('modalExcluir').classList.add('show');
}

function fecharModalExcluir() {
  document.getElementById('modalExcluir').classList.remove('show');
}

async function confirmarExclusao() {
  if (!pedidoSelecionado) return;

  const pedidoIdAtual = pedidoSelecionado.pedido_id;
  const btnExcluir = document.getElementById('btnExcluir');
  if (btnExcluir) btnExcluir.disabled = true;

  try {
    const response = await fetch(`PHP/apagar_pedido.php?id=${pedidoIdAtual}`);
    const textoCru = await response.text();
    const res = JSON.parse(textoCru);

    if (res.sucesso) {
      showToast('Pedido excluído com sucesso!');
      fecharModalExcluir();
      pedidoSelecionado = null;
      await carregarPedidos();
    } else {
      showToast(res.mensagem || 'Erro ao excluir o pedido.');
      if (btnExcluir) btnExcluir.disabled = false;
    }
  } catch (erro) {
    showToast('Erro de conexão ao excluir o pedido. Veja o console (F12).');
    console.error('Erro ao excluir pedido:', erro);
    if (btnExcluir) btnExcluir.disabled = false;
  }
}

function abrirModalCupom() {
  if (!pedidoSelecionado) return;
  document.getElementById("modalCupom").classList.add("show");
}

function fecharModalCupom() {
  document.getElementById("modalCupom").classList.remove("show");
}

async function finalizarPedido(gerarNota) {
  if (!pedidoSelecionado) return;
  
  fecharModalCupom(); 
  
  const formaPag = document.getElementById("formaPagamento").value;
  const btn = document.getElementById("btnFinalizar");
  
  btn.disabled = true;
  btn.textContent = "Processando...";

  try {
    const response = await fetch('finalizar_pagamento.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        pedido_id: pedidoSelecionado.pedido_id, 
        forma_pagamento: formaPag
      })
    });

    const textoCru = await response.text();
    console.log("RESPOSTA DO SERVIDOR:", textoCru);
    const res = JSON.parse(textoCru);

    if (res.sucesso) {
      showToast(`Pedido pago e finalizado via ${formaPag}!`);
      
      if (gerarNota) {
        if (window.jspdf) {
          const { jsPDF } = window.jspdf;
          
          const numItens = pedidoSelecionado.itens.length;
          const alturaBase = 140; 
          const alturaPorItem = 8; 
          const alturaTotal = alturaBase + (numItens * alturaPorItem);

          const doc = new jsPDF({
            orientation: 'portrait',
            unit: 'mm',
            format: [80, alturaTotal]
          });

          doc.setFont("courier", "normal");
          let y = 6;
          const centro = 40;

          doc.setFontSize(10);
          doc.setFont("courier", "bold");
          doc.text("GRAO & MASSA PADARIA E CAFE", centro, y, { align: "center" }); y += 4;
          
          doc.setFontSize(8);
          doc.setFont("courier", "normal");
          doc.text("AV JOAO COLIN, 100 - CENTRO", centro, y, { align: "center" }); y += 4;
          doc.text("CEP: 89201-000 - JOINVILLE - SC", centro, y, { align: "center" }); y += 4;
          doc.text("CNPJ: 12.345.678/0001-90", 2, y); y += 4;
          doc.text("IE: 123.456.789", 2, y); y += 4;
          doc.text("IM: 987.654.321", 2, y); y += 4;

          doc.text("---------------------------------------------", centro, y, { align: "center" }); y += 4;

          const agora = new Date();
          const dataStr = agora.toLocaleDateString('pt-BR');
          const horaStr = agora.toLocaleTimeString('pt-BR');
          const ccf = String(Math.floor(Math.random() * 90000) + 10000);
          const coo = String(Math.floor(Math.random() * 900000) + 100000);
          
          doc.text(`${dataStr} ${horaStr}V  CCF:${ccf}  COO:${coo}`, 2, y); y += 4;

          doc.setFontSize(11);
          doc.setFont("courier", "bold");
          doc.text("CUPOM FISCAL", centro, y, { align: "center" }); y += 4;
          
          doc.setFontSize(8);
          doc.setFont("courier", "normal");
          
          doc.text("ITEM CÓDIGO      DESCRIÇÃO", 2, y); y += 3;
          doc.text("QTD UN. VL UNIT( R$)  ST       VL ITEM( R$)", 2, y); y += 3;
          doc.text("---------------------------------------------", centro, y, { align: "center" }); y += 4;

          let totalNota = 0;

          pedidoSelecionado.itens.forEach((item, index) => {
            const numItem = String(index + 1).padStart(3, '0');
            const codItem = "00000000000" + (100 + index);
            const descItem = item.produto.substring(0, 16).toUpperCase();
            
            const precoUnitario = item.preco_unitario ? parseFloat(item.preco_unitario) : 15.00;
            const subtotalItem = item.quantidade * precoUnitario;
            totalNota += subtotalItem;

            doc.text(`${numItem}  ${codItem}  ${descItem}`, 2, y); y += 4;
            
            const linhaQtd = `${item.quantidade}UN X ${precoUnitario.toFixed(2).replace('.',',')}`;
            const impostos = "02T18,00%";
            const linhaSubtotal = `${subtotalItem.toFixed(2).replace('.',',')}G`;
            doc.text(`    ${linhaQtd}     ${impostos}      ${linhaSubtotal}`, 2, y); y += 4;
          });

          doc.text("---------------------------------------------", centro, y, { align: "center" }); y += 5;

          doc.setFontSize(10);
          doc.setFont("courier", "bold");
          doc.text("TOTAL R$", 2, y);
          doc.text(`${totalNota.toFixed(2).replace('.', ',')}`, 78, y, { align: "right" }); y += 5;
          
          doc.setFont("courier", "normal");
          doc.setFontSize(9);
          const pagNome = formaPag.toUpperCase();
          doc.text(`Pgto ${pagNome}`, 2, y);
          doc.text(`${totalNota.toFixed(2).replace('.', ',')}`, 78, y, { align: "right" }); y += 5;

          doc.setFontSize(7);
          const impostosTotais = (totalNota * 0.245).toFixed(2).replace('.', ','); 
          doc.text(`T2=02T18,00%`, 2, y); y += 3;
          doc.text(`MD-5:E7B70BBEC831D240FF6D8C0DDC642AC1`, 2, y); y += 4;
          
          doc.text(`Valor aproximado dos tributos deste cupom`, 2, y); y += 3;
          doc.text(`(Conforme Lei Fed. 12.741/2012) R$ ${impostosTotais}`, 2, y); y += 4;

          doc.text("---------------------------------------------", centro, y, { align: "center" }); y += 4;
          doc.text(`CONTROLE:02066054`, 2, y); y += 4;
          doc.text("---------------------------------------------", centro, y, { align: "center" }); y += 4;

          doc.text(`Aplicativo:GRAO.MASSA - SISTEMA (47)`, 2, y); y += 3;
          doc.text(`3333-5555`, 2, y); y += 3;
          doc.text(`BEMATECH MP-4000 TH FI ECF-IF`, 2, y); y += 3;
          doc.text(`VERSÃO:01.00.02 ECF:001 LJ:0001`, 2, y); y += 3;
          doc.text(`QQQQQQQQQEPRTUWRYW ${dataStr} ${horaStr}V`, 2, y); y += 3;
          doc.text(`FAB:BE091710100011211499`, 2, y);

          doc.save(`Cupom_Fiscal_${pedidoSelecionado.pedido_id}.pdf`);
        } else {
          showToast("Erro: Biblioteca jsPDF não carregada.");
        }
      }
      
      pedidoSelecionado = null;
      await carregarPedidos(); 
      
      btn.textContent = "✔ Confirmar Pagamento"; 
    } else {
      showToast(res.mensagem || 'Erro ao finalizar pedido no banco.');
      btn.disabled = false;
      btn.textContent = "✔ Confirmar Pagamento";
    }
    
  } catch (erro) {
    showToast("Verifique o console (F12) para ver o erro exato.");
    btn.disabled = false;
    btn.textContent = "✔ Confirmar Pagamento";
    console.error("Erro ao converter para JSON. O servidor devolveu HTML ou um erro do PHP:", erro);
  }
}

let toastTimeout;
function showToast(msg) {
  const t = document.getElementById("toast");
  document.getElementById("toastMsg").textContent = msg;
  t.classList.add("show");
  
  clearTimeout(toastTimeout);
  toastTimeout = setTimeout(() => t.classList.remove("show"), 3000);
}

const userProfileBtn = document.getElementById('userProfileBtn');
const userDropdown = document.getElementById('userDropdown');

userProfileBtn.addEventListener('click', (e) => {
    e.stopPropagation();
    userDropdown.classList.toggle('show');
});

document.addEventListener('click', (e) => {
    if (!userDropdown.contains(e.target) && !userProfileBtn.contains(e.target)) {
        userDropdown.classList.remove('show');
    }
});

carregarPedidos();
carregarProdutosAtivos();
</script>
</body>
</html>