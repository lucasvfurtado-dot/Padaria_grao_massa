<?php
// Inclui o arquivo de conexão com o banco de dados
include("php/conexao.php");

// Busca todos os produtos ativos no banco de dados, ordenados por nome
$sql_produtos = "SELECT * FROM produtos WHERE produto_ativo = 1 ORDER BY nome_produto ASC";
$result_produtos = mysqli_query($conn, $sql_produtos);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Grão & Massa — Caixa</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=DM+Serif+Display&display=swap" rel="stylesheet">
<link rel="stylesheet" href="CSS/style.css">
<style>
  /* Ajuste rápido para garantir que a imagem do produto preencha o espaço corretamente */
  .card-img img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      border-radius: 8px; /* Ajuste conforme o design do seu CSS original */
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
    <li><a href="index.html" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>Dashboard</a></li>
    <li><a href="vendas.php" class="sb-link on"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 001.97-1.67L23 6H6"/></svg>Caixa / Vendas<span class="sb-dot"></span></a></li>
    <li><a href="#" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>Estoque</a></li>
    <li><a href="#" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>Relatórios</a></li>
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
        <div class="badge-icon"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 001.97-1.67L23 6H6"/></svg></div>
        Caixa
      </div>
      <span class="sep">•</span>
      <span class="date-chip" id="date"></span>
    </div>
    <div class="top-r">
      <button class="tb-btn" onclick="toggleTheme()">
        <svg class="icon-moon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
        <svg class="icon-sun" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
      </button>
    </div>
  </header>

  <div class="content">

    <div class="products">
      <div class="toolbar">
        <div class="srch">
          <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          <input type="text" placeholder="Buscar produto por nome ou código…" autofocus>
          <span class="kbd">F3</span>
        </div>
        <div class="tabs">
          <button class="tab on">Todos</button>
          <button class="tab">Pães</button>
          <button class="tab">Doces</button>
          <button class="tab">Bebidas</button>
          <button class="tab">Salgados</button>
        </div>
      </div>

      <div class="grid">
        <?php
        if (mysqli_num_rows($result_produtos) > 0) {
            while ($produto = mysqli_fetch_assoc($result_produtos)) {
                $id = $produto['id'];
                $nome = addslashes($produto['nome_produto']);
                $nome_display = htmlspecialchars($produto['nome_produto']);
                
                $preco_js = number_format($produto['preco'], 2, '.', '');
                $preco_tela = number_format($produto['preco'], 2, ',', '.');
                
                // --- NOVA LÓGICA DE IMAGEM AQUI ---
                if (!empty($produto['imagem_url'])) {
                    $caminho_banco = $produto['imagem_url'];
                    
                    // Verifica se o caminho salvo já tem a pasta "uploads/". Se não, adiciona.
                    if (strpos($caminho_banco, 'uploads/') === false) {
                        $caminho_imagem = "uploads/" . $caminho_banco;
                    } else {
                        $caminho_imagem = $caminho_banco;
                    }

                    // Renderiza a imagem. Se falhar ao carregar no HTML, coloca um placeholder padrão.
                    $imagem_render = "<img src='{$caminho_imagem}' alt='{$nome_display}' onerror=\"this.onerror=null; this.src='https://via.placeholder.com/150?text=Sem+Imagem';\">";
                } else {
                    // Ícone padrão caso não tenha imagem cadastrada
                    $imagem_render = '<svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>';
                }

                $unidade = 'un';

                echo "
                <div class='card' onclick=\"add({$id}, '{$nome}', {$preco_js}, '{$unidade}')\">
                  <div class='card-img'>{$imagem_render}</div>
                  <span class='card-name'>{$nome_display}</span>
                  <span class='card-price'>R$ {$preco_tela} <span class='card-unit'>/{$unidade}</span></span>
                </div>
                ";
            }
        } else {
            echo "<p style='grid-column: 1/-1; text-align: center; color: var(--ash); padding: 40px;'>Nenhum produto cadastrado ou ativo.</p>";
        }
        ?>
      </div>
    </div>

    <div class="cart">
      <div class="cart-head">
        <div class="cart-title">
          <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 001.97-1.67L23 6H6"/></svg>
          Pedido <span class="cart-n" id="cart-n">0</span>
        </div>
        <button class="cart-clear" onclick="clearCart()">Limpar</button>
      </div>

      <div class="cart-items" id="cart-items">
        <div class="cart-empty" id="empty">
          <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 001.97-1.67L23 6H6"/></svg>
          <p>Nenhum item adicionado</p>
        </div>
      </div>

      <div class="checkout">
        <div class="tot-row"><span>Subtotal</span><span id="sub">R$ 0,00</span></div>
        <div class="tot-row green"><span>Desconto</span><span>— R$ 0,00</span></div>
        <hr class="divider">
        <div class="tot-big">
          <span class="tot-label">Total</span>
          <span class="tot-val" id="tot">R$ 0,00</span>
        </div>

        <button class="btn-ok" onclick="finalizar()" style="margin-top: 16px;">
          <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
          Concluir Pedido
          <span class="btn-ok-kbd">F10</span>
        </button>
      </div>
    </div>

  </div>
</div>

<script>
  document.getElementById('date').textContent = new Date().toLocaleDateString('pt-BR',{weekday:'long',day:'numeric',month:'long'}).replace(/^\w/,c=>c.toUpperCase());

  let cart = [];
  const fmt = n => 'R$ ' + n.toFixed(2).replace('.',',');

  function add(id, name, price, unit) {
    const ex = cart.find(i => i.id === id);
    ex ? ex.qty++ : cart.push({id, name, price, unit, qty: 1});
    render();
  }

  function render() {
    const box = document.getElementById('cart-items');
    const empty = document.getElementById('empty');
    document.getElementById('cart-n').textContent = cart.reduce((s,i)=>s+i.qty,0);
    if (!cart.length) { box.innerHTML=''; box.appendChild(empty); empty.style.display='flex'; updateTot(); return; }
    empty.style.display = 'none';
    box.innerHTML = '';
    cart.forEach((item, i) => {
      const el = document.createElement('div');
      el.className = 'cart-item';
      el.innerHTML = `
        <div class="item-info">
          <div class="item-name">${item.name}</div>
          <div class="item-sub">${fmt(item.price)} / ${item.unit}</div>
        </div>
        <div class="qty">
          <button class="qty-btn minus" onclick="chg(${i},-1)">−</button>
          <input class="qty-val" type="number" value="${item.qty}" min="1" onchange="setQ(${i},this.value)">
          <button class="qty-btn" onclick="chg(${i},1)">+</button>
        </div>
        <div class="item-price">${fmt(item.price*item.qty)}</div>
        <button class="rm-btn" onclick="rm(${i})"><svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg></button>`;
      box.appendChild(el);
    });
    updateTot();
  }

  function chg(i,d){ cart[i].qty = Math.max(1, cart[i].qty+d); render(); }
  function setQ(i,v){ cart[i].qty = Math.max(1, parseInt(v)||1); render(); }
  function rm(i){ cart.splice(i,1); render(); }
  function clearCart(){ cart=[]; render(); }
  function updateTot(){ const s=cart.reduce((t,i)=>t+i.price*i.qty,0); document.getElementById('sub').textContent=fmt(s); document.getElementById('tot').textContent=fmt(s); }
  
  function finalizar() {
    if(!cart.length) {
      alert('Adicione produtos antes de concluir o pedido.');
      return;
    }
    
    const totalVenda = cart.reduce((s,i) => s + i.price * i.qty, 0);

    // Envia os dados via AJAX para o seu arquivo de processamento PHP
    fetch('php/salvar_pedido.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        itens: cart,
        valor_total: totalVenda
      })
    })
    .then(response => response.json())
    .then(data => {
      if(data.sucesso) {
        alert('Pedido concluído com sucesso!');
        clearCart(); 
        // window.location.href = 'gerenciar_pedidos.php'; // Remova o '//' se quiser redirecionar automaticamente
      } else {
        alert('Erro ao concluir pedido: ' + data.mensagem);
      }
    })
    .catch(error => {
      console.error('Erro:', error);
      alert('Ocorreu um erro ao comunicar com o servidor.');
    });
  }

  function toggleTheme(){ const d=document.documentElement; const t=d.getAttribute('data-theme')==='dark'?'light':'dark'; d.setAttribute('data-theme',t); localStorage.setItem('theme',t); }
  (()=>{ const s=localStorage.getItem('theme'); if(s==='dark'||(! s&&window.matchMedia('(prefers-color-scheme: dark)').matches)) document.documentElement.setAttribute('data-theme','dark'); })();
  document.addEventListener('keydown',e=>{ if(e.key==='F3'){e.preventDefault();document.querySelector('.srch input').focus();} if(e.key==='F10'){e.preventDefault();finalizar();} });
  document.querySelectorAll('.tab').forEach(t=>t.addEventListener('click',()=>{ document.querySelectorAll('.tab').forEach(x=>x.classList.remove('on')); t.classList.add('on'); }));
</script>
</body>
</html>