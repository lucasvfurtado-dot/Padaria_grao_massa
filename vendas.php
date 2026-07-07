<?php
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
  /* 1. Trava a página inteira para não ter scroll geral */
  body {
      overflow: hidden;
  }

  /* 2. Define que a área de conteúdo ocupa o resto da tela */
  .content {
      display: flex;
      height: calc(100vh - 80px);
      overflow: hidden;
  }

  /* 3. A coluna do meio (Lista de Produtos) */
  .products {
      flex: 1;
      display: flex;
      flex-direction: column;
      overflow: hidden;
  }

  /* 4. Scroll apenas no Grid, com tamanho MAIS COMPACTO */
  .grid {
      flex: 1;
      overflow-y: auto !important;
      padding: 10px 20px 20px 20px;
      display: grid;
      /* Largura mínima reduzida para 130px (mais cards por linha) */
      grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)) !important;
      gap: 15px; /* Espaço um pouco menor entre os cards */
      align-content: start;
  }

  /* 5. Altura do card reduzida */
  .card {
      display: flex !important;
      flex-direction: column;
      height: 200px !important; /* Altura bem menor */
      padding: 12px; /* Menos espaçamento interno */
      border-radius: 12px;
      box-shadow: 0 4px 6px rgba(0,0,0,0.05);
      cursor: pointer;
      transition: transform 0.2s;
  }

  .card:hover {
      transform: translateY(-4px);
  }

  /* 6. Imagem mais delicada */
  .card-img {
      height: 80px !important; /* Imagem menor para não roubar espaço */
      width: 100%;
      margin-bottom: 8px;
      flex-shrink: 0;
      display: flex;
      align-items: center;
      justify-content: center;
  }

  .card-img img {
      width: 100%;
      height: 100%;
      object-fit: contain !important; 
      border-radius: 8px;
  }

  /* 7. Textos ajustados para o novo tamanho */
  .card-name {
      font-size: 13px; /* Fonte levemente menor */
      font-weight: 600;
      text-align: center;
      margin-bottom: 6px;
      flex-grow: 1;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
  }

  .card-price {
      font-size: 14px; /* Preço um pouco menor */
      font-weight: 700;
      text-align: center;
  }
  
  /* 8. Deixar o Scroll mais bonito */
  .grid::-webkit-scrollbar {
      width: 8px;
  }
  .grid::-webkit-scrollbar-track {
      background: transparent;
  }
  .grid::-webkit-scrollbar-thumb {
      background-color: #ccc;
      border-radius: 10px;
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
    <li><a href="index.php" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>Dashboard</a></li>
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

      <div class="grid" id="product-grid">
        <?php
        if (mysqli_num_rows($result_produtos) > 0) {
            while ($produto = mysqli_fetch_assoc($result_produtos)) {
                $id = $produto['id'];
                $nome = addslashes($produto['nome_produto']);
                $nome_display = htmlspecialchars($produto['nome_produto']);
                // Tenta puxar a categoria, se não houver, coloca 'Sem Categoria'
                $categoria = htmlspecialchars($produto['categoria'] ?? 'Sem Categoria');
                
                $preco_js = number_format($produto['preco'], 2, '.', '');
                $preco_tela = number_format($produto['preco'], 2, ',', '.');
                
                if (!empty($produto['imagem_url'])) {
                    $caminho_banco = $produto['imagem_url'];
                    if (strpos($caminho_banco, 'uploads/') === false) {
                        $caminho_imagem = "uploads/" . $caminho_banco;
                    } else {
                        $caminho_imagem = $caminho_banco;
                    }
                    $imagem_render = "<img src='{$caminho_imagem}' alt='{$nome_display}' onerror=\"this.onerror=null; this.src='https://via.placeholder.com/150?text=Sem+Imagem';\">";
                } else {
                    $imagem_render = '<svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>';
                }

                $unidade = 'un';
                
               
                $estoque = intval($produto['estoque']); 

                echo "
                <div class='card' data-nome='".strtolower($nome_display)."' data-categoria='".strtolower($categoria)."' onclick=\"add({$id}, '{$nome}', {$preco_js}, '{$unidade}', {$estoque})\">
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
        <div class="cpf-box" style="margin-bottom: 16px;">
          <div class="srch" style="margin-bottom: 8px;">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            <input type="text" id="cpf_cliente" placeholder="CPF do Cliente (Obrigatório)" maxlength="14" oninput="mascaraCPF(this); buscarCliente(this.value)">
          </div>
          <input type="hidden" id="id_cliente" value="">
          <div id="nome_cliente" style="font-size: 13px; color: var(--green); font-weight: 600; padding-left: 5px;"></div>
        </div>
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
  // Define a data atual no topo da tela
  document.getElementById('date').textContent = new Date().toLocaleDateString('pt-BR',{weekday:'long',day:'numeric',month:'long'}).replace(/^\w/,c=>c.toUpperCase());

  let cart = [];
  
  // Função para formatar dinheiro corretamente
  const fmt = n => 'R$ ' + parseFloat(n).toFixed(2).replace('.', ',');

  // --- ALTERAÇÃO AQUI: Função add() passa a verificar o maxQty ---
  function add(id, name, price, unit, maxQty) {
    id = String(id); 
    const ex = cart.find(i => i.id === id);
    
    if (ex) {
      // Bloqueia adição se passar do stock
      if (ex.qty + 1 > ex.maxQty) {
        alert(`Produtos insuficiente! Temos apenas ${ex.maxQty} unidades de ${name}.`);
        return; 
      }
      ex.qty++;
    } else {
      // Bloqueia se stock for 0
      if (maxQty < 1) {
        alert(`Produto fora de Estoque!`);
        return; 
      }
      cart.push({ id, name, price: parseFloat(price), unit, qty: 1, maxQty: parseInt(maxQty) });
    }
    render();
  }

  // --- ALTERAÇÃO AQUI: Função chg() passa a verificar o maxQty ---
  function chg(id, d) { 
    id = String(id);
    const item = cart.find(i => i.id === id);
    if (item) { 
      let newQty = item.qty + d;
      // Impede que os botões + adicionem mais do que o limite
      if (newQty > item.maxQty) {
        alert(`Estoque insuficiente! O limite é de ${item.maxQty} unidades.`);
        return;
      }
      item.qty = Math.max(1, newQty); 
      render(); 
    }
  }
  
  // --- ALTERAÇÃO AQUI: Função setQ() corrige inserção manual inválida ---
  function setQ(id, v) { 
    id = String(id);
    const item = cart.find(i => i.id === id);
    if (item) { 
      let newQty = parseInt(v) || 1; 
      // Retifica para o máximo se digitado for superior ao limite
      if (newQty > item.maxQty) {
        alert(`Estoque insuficiente! O limite é de ${item.maxQty} unidades.`);
        newQty = item.maxQty;
      }
      item.qty = Math.max(1, newQty); 
      render(); 
    }
  }
  
  function rm(id) { 
    id = String(id);
    cart = cart.filter(i => i.id !== id); 
    render(); 
  }
  
  function clearCart() { 
    cart = []; 
    render(); 
  }
  
  function render() {
    const box = document.getElementById('cart-items');
    
    let totalQty = 0;
    let totalPrice = 0;

    cart.forEach(item => {
      totalQty += item.qty;
      totalPrice += (item.price * item.qty);
    });

    document.getElementById('cart-n').textContent = totalQty;
    document.getElementById('sub').textContent = fmt(totalPrice);
    document.getElementById('tot').textContent = fmt(totalPrice);
    
    if (cart.length === 0) { 
      box.innerHTML = `
        <div class="cart-empty" id="empty" style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; color: var(--ash); padding-top: 40px;">
          <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="width: 48px; height: 48px; margin-bottom: 16px;"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 001.97-1.67L23 6H6"/></svg>
          <p>Nenhum item adicionado</p>
        </div>`;
      return; 
    }
    
    box.innerHTML = cart.map(item => `
      <div class="cart-item">
        <div class="item-info">
          <div class="item-name">${item.name}</div>
          <div class="item-sub">${fmt(item.price)} / ${item.unit}</div>
        </div>
        <div class="qty">
          <button class="qty-btn minus" onclick="chg('${item.id}', -1)">−</button>
          <input class="qty-val" type="number" value="${item.qty}" min="1" onchange="setQ('${item.id}', this.value)">
          <button class="qty-btn" onclick="chg('${item.id}', 1)">+</button>
        </div>
        <div class="item-price">${fmt(item.price * item.qty)}</div>
        <button class="rm-btn" onclick="rm('${item.id}')">
          <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
        </button>
      </div>
    `).join('');
  }

  // --- MÁSCARA DE CPF ---
  function mascaraCPF(campo) {
    let cpf = campo.value.replace(/\D/g, ''); 
    if (cpf.length > 3) cpf = cpf.replace(/(\d{3})(\d)/, '$1.$2');
    if (cpf.length > 6) cpf = cpf.replace(/(\d{3})(\d)/, '$1.$2');
    if (cpf.length > 9) cpf = cpf.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
    campo.value = cpf;
  }

  // --- BUSCAR CLIENTE PELO CPF ---
  function buscarCliente(cpf) {
    const divNome = document.getElementById('nome_cliente');
    const inputId = document.getElementById('id_cliente');
    
    if (cpf.length === 14) {
      divNome.style.color = "var(--ash)";
      divNome.textContent = "Buscando...";
      
      let cpfLimpo = cpf.replace(/\D/g, '');

      fetch(`php/buscar_cliente.php?cpf=${cpfLimpo}`)
        .then(res => res.json())
        .then(data => {
          if (data.sucesso) {
            divNome.style.color = "#10b981"; // Verde 
            divNome.textContent = "Cliente: " + data.nome;
            inputId.value = data.id; 
          } else {
            divNome.style.color = "#ef4444"; // Vermelho
            divNome.textContent = "Cliente não encontrado.";
            inputId.value = "";
          }
        })
        .catch(err => {
            console.error(err);
            divNome.textContent = "Erro na busca";
            inputId.value = "";
        });
    } else {
      divNome.textContent = "";
      inputId.value = "";
    }
  }

  // --- INTEGRAÇÃO COM O BANCO DE DADOS ---
  function finalizar() {
    if(!cart.length) {
      alert('Adicione produtos antes de concluir o pedido.');
      return;
    }
    
    const idCliente = document.getElementById('id_cliente').value;

    // Se não tiver ID (seja porque não buscou, ou o CPF não existe no BD) barra a operação
    if (!idCliente) {
      alert('Atenção: É obrigatório informar um CPF válido e cadastrado para concluir a venda!');
      document.getElementById('cpf_cliente').focus(); // Foca no campo do CPF
      return; // Trava a execução
    }

    const totalVenda = cart.reduce((s,i) => s + (i.price * i.qty), 0);

    fetch('php/salvar_pedido.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        itens: cart,
        valor_total: totalVenda,
        cliente_id: idCliente,
        status: 'pendente'
      })
    })
    .then(response => response.json())
    .then(data => {
      if(data.sucesso) {
        alert('Pedido salvo como Pendente com sucesso!');
        clearCart(); 
        
        // Limpar os campos de cliente pós-venda
        document.getElementById('cpf_cliente').value = '';
        document.getElementById('nome_cliente').textContent = '';
        document.getElementById('id_cliente').value = '';
      } else {
        alert('Erro ao concluir pedido: ' + data.mensagem);
      }
    })
    .catch(error => {
      console.error('Erro:', error);
      alert('Ocorreu um erro ao comunicar com o servidor.');
    });
  }

  // --- BUSCA E FILTRO DE CATEGORIAS ---
  const searchInput = document.querySelector('.srch input');
  const tabs = document.querySelectorAll('.tab');
  const cards = document.querySelectorAll('.card');

  function limpaTexto(txt) {
    if (!txt) return '';
    return txt.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase().trim();
  }

  function filterProducts() {
    const query = limpaTexto(searchInput.value);
    
    const activeTabElement = document.querySelector('.tab.on');
    const activeTab = activeTabElement ? limpaTexto(activeTabElement.textContent) : 'todos';

    cards.forEach(card => {
      const name = limpaTexto(card.getAttribute('data-nome'));
      const code = limpaTexto(card.getAttribute('data-codigo'));
      const cat = limpaTexto(card.getAttribute('data-categoria'));
      
      const matchesSearch = name.includes(query) || (code && code.includes(query));
      const matchesTab = (activeTab === 'todos' || cat === activeTab);

      if (matchesSearch && matchesTab) {
        card.style.setProperty('display', 'flex', 'important');
      } else {
        card.style.setProperty('display', 'none', 'important');
      }
    });
  }

  if (searchInput) {
    searchInput.addEventListener('input', filterProducts);
  }

  tabs.forEach(t => t.addEventListener('click', (e) => {
    tabs.forEach(x => x.classList.remove('on'));
    e.target.classList.add('on');
    filterProducts();
  }));

  // --- TEMA E ATALHOS ---
  function toggleTheme(){ const d=document.documentElement; const t=d.getAttribute('data-theme')==='dark'?'light':'dark'; d.setAttribute('data-theme',t); localStorage.setItem('theme',t); }
  (()=> { const s=localStorage.getItem('theme'); if(s==='dark'||(!s&&window.matchMedia('(prefers-color-scheme: dark)').matches)) document.documentElement.setAttribute('data-theme','dark'); })();
  
  document.addEventListener('keydown',e=>{ 
    if(e.key==='F3'){ e.preventDefault(); if(searchInput) searchInput.focus(); } 
    if(e.key==='F10'){ e.preventDefault(); finalizar(); } 
  });
</script>
</body>
</html>