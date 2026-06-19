<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Grão & Massa - Pedidos</title>
<link rel="stylesheet" href="CDN/bootstrap-5.3.8-dist/bootstrap-5.3.8-dist/css/bootstrap.min.css">
<style>
/* COR PRINCIPAL DO SITE */
body {
background-color: #f3f4f6;
font-family: 'Segoe UI', sans-serif;
}

/* SIDEBAR - barra lateral escura */
.sidebar {
width: 220px;
min-height: 100vh;
background-color: #1a0000;
}

.sidebar-brand {
padding: 22px 20px;
color: white;
font-size: 16px;
font-weight: bold;
text-align: center;
border-bottom: 1px solid #333;
}

.sidebar a {
display: block;
color: #cccccc;
padding: 11px 20px;
text-decoration: none;
font-size: 14px;
}

.sidebar a:hover {
background-color: #3a0000;
color: white;
}

.sidebar a.active {
background-color: #8B0000;
color: white;
}

/* TOPBAR - barra do topo */
.topbar {
background-color: white;
border-bottom: 1px solid #e0e0e0;
padding: 13px 28px;
display: flex;
justify-content: space-between;
align-items: center;
}

/* AVATAR do admin */
.avatar {
width: 34px;
height: 34px;
background-color: #8B0000;
color: white;
border-radius: 50%;
display: flex;
align-items: center;
justify-content: center;
font-size: 12px;
font-weight: bold;
}

/* CARD que envolve a tabela */
.card {
border-radius: 16px;
box-shadow: 0 2px 10px #00000015;
overflow: hidden;
}

/* CABEÇALHO DA TABELA */
thead th {
padding: 13px 18px;
font-size: 12px;
font-weight: bold;
text-transform: uppercase;
color: #999999;
background-color: #f9fafb;
border-bottom: 2px solid #eeeeee;
}

/* CÉLULAS DA TABELA */
tbody td {
padding: 15px 18px;
font-size: 14px;
color: #444444;
border-bottom: 1px solid #f5f5f5;
vertical-align: middle;
}

/* HOVER e SELEÇÃO de linha */
tbody tr:hover td { background-color: #fff5f5; }
tbody tr.selecionado td { background-color: #fff0f0; border-left: 3px solid #8B0000; }
tbody tr { cursor: pointer; }

/* BADGES DE TIPO (Doce, Salgado...) */
.badge-tipo {
display: inline-block;
padding: 4px 12px;
border-radius: 20px;
font-size: 12px;
font-weight: bold;
}
.badge-doce { background-color: #fce7f3; color: #9d174d; }
.badge-salgado { background-color: #fef3c7; color: #92400e; }
.badge-bebida { background-color: #dbeafe; color: #1e40af; }
.badge-misto { background-color: #ede9fe; color: #5b21b6; }

/* BADGES DE STATUS (Pendente, Pronto...) */
.badge-status {
display: inline-block;
padding: 4px 12px;
border-radius: 20px;
font-size: 12px;
font-weight: bold;
}
.badge-pendente { background-color: #fef3c7; color: #92400e; }
.badge-producao { background-color: #dbeafe; color: #1e40af; }
.badge-pronto { background-color: #d1fae5; color: #065f46; }
.badge-entregue { background-color: #f0fdf4; color: #166534; }

/* BARRA DE BOTÕES embaixo da tabela */
.acoes-bar {
display: flex;
gap: 10px;
padding: 16px 20px;
border-top: 1px solid #eeeeee;
background-color: white;
}

/* BOTÕES DE AÇÃO */
.btn-acao {
padding: 9px 20px;
border-radius: 9px;
font-size: 13px;
font-weight: bold;
cursor: pointer;
border: none;
}
.btn-add { background-color: #8B0000; color: white; }
.btn-add:hover { background-color: #a50000; }
.btn-edit { background-color: #f3f4f6; color: #333333; border: 1px solid #dddddd; }
.btn-edit:hover { background-color: #e5e7eb; }
.btn-del { background-color: #fff0f0; color: #8B0000; border: 1px solid #ffcccc; }
.btn-del:hover { background-color: #8B0000; color: white; }

/* CABEÇALHO DO MODAL */
.modal-header {
background-color: #8B0000;
border-radius: 16px 16px 0 0;
padding: 18px 24px;
}
.modal-title { color: white; font-weight: bold; }
.modal-content { border-radius: 16px; border: none; }

/* BOTÃO SALVAR do modal */
.btn-salvar {
background-color: #8B0000;
color: white;
border: none;
padding: 9px 24px;
border-radius: 9px;
font-size: 14px;
font-weight: bold;
cursor: pointer;
}
.btn-salvar:hover { background-color: #a50000; }
</style>
</head>
<body>
<div class="d-flex" style="height:100vh; overflow:hidden;">

<!-- SIDEBAR -->
<div class="sidebar">
<div class="sidebar-brand">🛡 Grão & Massa</div>
<nav class="p-2 mt-1">
<a href="#">📊 Dashboard</a>
<a href="#" class="active">📋 Pedidos</a>
<a href="vendas.html">💰 Caixa</a>
<a href="#">📦 Estoque</a>
<a href="#">📄 Relatórios</a>
</nav>
</div>

<!-- ÁREA PRINCIPAL -->
<div class="d-flex flex-column flex-grow-1 overflow-hidden">

<!-- TOPBAR -->
<div class="topbar">
<span style="font-size:13px; color:#6b7280; font-weight:500;" id="dataHoje"></span>
<div class="d-flex align-items-center gap-2">
<div class="avatar">AS</div>
<span style="font-size:13px; font-weight:600;">Admin</span>
</div>
</div>

<!-- CONTEÚDO CENTRALIZADO -->
<div class="flex-grow-1 overflow-auto p-4">

<div class="mb-4">
<h4 class="fw-bold mb-0">📋 Pedidos</h4>
<p class="text-muted small mb-0">Clique em um pedido para selecioná-lo antes de editar ou excluir.</p>
</div>

<!-- CARD COM TABELA + BOTÕES EMBAIXO -->
<div class="card">

<div class="table-responsive">
<table>
<thead>
<tr>
<th>#</th>
<th>Cliente</th>
<th>Telefone</th>
<th>Tipo</th>
<th>Produto</th>
<th>Quantidade</th>
<th>Status</th>
</tr>
</thead>
<tbody id="tabelaPedidos">

<tr onclick="selecionar(this)">
<td><strong>P-001</strong></td>
<td>Maria Silva</td>
<td>(47) 99801-2233</td>
<td><span class="badge-tipo badge-doce">🍰 Doce</span></td>
<td>Bolo de Cenoura</td>
<td>2 unidades</td>
<td><span class="badge-status badge-pronto">Pronto</span></td>
</tr>
<tr onclick="selecionar(this)">
<td><strong>P-002</strong></td>
<td>João Ricardo</td>
<td>(47) 98877-4411</td>
<td><span class="badge-tipo badge-salgado">🥐 Salgado</span></td>
<td>Coxinha de Frango</td>
<td>10 unidades</td>
<td><span class="badge-status badge-producao">Em Produção</span></td>
</tr>
<tr onclick="selecionar(this)">
<td><strong>P-003</strong></td>
<td>Ana Paula Lima</td>
<td>(47) 99654-0099</td>
<td><span class="badge-tipo badge-misto">🍱 Misto</span></td>
<td>Pão de Queijo + Brownie</td>
<td>5 unidades</td>
<td><span class="badge-status badge-pendente">Pendente</span></td>
</tr>
<tr onclick="selecionar(this)">
<td><strong>P-004</strong></td>
<td>Carlos Mendes</td>
<td>(47) 99321-7788</td>
<td><span class="badge-tipo badge-bebida">🥤 Bebida</span></td>
<td>Suco Natural 500ml</td>
<td>3 unidades</td>
<td><span class="badge-status badge-entregue">Entregue</span></td>
</tr>
<tr onclick="selecionar(this)">
<td><strong>P-005</strong></td>
<td>Fernanda Costa</td>
<td>(47) 98800-5566</td>
<td><span class="badge-tipo badge-doce">🍰 Doce</span></td>
<td>Brigadeiro Gourmet</td>
<td>20 unidades</td>
<td><span class="badge-status badge-pendente">Pendente</span></td>
</tr>
<tr onclick="selecionar(this)">
<td><strong>P-006</strong></td>
<td>Rafael Oliveira</td>
<td>(47) 99100-3344</td>
<td><span class="badge-tipo badge-salgado">🥐 Salgado</span></td>
<td>Croissant de Presunto</td>
<td>6 unidades</td>
<td><span class="badge-status badge-pronto">Pronto</span></td>
</tr>

</tbody>
</table>
</div>

<!-- BOTÕES EMBAIXO DA TABELA -->
<div class="acoes-bar">
<button class="btn-acao btn-add" onclick="abrirModal(false)">＋ Adicionar Pedido</button>
<button class="btn-acao btn-edit" onclick="editarSelecionado()">✏️ Editar Pedido</button>
<button class="btn-acao btn-del" onclick="excluirSelecionado()">🗑 Excluir Pedido</button>
</div>

</div>
</div>
</div>
</div>

<!-- MODAL -->
<div class="modal fade" id="modalPedido" tabindex="-1">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title" id="modalTitulo">Adicionar Pedido</h5>
<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body">
<div class="row g-3">
<div class="col-md-6">
<label class="form-label">Cliente *</label>
<input type="text" class="form-control" id="fCliente" placeholder="Nome do cliente">
</div>
<div class="col-md-6">
<label class="form-label">Telefone</label>
<input type="text" class="form-control" id="fTelefone" placeholder="(47) 99999-0000">
</div>
<div class="col-md-6">
<label class="form-label">Tipo *</label>
<select class="form-select" id="fTipo">
<option value="" disabled selected>Selecione...</option>
<option value="doce">🍰 Doce</option>
<option value="salgado">🥐 Salgado</option>
<option value="bebida">🥤 Bebida</option>
<option value="misto">🍱 Misto</option>
</select>
</div>
<div class="col-md-6">
<label class="form-label">Produto *</label>
<input type="text" class="form-control" id="fProduto" placeholder="Ex: Bolo de Cenoura">
</div>
<div class="col-md-6">
<label class="form-label">Quantidade *</label>
<input type="number" class="form-control" id="fQtd" placeholder="0" min="1">
</div>
<div class="col-md-6">
<label class="form-label">Status</label>
<select class="form-select" id="fStatus">
<option>Pendente</option>
<option>Em Produção</option>
<option>Pronto</option>
<option>Entregue</option>
</select>
</div>
</div>
</div>
<div class="modal-footer">
<button class="btn btn-light border px-4" data-bs-dismiss="modal">Cancelar</button>
<button class="btn-salvar" onclick="salvar()">✔ Salvar</button>
</div>
</div>
</div>
</div>

<script src="CDN/bootstrap-5.3.8-dist/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
<script>
// Data no topbar
const meses = ['janeiro','fevereiro','março','abril','maio','junho','julho','agosto','setembro','outubro','novembro','dezembro'];
const hoje = new Date();
document.getElementById("dataHoje").textContent = `${hoje.getDate()} de ${meses[hoje.getMonth()]} de ${hoje.getFullYear()}`;

let proximoNum = 7; // próximo número de pedido
let linhaSelecionada = null; // linha clicada na tabela
let modoEdicao = false; // true = editando, false = adicionando

// Dados de badge por tipo e status
const tipoBadge = { doce: "badge-doce", salgado: "badge-salgado", bebida: "badge-bebida", misto: "badge-misto" };
const tipoEmoji = { doce: "🍰 Doce", salgado: "🥐 Salgado", bebida: "🥤 Bebida", misto: "🍱 Misto" };
const statusBadge = { "Pendente": "badge-pendente", "Em Produção": "badge-producao", "Pronto": "badge-pronto", "Entregue": "badge-entregue" };

// Marca a linha como selecionada (destaque vermelho)
function selecionar(tr) {
if (linhaSelecionada) linhaSelecionada.classList