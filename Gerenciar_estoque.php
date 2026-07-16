<?php
include("php/conexao.php");

$sql_produtos = "SELECT * FROM produtos ORDER BY nome_produto ASC";
$result_produtos = mysqli_query($conn, $sql_produtos);
?>
<!DOCTYPE html>
<html lang="pt-BR" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Grão & Massa — Gerir Estoque</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=DM+Serif+Display&display=swap" rel="stylesheet">
<link rel="stylesheet" href="CSS/style.css">
<style>
  body { overflow: hidden; }
  .content { display: flex; height: calc(100vh - 80px); overflow: hidden; }
  .products { flex: 1; display: flex; flex-direction: column; overflow: hidden; padding: 24px; }
  
 
  .grid {
      flex: 1; 
      overflow-y: auto !important; 
      padding: 10px 20px 20px 20px; 
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)) !important;
      gap: 15px; 
      align-content: start;
  }

  .grid::-webkit-scrollbar { width: 8px; }
  .grid::-webkit-scrollbar-track { background: transparent; }
  .grid::-webkit-scrollbar-thumb { background-color: var(--border); border-radius: 10px; }

  
  .card {
      display: flex !important; 
      flex-direction: column; 
      height: 200px !important;
      padding: 12px; 
      border-radius: 12px; 
      box-shadow: 0 4px 6px rgba(0,0,0,0.05);
      cursor: pointer; 
      transition: transform 0.2s; 
      position: relative; 
  }

  .card:hover { transform: translateY(-4px); box-shadow: 0 6px 12px rgba(0,0,0,0.05); }

  
  .card-img {
      height: 70px;
      width: 100%;
      margin-bottom: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: var(--surface);
      border-radius: 8px;
      overflow: hidden;
  }
  .card-img img { width: 100%; height: 100%; object-fit: contain; }

  
  .card-name { font-size: 12px; font-weight: 600; text-align: center; margin-bottom: 4px; color: var(--ink); }
  .card-cat { font-size: 9px; text-align: center; color: var(--ash); margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.5px;}

 
  .stock-controls {
      display: flex;
      align-items: center;
      justify-content: space-between;
      background: var(--surface);
      border-radius: 8px;
      padding: 4px;
      margin-bottom: 10px;
  }
  .btn-stock {
      width: 26px;
      height: 26px;
      background: var(--white);
      border: 1px solid var(--border);
      border-radius: 6px;
      font-size: 14px;
      font-weight: bold;
      color: var(--ink);
      cursor: pointer;
      display: flex; align-items: center; justify-content: center;
      transition: all 0.2s;
  }
  .btn-stock:hover { background: var(--cr); color: #fff; border-color: var(--cr); }
  
  .input-stock {
      width: 35px;
      text-align: center;
      border: none;
      background: transparent;
      font-size: 13px;
      font-weight: 700;
      color: var(--ink);
      outline: none;
  }
  
  
  .btn-delete {
      width: 100%;
      padding: 6px;
      background: rgba(220, 38, 38, 0.1);
      color: #dc2626;
      border: 1px solid transparent;
      border-radius: 8px;
      font-size: 11px;
      font-weight: 600;
      cursor: pointer;
      display: flex; align-items: center; justify-content: center; gap: 6px;
      transition: all 0.2s;
  }
  .btn-delete:hover { background: rgba(220, 38, 38, 0.2); border-color: rgba(220, 38, 38, 0.3); }

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
    <li><a href="index.php" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>Dashboard</a></li>
    <li><a href="vendas.php" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 001.97-1.67L23 6H6"/></svg>Caixa / Vendas</a></li>
    <li><a href="cadastrar_pedido.php" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>Pedidos</a></li>
    <li><a href="gerenciar_estoque.php" class="sb-link on"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>Estoque<span class="sb-dot"></span></a></li>
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
        <div class="badge-icon"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg></div>
        Gerir Estoque
      </div>
      <span class="sep">•</span>
      <span class="date-chip" id="date"></span>
    </div>
    <div class="top-r">
      <button class="tb-btn" onclick="toggleTheme()">
        <svg class="icon-moon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
        <svg class="icon-sun" style="display:none;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/></svg>
      </button>
    </div>
  </header>

  <div class="content">
    <div class="products">
      <div class="toolbar" style="margin-bottom: 20px;">
        <div class="srch">
          <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          <input type="text" id="busca" placeholder="Buscar produto para ajustar..." autofocus>
        </div>
        <div class="tabs">
          <button class="tab on">Todos</button>
          <button class="tab">Em Baixa</button>
          <button class="tab">Esgotados</button>
        </div>
      </div>

      <div class="grid" id="product-grid">
        <?php
        if (mysqli_num_rows($result_produtos) > 0) {
            while ($produto = mysqli_fetch_assoc($result_produtos)) {
                $id = $produto['id'];
                $nome = htmlspecialchars($produto['nome_produto']);
                $categoria = htmlspecialchars($produto['categoria'] ?? 'Sem Categoria');
                $estoque = intval($produto['estoque']);
                
                if (!empty($produto['imagem_url'])) {
                    $caminho_imagem = (strpos($produto['imagem_url'], 'uploads/') === false) ? "uploads/" . $produto['imagem_url'] : $produto['imagem_url'];
                    $imagem_render = "<img src='{$caminho_imagem}' alt='{$nome}' onerror=\"this.src='https://via.placeholder.com/150?text=Sem+Imagem';\">";
                } else {
                    $imagem_render = '<svg fill="none" stroke="#ccc" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/></svg>';
                }

                $status_estoque = ($estoque == 0) ? 'esgotados' : (($estoque <= 5) ? 'em baixa' : 'ok');

                echo "
                <div class='card' data-nome='".strtolower($nome)."' data-status='{$status_estoque}' id='card-{$id}'>
                  <div class='card-img'>{$imagem_render}</div>
                  <span class='card-name'>{$nome}</span>
                  <span class='card-cat'>{$categoria}</span>
                  
                  <div class='stock-controls'>
                      <button class='btn-stock' onclick=\"mudaEstoque({$id}, -1)\">−</button>
                      <input type='number' class='input-stock' id='input-{$id}' value='{$estoque}' onchange=\"salvaEstoque({$id}, this.value)\">
                      <button class='btn-stock' onclick=\"mudaEstoque({$id}, 1)\">+</button>
                  </div>

                  <button class='btn-delete' onclick=\"window.location.href='apagar_estoque.php?id={$id}'\">
                      <svg fill='none' stroke='currentColor' stroke-width='2' viewBox='0 0 24 24' style='width:14px;height:14px;'><polyline points='3 6 5 6 21 6'/><path d='M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2'/></svg>
                      Excluir
                  </button>
                </div>
                ";
            }
        } else {
            echo "<p style='text-align: center; color: var(--ash); width: 100%;'>Nenhum produto cadastrado.</p>";
        }
        ?>
      </div>
    </div>
  </div>
</div>

<script>
 
  document.getElementById('date').textContent = new Date().toLocaleDateString('pt-BR',{weekday:'long',day:'numeric',month:'long'}).replace(/^\w/,c=>c.toUpperCase());

  function mudaEstoque(id, delta) {
      const input = document.getElementById(`input-${id}`);
      let novoValor = parseInt(input.value) + delta;
      
      if(novoValor < 0) novoValor = 0;
      
      input.value = novoValor;
      salvaEstoque(id, novoValor); 
  }

  function salvaEstoque(id, novoValor) {
      fetch('php/acao_estoque.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
              acao: 'atualizar',
              id: id,
              estoque: novoValor
          })
      })
      .then(res => res.json())
      .then(data => {
          if(!data.sucesso) {
              alert("Erro: " + data.mensagem);
          }
      });
  }

  const searchInput = document.getElementById('busca');
  const tabs = document.querySelectorAll('.tab');
  const cards = document.querySelectorAll('.card');

  function filterProducts() {
    const query = searchInput.value.toLowerCase().trim();
    const activeTab = document.querySelector('.tab.on').textContent.toLowerCase();

    cards.forEach(card => {
      const name = card.getAttribute('data-nome');
      const status = card.getAttribute('data-status');
      
      const matchesSearch = name.includes(query);
      let matchesTab = true;
      
      if (activeTab === 'esgotados') matchesTab = (status === 'esgotados');
      if (activeTab === 'em baixa') matchesTab = (status === 'em baixa' || status === 'esgotados');

      if (matchesSearch && matchesTab) {
        card.style.display = 'flex';
      } else {
        card.style.display = 'none';
      }
    });
  }

  searchInput.addEventListener('input', filterProducts);
  tabs.forEach(t => t.addEventListener('click', (e) => {
    tabs.forEach(x => x.classList.remove('on'));
    e.target.classList.add('on');
    filterProducts();
  }));

  function toggleTheme(){ 
      const d = document.documentElement; 
      const t = d.getAttribute('data-theme')==='dark' ? 'light' : 'dark'; 
      d.setAttribute('data-theme',t); localStorage.setItem('theme',t); 
      
      document.querySelector('.icon-moon').style.display = t==='dark'?'none':'block';
      document.querySelector('.icon-sun').style.display = t==='dark'?'block':'none';
  }
  (()=>{ 
      const s=localStorage.getItem('theme'); 
      const t=(s==='dark'||(!s&&window.matchMedia('(prefers-color-scheme: dark)').matches))?'dark':'light';
      document.documentElement.setAttribute('data-theme', t); 
      if(t==='dark') {
          document.querySelector('.icon-moon').style.display = 'none';
          document.querySelector('.icon-sun').style.display = 'block';
      }
  })();
</script>
</body>
</html>