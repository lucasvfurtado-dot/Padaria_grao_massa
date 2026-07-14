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
  .page-title { font-size: 20px; font-weight: 700; margin-bottom: 4px; color: var(--ink); }
  .page-sub { font-size: 13px; color: var(--ash); margin-bottom: 20px; }
  
  .content-pad { flex: 1; padding: 24px; overflow: hidden; display: flex; flex-direction: column; }
  
  /* SPLIT LAYOUT */
  .split { display: grid; grid-template-columns: 1fr 320px; gap: 20px; flex: 1; overflow: hidden; }
  
  /* PAINEL / CARD */
  .panel { background: var(--white); border-radius: var(--r2); border: 1px solid var(--border); display: flex; flex-direction: column; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.02); }
  .panel-header { padding: 16px 20px; border-bottom: 1px solid var(--border); font-size: 14px; font-weight: 600; color: var(--ink); display: flex; align-items: center; justify-content: space-between; }
  .panel-body { flex: 1; overflow-y: auto; }
  
  /* TABELA DE PEDIDOS (MAIOR) */
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
  .acoes-bar { padding: 14px 20px; border-top: 1px solid var(--border); display: flex; flex-direction: column; gap: 12px; background: var(--white); margin-top: auto; }
  .btn-acao { font-family: inherit; font-size: 13px; font-weight: 600; padding: 10px 18px; border-radius: 8px; border: 1px solid transparent; cursor: pointer; transition: all var(--t); display: flex; align-items: center; gap: 6px; justify-content: center; width: 100%;}
  .btn-acao svg { width: 16px; height: 16px; }
  
  .btn-novo { background: var(--cr); color: #fff; }
  .btn-novo:hover { background: var(--cr2); }
  .btn-edit { background: var(--surface); color: var(--ink); border-color: var(--border); }
  .btn-edit:hover:not(:disabled) { background: var(--border); }
  .btn-acao:disabled { opacity: 0.4; cursor: not-allowed; }

  /* PAINEL FINALIZAR (TABELA MENOR) */
  .checkout-info { padding: 20px; display: flex; flex-direction: column; gap: 16px; }
  .checkout-item { display: flex; justify-content: space-between; font-size: 13.5px; padding-bottom: 12px; border-bottom: 1px dashed var(--border); }
  
  .form-group { display: flex; flex-direction: column; gap: 6px; }
  .form-group label { font-size: 12px; font-weight: 600; color: var(--ink); }
  .form-group select { width: 100%; font-family: inherit; font-size: 14px; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; background: var(--surface); color: var(--ink); outline: none; transition: border-color var(--t); }
  .form-group select:focus { border-color: var(--cr); }
  .form-group select:disabled { opacity: 0.5; cursor: not-allowed; }
  
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
    <div class="top-r">
      <!-- BOTÃO VOLTAR PARA VENDAS -->
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

        <!-- TABELA MAIOR (Esquerda) -->
        <div class="panel">
          <div class="panel-header" id="headerPedidos">
            <span>Todos os Pedidos Pendentes</span>
            <span style="font-size:12px;color:var(--ash);font-weight:500;" id="contadorPendentes">0 registros</span>
          </div>
          
          <div class="pedidos-wrap">
            <div id="tabelaPedidosWrap"></div>
          </div>
        </div>

        <!-- TABELA MENOR (Direita) - Painel de Finalização -->
        <div class="panel">
          <div class="panel-header">
            <span>Finalizar Pedido</span>
          </div>
          <div class="panel-body" id="painelFinalizar">
            <!-- Conteúdo injetado pelo JS -->
          </div>
          
          <!-- SELECT E BOTÃO FIXOS NA BASE -->
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
            
            <button class="btn-acao btn-novo" id="btnFinalizar" onclick="finalizarPedido()" disabled>
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

// ─── DADOS ──────────────────────────────────────────────────────
let pedidos = [];               
let pedidoSelecionado = null;

const tipoBadge  = { doce:"badge-doce", salgado:"badge-salgado", bebida:"badge-bebida", misto:"badge-misto" };
const tipoLabel  = { doce:"Doce", salgado:"Salgado", bebida:"Bebida", misto:"Misto" };
const statusBadge = { "Pendente":"badge-pendente", "Em Produção":"badge-producao", "Pronto":"badge-pronto", "Entregue":"badge-entregue" };

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
    const resp = await fetch('listar_pedidos.php');
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
            <td><span class="badge ${statusBadge[p.status] || 'badge-pendente'}">${p.status}</span></td>
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
  const selectPgto = document.getElementById("formaPagamento");

  if (!pedidoSelecionado) {
    painel.innerHTML = `
      <div class="empty" style="min-height: 200px; padding: 20px;">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:30px; height:30px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" /></svg>
        <div class="empty-text" style="font-size: 12.5px;">Selecione um pedido ao lado para finalizar.</div>
      </div>`;
    btn.disabled = true;
    selectPgto.disabled = true;
    selectPgto.value = "Pix"; // Reseta
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
  selectPgto.disabled = false;
}

// === FUNÇÃO DE DEPURACÃO PARA VER O ERRO DO SERVIDOR ===
async function finalizarPedido() {
  if (!pedidoSelecionado) return;
  
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

    // 1. Pegamos a resposta como TEXTO primeiro para ver se tem erro de PHP
    const textoCru = await response.text();
    console.log("RESPOSTA DO SERVIDOR:", textoCru);

    // 2. Tentamos transformar em JSON
    const res = JSON.parse(textoCru);

    if (res.sucesso) {
      showToast(`Pedido pago e finalizado via ${formaPag}!`);
      
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

// ─── TOAST (MENSAGENS) ──────────────────────────────────────────
let toastTimeout;
function showToast(msg) {
  const t = document.getElementById("toast");
  document.getElementById("toastMsg").textContent = msg;
  t.classList.add("show");
  
  clearTimeout(toastTimeout);
  toastTimeout = setTimeout(() => t.classList.remove("show"), 3000);
}

carregarPedidos();
</script>
</body>
</html>