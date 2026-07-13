<!DOCTYPE html>
<html lang="pt-BR" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Grão & Massa — Pedidos</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=DM+Serif+Display&display=swap" rel="stylesheet">
<link rel="stylesheet" href="CSS/style.css">

<style>
  /* ─── ESTILOS ESPECÍFICOS DA TELA DE PEDIDOS ─── */
  /* Adaptados para suportar Dark/Light mode usando variáveis globais */

  .page-title { font-size: 20px; font-weight: 700; margin-bottom: 4px; color: var(--ink); }
  .page-sub { font-size: 13px; color: var(--ash); margin-bottom: 20px; }
  
  .content-pad { flex: 1; padding: 24px; overflow: hidden; display: flex; flex-direction: column; }
  
  /* SPLIT LAYOUT */
  .split { display: grid; grid-template-columns: 1fr 320px; gap: 20px; flex: 1; overflow: hidden; }
  
  /* PAINEL / CARD */
  .panel { background: var(--white); border-radius: var(--r2); border: 1px solid var(--border); display: flex; flex-direction: column; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.02); }
  .panel-header { padding: 16px 20px; border-bottom: 1px solid var(--border); font-size: 14px; font-weight: 600; color: var(--ink); display: flex; align-items: center; justify-content: space-between; }
  .panel-body { flex: 1; overflow-y: auto; }
  
  /* LISTA DE CLIENTES */
  .cliente-item { display: flex; align-items: center; gap: 12px; padding: 14px 20px; cursor: pointer; border-bottom: 1px solid var(--border); transition: background var(--t); }
  .cliente-item:hover { background: var(--surface); }
  .cliente-item.selected { background: var(--cr-bg); border-left: 3px solid var(--cr); padding-left: 17px; }
  .cli-avatar { width: 38px; height: 38px; border-radius: 50%; background: var(--cr); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 13px; font-weight: 700; flex-shrink: 0; }
  .cli-info { flex: 1; min-width: 0; }
  .cli-name { font-size: 13.5px; font-weight: 600; color: var(--ink); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
  .cli-phone { font-size: 12px; color: var(--ash); margin-top: 2px; }
  .cli-badge { font-size: 11px; background: var(--surface); color: var(--ink); border-radius: 20px; padding: 2px 8px; font-weight: 600; white-space: nowrap; border: 1px solid var(--border); }
  
  /* TABELA DE PEDIDOS */
  .pedidos-wrap { flex: 1; overflow: auto; }
  table { width: 100%; border-collapse: collapse; font-size: 13.5px; }
  thead th { background: var(--surface); padding: 12px 20px; text-align: left; font-size: 11.5px; font-weight: 600; text-transform: uppercase; color: var(--ash); letter-spacing: .5px; white-space: nowrap; border-bottom: 1px solid var(--border); position: sticky; top: 0; z-index: 10; }
  tbody tr { border-bottom: 1px solid var(--border); cursor: pointer; transition: background var(--t); }
  tbody tr:hover { background: var(--surface); }
  tbody tr.selected-row { background: var(--cr-bg); }
  tbody td { padding: 14px 20px; color: var(--ink); vertical-align: middle; }
  
  /* BADGES DE TIPO/STATUS */
  .badge { display: inline-flex; align-items: center; gap: 4px; font-size: 11.5px; font-weight: 600; padding: 4px 10px; border-radius: 20px; }
  
  .badge-doce { background: rgba(190, 24, 93, 0.1); color: #be185d; }
  .badge-salgado { background: rgba(146, 64, 14, 0.1); color: #92400e; }
  .badge-bebida { background: rgba(29, 78, 216, 0.1); color: #1d4ed8; }
  .badge-misto { background: rgba(6, 95, 70, 0.1); color: #065f46; }
  
  .badge-pendente { background: rgba(146, 64, 14, 0.1); color: #92400e; }
  .badge-producao { background: rgba(30, 64, 175, 0.1); color: #1e40af; }
  .badge-pronto { background: rgba(6, 95, 70, 0.1); color: #065f46; }
  .badge-entregue { background: var(--surface); color: var(--ash); border: 1px solid var(--border); }
  
  /* Ajuste de cores das Badges no Dark Mode */
  [data-theme=dark] .badge-doce { color: #f9a8d4; }
  [data-theme=dark] .badge-salgado { color: #fcd34d; }
  [data-theme=dark] .badge-bebida { color: #93c5fd; }
  [data-theme=dark] .badge-misto { color: #6ee7b7; }
  [data-theme=dark] .badge-pendente { color: #fcd34d; }
  [data-theme=dark] .badge-producao { color: #93c5fd; }
  [data-theme=dark] .badge-pronto { color: #6ee7b7; }
  
  /* ESTADO VAZIO (EMPTY STATE) */
  .empty { display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; min-height: 250px; gap: 12px; color: var(--ash); padding: 40px; }
  .empty svg { width: 50px; height: 50px; stroke: var(--ash); stroke-width: 1.5; }
  .empty-text { font-size: 14px; text-align: center; line-height: 1.6; }
  
  /* BARRA DE AÇÕES INFERIOR */
  .acoes-bar { padding: 14px 20px; border-top: 1px solid var(--border); display: flex; gap: 10px; background: var(--white); }
  .btn-acao { font-family: inherit; font-size: 13px; font-weight: 600; padding: 10px 18px; border-radius: 8px; border: 1px solid transparent; cursor: pointer; transition: all var(--t); display: flex; align-items: center; gap: 6px; }
  .btn-acao svg { width: 16px; height: 16px; }
  
  .btn-novo { background: var(--cr); color: #fff; }
  .btn-novo:hover { background: var(--cr2); }
  .btn-edit { background: var(--surface); color: var(--ink); border-color: var(--border); }
  .btn-edit:hover:not(:disabled) { background: var(--border); }
  .btn-del { background: rgba(220, 38, 38, 0.1); color: #dc2626; }
  .btn-del:hover:not(:disabled) { background: rgba(220, 38, 38, 0.2); }
  .btn-acao:disabled { opacity: 0.4; cursor: not-allowed; }
  
  /* MODAL */
  .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.6); z-index: 100; align-items: center; justify-content: center; backdrop-filter: blur(2px); }
  .modal-overlay.open { display: flex; }
  .modal-box { background: var(--white); border-radius: var(--r2); width: 480px; max-width: 95vw; box-shadow: 0 20px 60px rgba(0,0,0,.3); border: 1px solid var(--border); overflow: hidden; }
  .modal-hdr { padding: 18px 24px; background: var(--night); color: #fff; display: flex; justify-content: space-between; align-items: center; }
  .modal-hdr h5 { font-size: 15px; font-weight: 600; margin: 0; }
  .modal-hdr button { background: none; border: none; color: rgba(255,255,255,.6); font-size: 18px; cursor: pointer; line-height: 1; transition: color var(--t); }
  .modal-hdr button:hover { color: #fff; }
  .modal-body { padding: 24px; display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
  .modal-body .full-width { grid-column: 1 / -1; }
  .modal-body label { font-size: 12px; font-weight: 600; color: var(--ink); display: block; margin-bottom: 6px; }
  .modal-body input, .modal-body select { width: 100%; font-family: inherit; font-size: 14px; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; background: var(--surface); color: var(--ink); outline: none; transition: all var(--t); }
  .modal-body input:focus, .modal-body select:focus { border-color: var(--cr); box-shadow: 0 0 0 3px var(--cr-bg); background: var(--white); }
  .modal-ftr { padding: 16px 24px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 10px; background: var(--surface); }
  
  /* TOAST */
  .toast { position: fixed; bottom: 24px; right: 24px; background: var(--night); color: #fff; font-size: 14px; font-weight: 500; padding: 14px 24px; border-radius: var(--r); z-index: 999; opacity: 0; transform: translateY(15px); transition: all .3s; pointer-events: none; border: 1px solid rgba(255,255,255,0.1); box-shadow: 0 10px 30px rgba(0,0,0,0.2); display: flex; align-items: center; gap: 8px; }
  .toast.show { opacity: 1; transform: translateY(0); }
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
    <li><a href="index.php" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>Dashboard</a></li>
    <li><a href="vendas.php" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 001.97-1.67L23 6H6"/></svg>Caixa / Vendas</a></li>
    <li><a href="cadastrar_pedido.php" class="sb-link on"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>Pedidos<span class="sb-dot"></span></a></li>
    <li><a href="Relatorios.php" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>Relatórios</a></li>
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

<div class="main">
  <header class="top">
    <div class="top-l">
      <div class="badge-pg">
        <div class="badge-icon"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg></div>
        Pedidos
      </div>
      <span class="sep">•</span>
      <span class="date-chip" id="dataHoje"></span>
    </div>
    <div class="top-r">
      <button class="tb-btn" onclick="toggleTheme()">
        <svg class="icon-moon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
        <svg class="icon-sun" style="display: none;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
      </button>
    </div>
  </header>

  <div class="content">
    <div class="content-pad">
      
      <div style="display: flex; justify-content: flex-start; align-items: flex-end; gap: 20px; margin-bottom: 24px;">
        <button class="btn-acao btn-novo" onclick="abrirModalNovo()">
          <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          Novo Pedido
        </button>
        <div>
          <h1 class="page-title">Gerenciamento de Pedidos</h1>
          <p class="page-sub">Selecione um cliente à direita para visualizar e gerenciar os seus pedidos.</p>
        </div>
      </div>

      <div class="split">

        <div class="panel">
          <div class="panel-header" id="headerPedidos">
            <span>Pedido</span>
          </div>
          
          <div class="pedidos-wrap">
            <div id="tabelaPedidosWrap"></div>
          </div>
          
          <div class="acoes-bar">
            <button class="btn-acao btn-edit" id="btnEditar" onclick="editarSelecionado()" disabled>
              <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
              Editar Selecionado
            </button>
            <button class="btn-acao btn-del" id="btnExcluir" onclick="excluirSelecionado()" disabled>
              <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
              Excluir
            </button>
          </div>
        </div>

        <div class="panel">
          <div class="panel-header">
            <span>Clientes com Pedidos</span>
            <span style="font-size:12px;color:var(--ash);font-weight:500;" id="totalClientes"></span>
          </div>
          <div class="panel-body" id="listaClientes"></div>
        </div>

      </div>
    </div>
  </div>
</div>

<div class="modal-overlay" id="modalOverlay">
  <div class="modal-box">
    <div class="modal-hdr">
      <h5 id="modalTitulo">Novo Pedido</h5>
      <button onclick="fecharModal()">✕</button>
    </div>
    <div class="modal-body">
      <div class="full-width">
        <label>Cliente *</label>
        <input type="text" id="fCliente" placeholder="Nome completo do cliente">
      </div>
      <div>
        <label>Telefone</label>
        <input type="text" id="fTelefone" placeholder="(00) 00000-0000">
      </div>
      <div>
        <label>Tipo *</label>
        <select id="fTipo">
          <option value="" disabled selected>Selecione...</option>
          <option value="doce">Doce</option>
          <option value="salgado">Salgado</option>
          <option value="bebida">Bebida</option>
          <option value="misto">Misto</option>
        </select>
      </div>
      <div class="full-width">
        <label>Produto *</label>
        <input type="text" id="fProduto" placeholder="Ex: Bolo de Cenoura com Cobertura">
      </div>
      <div>
        <label>Quantidade *</label>
        <input type="number" id="fQtd" placeholder="0" min="1">
      </div>
      <div>
        <label>Status</label>
        <select id="fStatus">
          <option>Pendente</option>
          <option>Em Produção</option>
          <option>Pronto</option>
          <option>Entregue</option>
        </select>
      </div>
    </div>
    <div class="modal-ftr">
      <button class="btn-acao btn-edit" onclick="fecharModal()">Cancelar</button>
      <button class="btn-acao btn-novo" onclick="salvar()">✔ Salvar Pedido</button>
    </div>
  </div>
</div>

<div class="toast" id="toast">
  <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 20px; height: 20px;"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
  <span id="toastMsg">Mensagem</span>
</div>

<script>
// ─── TEMA CLARO/ESCURO (DARK MODE) ──────────────────────────────
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

// ─── DADOS (agora vindos do banco, via listar_pedidos.php) ──────
let pedidos = [];               // populado por carregarPedidos()
let clienteSelecionado = null;
let pedidoSelecionadoId = null;
let modoEdicao = false;

const tipoBadge  = { doce:"badge-doce", salgado:"badge-salgado", bebida:"badge-bebida", misto:"badge-misto" };
const tipoLabel  = { doce:"Doce", salgado:"Salgado", bebida:"Bebida", misto:"Misto" };
const statusBadge = { "Pendente":"badge-pendente", "Em Produção":"badge-producao", "Pronto":"badge-pronto", "Entregue":"badge-entregue" };

// Descobre um "tipo" (pra colorir o badge) a partir da(s) categoria(s) dos itens do pedido
function categoriaParaTipo(categorias) {
  const unicas = [...new Set(categorias.map(c => (c || '').toLowerCase()))];
  if (unicas.length > 1) return 'misto';
  const c = unicas[0] || '';
  if (c.includes('doce') || c.includes('sobremesa')) return 'doce';
  if (c.includes('salg')) return 'salgado';
  if (c.includes('bebida') || c.includes('suco') || c.includes('caf')) return 'bebida';
  return 'misto';
}

// Busca os pedidos reais no banco de dados e monta o formato usado nas telas
async function carregarPedidos() {
  try {
    const resp = await fetch('listar_pedidos.php');
    const dados = await resp.json();

    if (!Array.isArray(dados)) {
      showToast(dados.mensagem || 'Erro ao carregar pedidos do banco.');
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
        produto: p.itens.map(i => `${i.quantidade}x ${i.produto}`).join(', '),
        qtd: p.itens.reduce((soma, i) => soma + i.quantidade, 0)
      }));
    }
  } catch (e) {
    showToast('Não foi possível conectar ao banco de dados.');
    pedidos = [];
  }

  renderClientes();
  renderPedidos();
}

// ─── DATA ATUAL DO TOPBAR ───────────────────────────────────────
const hoje = new Date();
const dataFormatada = hoje.toLocaleDateString('pt-BR', { weekday: 'long', day: 'numeric', month: 'long' });
document.getElementById("dataHoje").textContent = dataFormatada.charAt(0).toUpperCase() + dataFormatada.slice(1);

// ─── FUNÇÕES DE CLIENTES ────────────────────────────────────────
function getClientes() {
  const map = {};
  pedidos.forEach(p => {
    if (!map[p.cliente]) map[p.cliente] = { nome: p.cliente, telefone: p.telefone, total: 0 };
    map[p.cliente].total++;
  });
  return Object.values(map);
}

function iniciais(nome) {
  return nome.split(' ').slice(0, 2).map(n => n[0]).join('').toUpperCase();
}

function renderClientes() {
  const clientes = getClientes();
  const lista = document.getElementById("listaClientes");
  document.getElementById("totalClientes").textContent = clientes.length + " clientes";

  lista.innerHTML = clientes.map(c => `
    <div class="cliente-item ${clienteSelecionado === c.nome ? 'selected' : ''}" onclick="selecionarCliente('${c.nome.replace(/'/g, "\\'")}')">
      <div class="cli-avatar">${iniciais(c.nome)}</div>
      <div class="cli-info">
        <div class="cli-name">${c.nome}</div>
        <div class="cli-phone">${c.telefone}</div>
      </div>
      <span class="cli-badge">${c.total} pedido${c.total > 1 ? 's' : ''}</span>
    </div>
  `).join(''); 
}

function selecionarCliente(nome) {
  clienteSelecionado = nome;
  pedidoSelecionadoId = null;
  atualizarBotoes(); 
  renderClientes();
  renderPedidos();
}

// ─── FUNÇÕES DE PEDIDOS ─────────────────────────────────────────
function renderPedidos() {
  const hdr  = document.getElementById("headerPedidos");
  const wrap = document.getElementById("tabelaPedidosWrap");

  if (!clienteSelecionado) {
    hdr.innerHTML = `<span>Pedidos do Cliente</span>`;
    wrap.innerHTML = `
      <div class="empty">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
        <div class="empty-text">Selecione um cliente na lista ao lado<br>para visualizar os seus pedidos.</div>
      </div>`;
    return;
  }

  const lista = pedidos.filter(p => p.cliente === clienteSelecionado);
  hdr.innerHTML = `<span>Pedidos de <b>${clienteSelecionado}</b></span>
    <span style="font-size:12px;color:var(--ash);font-weight:500;">${lista.length} registro${lista.length !== 1 ? 's' : ''}</span>`;

  if (lista.length === 0) {
    wrap.innerHTML = `
      <div class="empty">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
        <div class="empty-text">Nenhum pedido encontrado.</div>
      </div>`;
    return;
  }

  wrap.innerHTML = `
    <table>
      <thead>
        <tr>
          <th># ID</th>
          <th>Tipo</th>
          <th>Produto / Descrição</th>
          <th>Qtd</th>
          <th>Status Atual</th>
        </tr>
      </thead>
      <tbody>
        ${lista.map(p => `
          <tr onclick="selecionarPedido('${p.id}')" class="${pedidoSelecionadoId === p.id ? 'selected-row' : ''}">
            <td><strong>${p.id}</strong></td>
            <td><span class="badge ${tipoBadge[p.tipo]}">${tipoLabel[p.tipo]}</span></td>
            <td>${p.produto}</td>
            <td>${p.qtd} un.</td>
            <td><span class="badge ${statusBadge[p.status]}">${p.status}</span></td>
          </tr>
        `).join('')}
      </tbody>
    </table>`;
}

function selecionarPedido(id) {
  pedidoSelecionadoId = id;
  atualizarBotoes();
  renderPedidos();
}

function atualizarBotoes() {
  const temSel = !!pedidoSelecionadoId;
  document.getElementById("btnEditar").disabled = !temSel;
  document.getElementById("btnExcluir").disabled = !temSel;
}

// ─── FUNÇÕES DO MODAL ───────────────────────────────────────────
function abrirModalNovo() {
  modoEdicao = false;
  document.getElementById("modalTitulo").textContent = "Adicionar Novo Pedido";
  document.getElementById("fCliente").value  = clienteSelecionado || "";
  document.getElementById("fTelefone").value = clienteSelecionado
    ? (getClientes().find(c => c.nome === clienteSelecionado) || {}).telefone || ""
    : "";
  document.getElementById("fTipo").value    = "";
  document.getElementById("fProduto").value = "";
  document.getElementById("fQtd").value     = "";
  document.getElementById("fStatus").value  = "Pendente";
  document.getElementById("modalOverlay").classList.add("open");
}

function editarSelecionado() {
  if (!pedidoSelecionadoId) return;
  const p = pedidos.find(x => x.id === pedidoSelecionadoId);
  if (!p) return;
  modoEdicao = true;
  document.getElementById("modalTitulo").textContent = "Editar Pedido — " + p.id;
  document.getElementById("fCliente").value  = p.cliente;
  document.getElementById("fTelefone").value = p.telefone;
  document.getElementById("fTipo").value     = p.tipo;
  document.getElementById("fProduto").value  = p.produto;
  document.getElementById("fQtd").value      = p.qtd;
  document.getElementById("fStatus").value   = p.status;
  document.getElementById("modalOverlay").classList.add("open");
}

function fecharModal() {
  document.getElementById("modalOverlay").classList.remove("open");
}

function salvar() {
  const cliente  = document.getElementById("fCliente").value.trim();
  const telefone = document.getElementById("fTelefone").value.trim();
  const tipo     = document.getElementById("fTipo").value;
  const produto  = document.getElementById("fProduto").value.trim();
  const qtd      = parseInt(document.getElementById("fQtd").value);
  const status   = document.getElementById("fStatus").value;

  if (!cliente || !tipo || !produto || !qtd) {
    showToast("Preencha os campos obrigatórios (*).");
    return;
  }

  // ATENÇÃO: este modal ainda não está ligado ao banco de dados.
  // Criar pedido com produto/quantidade/preço reais deve ser feito pela
  // tela "Caixa / Vendas" (vendas.php -> salvar_pedido.php), que já grava
  // em `pedidos` + `itens_pedido` e dá baixa no estoque corretamente.
  // Editar status de um pedido existente também precisa de um endpoint
  // próprio (ex: atualizar_status_pedido.php) — ainda não criado.
  showToast("Use a tela 'Caixa / Vendas' para registrar pedidos. Este formulário ainda não grava no banco.");
  fecharModal();
}

async function excluirSelecionado() {
  if (!pedidoSelecionadoId) return;
  if (!confirm("Tem certeza que deseja excluir o pedido " + pedidoSelecionadoId + "? Esta ação não pode ser desfeita.")) return;

  const pedido = pedidos.find(p => p.id === pedidoSelecionadoId);
  if (!pedido) return;

  try {
    const resp = await fetch('apagar_pedido.php?id=' + pedido.pedido_id);
    const res = await resp.json();

    if (res.sucesso) {
      showToast("Pedido excluído do sistema.");
    } else {
      showToast(res.mensagem || "Erro ao excluir pedido.");
    }
  } catch (e) {
    showToast("Não foi possível conectar ao banco de dados.");
  }

  pedidoSelecionadoId = null;
  atualizarBotoes();
  await carregarPedidos();
}

// ─── TOAST (MENSAGENS) ────────id, nome, status, valor──────────────────────────────────
let toastTimeout;
function showToast(msg) {
  const t = document.getElementById("toast");
  document.getElementById("toastMsg").textContent = msg;
  t.classList.add("show");
  
  clearTimeout(toastTimeout);
  toastTimeout = setTimeout(() => t.classList.remove("show"), 3000);
}

// ─── FECHAR MODAL CLICANDO FORA ─────────────────────────────────
document.getElementById("modalOverlay").addEventListener("click", e => {
  if (e.target === e.currentTarget) fecharModal();
});

// ─── INIT ───────────────────────────────────────────────────────
carregarPedidos();
</script>
</body>
</html>