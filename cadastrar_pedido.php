<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Grão & Massa - Pedidos</title>
<link rel="stylesheet" href="CDN/bootstrap-5.3.8-dist/bootstrap-5.3.8-dist/css/bootstrap.min.css">
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: 'Segoe UI', sans-serif; background: #f3f4f6; color: #1f2937; height: 100vh; overflow: hidden; display: flex; }
 
  /* SIDEBAR */
  .sidebar { width: 200px; background: #1a0000; display: flex; flex-direction: column; flex-shrink: 0; }
  .sidebar-brand { color: #fff; font-size: 15px; font-weight: 700; padding: 20px 16px 16px; border-bottom: 1px solid #3d0000; letter-spacing: .3px; }
  .sidebar nav a { display: block; color: #d4a0a0; text-decoration: none; font-size: 13px; padding: 10px 16px; border-radius: 8px; margin: 2px 8px; transition: all .15s; }
  .sidebar nav a:hover { background: #3d0000; color: #fff; }
  .sidebar nav a.active { background: #880000; color: #fff; font-weight: 600; }
 
  /* MAIN */
  .main { flex: 1; display: flex; flex-direction: column; overflow: hidden; }
 
  /* TOPBAR */
  .topbar { background: #fff; border-bottom: 1px solid #e5e7eb; padding: 12px 24px; display: flex; justify-content: space-between; align-items: center; }
  .avatar { width: 32px; height: 32px; background: #880000; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 11px; font-weight: 700; }
 
  /* CONTENT */
  .content { flex: 1; overflow: auto; padding: 24px; }
  .page-title { font-size: 18px; font-weight: 700; margin-bottom: 4px; }
  .page-sub { font-size: 12px; color: #6b7280; margin-bottom: 20px; }
 
  /* SPLIT LAYOUT */
  .split { display: grid; grid-template-columns: 320px 1fr; gap: 16px; height: calc(100% - 64px); }
 
  /* CARD */
  .card { background: #fff; border-radius: 12px; border: 1px solid #e5e7eb; display: flex; flex-direction: column; overflow: hidden; }
  .card-header { padding: 14px 18px; border-bottom: 1px solid #f0f0f0; font-size: 13px; font-weight: 700; color: #374151; display: flex; align-items: center; gap: 8px; }
  .card-body { flex: 1; overflow-y: auto; }
 
  /* CLIENTES LIST */
  .cliente-item { display: flex; align-items: center; gap: 12px; padding: 12px 18px; cursor: pointer; border-bottom: 1px solid #f9fafb; transition: background .12s; }
  .cliente-item:hover { background: #fff5f5; }
  .cliente-item.selected { background: #fff0f0; border-left: 3px solid #880000; }
  .cli-avatar { width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, #880000, #bb3333); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 13px; font-weight: 700; flex-shrink: 0; }
  .cli-info { flex: 1; min-width: 0; }
  .cli-name { font-size: 13px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
  .cli-phone { font-size: 11px; color: #6b7280; margin-top: 2px; }
  .cli-badge { font-size: 10px; background: #fff0f0; color: #880000; border-radius: 20px; padding: 2px 8px; font-weight: 600; white-space: nowrap; }
 
  /* PEDIDOS TABLE */
  .pedidos-wrap { flex: 1; overflow: auto; }
  table { width: 100%; border-collapse: collapse; font-size: 12.5px; }
  thead th { background: #f9fafb; padding: 10px 14px; text-align: left; font-size: 11px; font-weight: 600; text-transform: uppercase; color: #6b7280; letter-spacing: .5px; white-space: nowrap; }
  tbody tr { border-bottom: 1px solid #f3f4f6; cursor: pointer; transition: background .1s; }
  tbody tr:hover { background: #f9fafb; }
  tbody tr.selected-row { background: #fef9ee; }
  tbody td { padding: 11px 14px; color: #374151; vertical-align: middle; }
 
  /* BADGES */
  .badge { display: inline-flex; align-items: center; gap: 3px; font-size: 11px; font-weight: 600; padding: 3px 9px; border-radius: 20px; }
  .badge-doce { background: #fce7f3; color: #be185d; }
  .badge-salgado { background: #fef3c7; color: #92400e; }
  .badge-bebida { background: #dbeafe; color: #1d4ed8; }
  .badge-misto { background: #d1fae5; color: #065f46; }
  .badge-pendente { background: #fef3c7; color: #92400e; }
  .badge-producao { background: #dbeafe; color: #1e40af; }
  .badge-pronto { background: #d1fae5; color: #065f46; }
  .badge-entregue { background: #f3f4f6; color: #374151; }
 
  /* EMPTY STATE */
  .empty { display: flex; flex-direction: column; align-items: center; justify-content: center; height: 200px; gap: 10px; color: #9ca3af; padding: 40px; }
  .empty-icon { font-size: 40px; }
  .empty-text { font-size: 13px; text-align: center; line-height: 1.6; }
 
  /* ACOES BAR */
  .acoes-bar { padding: 12px 18px; border-top: 1px solid #f0f0f0; display: flex; gap: 8px; background: #fff; }
  .btn-acao { font-size: 12px; font-weight: 600; padding: 8px 16px; border-radius: 8px; border: none; cursor: pointer; transition: all .15s; display: flex; align-items: center; gap: 6px; }
  .btn-novo { background: #880000; color: #fff; }
  .btn-novo:hover { background: #660000; }
  .btn-edit { background: #f3f4f6; color: #374151; border: 1px solid #e5e7eb; }
  .btn-edit:hover:not(:disabled) { background: #e5e7eb; }
  .btn-del { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
  .btn-del:hover:not(:disabled) { background: #fee2e2; }
  .btn-acao:disabled { opacity: 0.4; cursor: not-allowed; }
 
  /* MODAL */
  .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.45); z-index: 100; align-items: center; justify-content: center; }
  .modal-overlay.open { display: flex; }
  .modal-box { background: #fff; border-radius: 14px; width: 460px; max-width: 95vw; box-shadow: 0 20px 60px rgba(0,0,0,.2); overflow: hidden; }
  .modal-hdr { padding: 18px 20px; background: #1a0000; color: #fff; display: flex; justify-content: space-between; align-items: center; }
  .modal-hdr h5 { font-size: 14px; font-weight: 700; margin: 0; }
  .modal-hdr button { background: none; border: none; color: #9ca3af; font-size: 18px; cursor: pointer; line-height: 1; }
  .modal-hdr button:hover { color: #fff; }
  .modal-body { padding: 20px; display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
  .modal-body label { font-size: 12px; font-weight: 600; color: #374151; display: block; margin-bottom: 5px; }
  .modal-body input, .modal-body select { width: 100%; font-size: 13px; padding: 8px 11px; border: 1px solid #d1d5db; border-radius: 8px; color: #1f2937; outline: none; }
  .modal-body input:focus, .modal-body select:focus { border-color: #880000; box-shadow: 0 0 0 3px #fff0f0; }
  .modal-ftr { padding: 14px 20px; border-top: 1px solid #f0f0f0; display: flex; justify-content: flex-end; gap: 8px; }
  .btn-cancel { font-size: 13px; padding: 8px 20px; border-radius: 8px; border: 1px solid #e5e7eb; background: #f9fafb; cursor: pointer; }
  .btn-save { font-size: 13px; padding: 8px 24px; border-radius: 8px; border: none; background: #880000; color: #fff; font-weight: 600; cursor: pointer; }
  .btn-save:hover { background: #660000; }
 
  /* TOAST */
  .toast { position: fixed; bottom: 24px; right: 24px; background: #1a0000; color: #fff; font-size: 13px; padding: 12px 20px; border-radius: 10px; z-index: 999; opacity: 0; transform: translateY(10px); transition: all .25s; pointer-events: none; }
  .toast.show { opacity: 1; transform: translateY(0); }
</style>
</head>
<body>
 
<!-- SIDEBAR -->
<div class="sidebar">
  <div class="sidebar-brand">🛡 Grão & Massa</div>
  <nav style="padding:8px; margin-top:4px;">
    <a href="#"> Dashboard</a>
    <a href="#" class="active"> Pedidos</a>
    <a href="vendas.php"> Caixa</a>
    <a href="#"> Estoque</a>
    <a href="#"> Relatórios</a>
  </nav>
</div>
 
<!-- MAIN -->
<div class="main">
  <div class="topbar">
    <span style="font-size:13px;color:#6b7280;font-weight:500;" id="dataHoje"></span>
    <div style="display:flex;align-items:center;gap:8px;">
      <div class="avatar">AS</div>
      <span style="font-size:13px;font-weight:600;">Admin</span>
    </div>
  </div>
 
  <div class="content">
    <div class="page-title"> Pedidos</div>
    <p class="page-sub">Selecione um cliente para ver seus pedidos.</p>
 
    <div class="split">
 
    
      <div class="card">
        <div class="card-header">
          <span> Clientes</span>
          <span style="font-size:11px;color:#9ca3af;font-weight:400;margin-left:auto;" id="totalClientes"></span>
        </div>
        <div class="card-body" id="listaClientes"></div>
 
      </div>
 
      
      <div class="card">
        <div class="card-header" id="headerPedidos">
          <span> Pedidos do cliente</span>
        </div>
        <div class="pedidos-wrap">
          <div id="tabelaPedidosWrap"></div>
        </div>
        <div class="acoes-bar">
          <button class="btn-acao btn-edit" id="btnEditar" onclick="editarSelecionado()" disabled>✏️ Editar Pedido</button>
          <button class="btn-acao btn-del" id="btnExcluir" onclick="excluirSelecionado()" disabled>🗑 Excluir Pedido</button>
        </div>
      </div>
 
    </div>
  </div>
</div>
 
<!-- MODAL -->
<div class="modal-overlay" id="modalOverlay">
  <div class="modal-box">
    <div class="modal-hdr">
      <h5 id="modalTitulo">Novo Pedido</h5>
      <button onclick="fecharModal()">✕</button>
    </div>
    <div class="modal-body">
      <div>
        <label>Cliente *</label>
        <input type="text" id="fCliente" placeholder="Nome do cliente">
      </div>
      <div>
        <label>Telefone</label>
        <input type="text" id="fTelefone" placeholder="(47) 99999-0000">
      </div>
      <div>
        <label>Tipo *</label>
        <select id="fTipo">
          <option value="" disabled selected>Selecione...</option>
          <option value="doce"> Doce</option>
          <option value="salgado"> Salgado</option>
          <option value="bebida"> Bebida</option>
          <option value="misto"> Misto</option>
        </select>
      </div>
      <div>
        <label>Produto *</label>
        <input type="text" id="fProduto" placeholder="Ex: Bolo de Cenoura">
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
      <button class="btn-cancel" onclick="fecharModal()">Cancelar</button>
      <button class="btn-save" onclick="salvar()">✔ Salvar</button>
    </div>
  </div>
</div>
 
<!-- TOAST -->
<div class="toast" id="toast"></div>
 
<script src="CDN/bootstrap-5.3.8-dist/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
<script>
// ─── DATA ───────────────────────────────────────────────────────
let pedidos = [
  { id: "P-001", cliente: "Maria Silva",    telefone: "(47) 99801-2233", tipo: "doce",    produto: "Bolo de Cenoura",        qtd: 2,  status: "Pronto" },
  { id: "P-002", cliente: "João Ricardo",   telefone: "(47) 98877-4411", tipo: "salgado", produto: "Coxinha de Frango",       qtd: 10, status: "Em Produção" },
  { id: "P-003", cliente: "Ana Paula Lima", telefone: "(47) 99654-0099", tipo: "misto",   produto: "Pão de Queijo + Brownie", qtd: 5,  status: "Pendente" },
  { id: "P-004", cliente: "Carlos Mendes",  telefone: "(47) 99321-7788", tipo: "bebida",  produto: "Suco Natural 500ml",      qtd: 3,  status: "Entregue" },
  { id: "P-005", cliente: "Fernanda Costa", telefone: "(47) 98800-5566", tipo: "doce",    produto: "Brigadeiro Gourmet",      qtd: 20, status: "Pendente" },
  { id: "P-006", cliente: "Maria Silva",    telefone: "(47) 99801-2233", tipo: "salgado", produto: "Empada de Palmito",       qtd: 6,  status: "Pendente" },
];
 
let proxNum = 7;
let clienteSelecionado = null;
let pedidoSelecionadoId = null;
let modoEdicao = false;
 
const tipoBadge  = { doce:"badge-doce", salgado:"badge-salgado", bebida:"badge-bebida", misto:"badge-misto" };
const tipoLabel  = { doce:" Doce", salgado:" Salgado", bebida:" Bebida", misto:" Misto" };
const statusBadge = { "Pendente":"badge-pendente", "Em Produção":"badge-producao", "Pronto":"badge-pronto", "Entregue":"badge-entregue" };
 
// ─── DATA ATUAL ─────────────────────────────────────────────────
const meses = ['janeiro','fevereiro','março','abril','maio','junho','julho','agosto','setembro','outubro','novembro','dezembro'];
const hoje = new Date();
document.getElementById("dataHoje").textContent = `${hoje.getDate()} de ${meses[hoje.getMonth()]} de ${hoje.getFullYear()}`;
 
// ─── CLIENTES ───────────────────────────────────────────────────
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
 
// ─── SELECIONAR CLIENTE ─────────────────────────────────────────
function selecionarCliente(nome) {
  clienteSelecionado = nome;
  pedidoSelecionadoId = null;
  atualizarBotoes(); 
  renderClientes();
  renderPedidos();
}
 
// ─── PEDIDOS ────────────────────────────────────────────────────
function renderPedidos() {
  const hdr  = document.getElementById("headerPedidos");
  const wrap = document.getElementById("tabelaPedidosWrap");
 
  if (!clienteSelecionado) {
    hdr.innerHTML = `<span> Pedidos do cliente</span>`;
    wrap.innerHTML = `
      <div class="empty">
        <div class="empty-icon"></div>
        <div class="empty-text">Selecione um cliente<br>para ver os pedidos.</div>
      </div>`;
    return;
  }
 
  const lista = pedidos.filter(p => p.cliente === clienteSelecionado);
  hdr.innerHTML = `<span> Pedidos — ${clienteSelecionado}</span>
    <span style="font-size:11px;color:#9ca3af;font-weight:400;margin-left:auto;">${lista.length} pedido${lista.length !== 1 ? 's' : ''}</span>`;
 
  if (lista.length === 0) {
    wrap.innerHTML = `
      <div class="empty">
        <div class="empty-icon"></div>
        <div class="empty-text">Nenhum pedido encontrado.</div>
      </div>`;
    return;
  }
 
  wrap.innerHTML = `
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Tipo</th>
          <th>Produto</th>
          <th>Qtd</th>
          <th>Status</th>
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
 
// ─── MODAL ──────────────────────────────────────────────────────
function abrirModalNovo() {
  modoEdicao = false;
  document.getElementById("modalTitulo").textContent = "Novo Pedido";
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
    showToast(" Preencha os campos obrigatórios.");
    return;
  }
 
  if (modoEdicao) {
    const p = pedidos.find(x => x.id === pedidoSelecionadoId);
    if (p) { p.cliente = cliente; p.telefone = telefone; p.tipo = tipo; p.produto = produto; p.qtd = qtd; p.status = status; }
    showToast(" Pedido atualizado!");
  } else {
    const novoId = "P-" + String(proxNum++).padStart(3, "0");
    pedidos.push({ id: novoId, cliente, telefone, tipo, produto, qtd, status });
    clienteSelecionado = cliente;
    showToast(" Pedido adicionado!");
  }
 
  fecharModal();
  renderClientes();
  renderPedidos();
}
 
function excluirSelecionado() {
  if (!pedidoSelecionadoId) return;
  if (!confirm("Deseja excluir o pedido " + pedidoSelecionadoId + "?")) return;
  pedidos = pedidos.filter(p => p.id !== pedidoSelecionadoId);
  pedidoSelecionadoId = null;
  atualizarBotoes();
  showToast("🗑 Pedido excluído.");
  renderClientes();
  renderPedidos();
}
 
// ─── TOAST ──────────────────────────────────────────────────────
function showToast(msg) {
  const t = document.getElementById("toast");
  t.textContent = msg;
  t.classList.add("show");
  setTimeout(() => t.classList.remove("show"), 2500);
}
 
// ─── FECHAR MODAL CLICANDO FORA ─────────────────────────────────
document.getElementById("modalOverlay").addEventListener("click", e => {
  if (e.target === e.currentTarget) fecharModal();
});
 
// ─── INIT ───────────────────────────────────────────────────────
renderClientes();
renderPedidos();
</script>
</body>
</html>