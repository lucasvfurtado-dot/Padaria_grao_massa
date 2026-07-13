<?php
include("php/funcaoProduto.php");

$id_produto = $_GET['id'] ?? 0;
$produto = carregaProduto($id_produto);
?>
<!DOCTYPE html>
<html lang="pt-BR" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Produto - Grão & Massa</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=DM+Serif+Display&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="CSS/alertas.css">
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
    <li><a href="cadastrar_pedido.php" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>Pedidos</a></li>
    <li><a href="Relatorios.php" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>Relatórios</a></li>
  </ul>

  <p class="sb-label">Cadastros</p>
  <ul class="sb-nav">
    <li><a href="Cadastrar_Cliente.php" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>Clientes</a></li>
    <li><a href="Cadastrar_Funcionario.php" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>Funcionários</a></li>
    <li><a href="Cadastrar_Fornecedor.php" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/></svg>Fornecedores</a></li>
    <li><a href="Cadastrar_Produto.php" class="sb-link on"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>Produtos<span class="sb-dot"></span></a></li>
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
        <div class="badge-icon"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg></div>
        Produtos
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
      <main class="dash-main">
          
          <div class="page-header">
              <a href="Cadastrar_Produto.php" class="btn-back" title="Voltar para a lista">
                  <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
              </a>
              <div>
                  <h3 class="page-title">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                          <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                          <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                      </svg>
                      Alterar Produto
                  </h3>
                  <span class="page-desc">Editando as informações registradas do produto (ID: <?php echo $id_produto; ?>).</span>
              </div>
          </div>

          <div class="content-card">
              <div class="form-section-title">Editar Dados</div>
              
              <form class="form-grid" method="POST" action="php/salvaProdutos.php?opcao=U&id=<?php echo $id_produto; ?>" enctype="multipart/form-data" id="formProduto">
                  
                  <input type="hidden" name="nImagemUrl" value="<?php echo htmlspecialchars($produto['imagem_url'] ?? ''); ?>">

                  <div class="fg-8">
                      <label class="input-label">Nome do Produto</label>
                      <input type="text" class="input-field" name="nProduto" id="nomeProduto" value="<?php echo htmlspecialchars($produto['nome_produto'] ?? ''); ?>" required>
                  </div>
                  
                  <div class="fg-4">
                      <label class="input-label">Código / Lote</label>
                      <input type="text" class="input-field" name="nCodigo" id="codigo" value="<?php echo htmlspecialchars($produto['codigo'] ?? ''); ?>">
                  </div>

                  <div class="fg-4">
                      <label class="input-label">Categoria</label>
                      <select class="input-field" name="nCategoria" id="categoria" required style="padding: 0 12px; cursor: pointer;">
                          <option value="Bebidas" <?php echo (isset($produto['categoria']) && $produto['categoria'] == 'Bebidas') ? 'selected' : ''; ?>>Bebidas</option>
                          <option value="Bolos" <?php echo (isset($produto['categoria']) && $produto['categoria'] == 'Bolos') ? 'selected' : ''; ?>>Bolos</option>
                          <option value="Salgados" <?php echo (isset($produto['categoria']) && $produto['categoria'] == 'Salgados') ? 'selected' : ''; ?>>Salgados</option>
                          <option value="Doces" <?php echo (isset($produto['categoria']) && $produto['categoria'] == 'Doces') ? 'selected' : ''; ?>>Doces</option>
                          <option value="Pães" <?php echo (isset($produto['categoria']) && $produto['categoria'] == 'Pães') ? 'selected' : ''; ?>>Pães</option>
                      </select>
                  </div>
                  
                  <div class="fg-4">
                      <label class="input-label">Preço (R$)</label>
                      <input type="text" class="input-field" name="nPreco" id="preco" value="<?php echo htmlspecialchars($produto['preco'] ?? ''); ?>" required>
                  </div>
                  
                  <div class="fg-4">
                      <label class="input-label">Estoque</label>
                      <input type="number" class="input-field" name="nEstoque" id="estoque" value="<?php echo htmlspecialchars($produto['estoque'] ?? '0'); ?>" required>
                  </div>

                  <div class="fg-12" style="margin-top: 8px;">
                      <label class="input-label">Imagem do Produto</label>
                      
                      <div style="display: flex; gap: 16px; align-items: center; background: var(--surface); padding: 12px; border: 1px solid var(--border); border-radius: 8px;">
                          <div>
                              <?php 
                                  $temImagem = !empty($produto['imagem_url']);
                                  $display = $temImagem ? 'block' : 'none';
                                  $src = $temImagem ? $produto['imagem_url'] : '';
                              ?>
                              <img id="preview-imagem" src="<?php echo $src; ?>" alt="Pré-visualização" style="width: 80px; height: 80px; object-fit: cover; border-radius: 6px; display: <?php echo $display; ?>; border: 1px solid var(--border);">
                              
                              <div id="sem-imagem-placeholder" style="width: 80px; height: 80px; background: var(--white); border: 1px dashed var(--border); border-radius: 6px; display: <?php echo $temImagem ? 'none' : 'flex'; ?>; align-items: center; justify-content: center; color: var(--ash);">
                                  <svg style="width: 24px; height: 24px; opacity: 0.5;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                      <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                      <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                      <polyline points="21 15 16 10 5 21"></polyline>
                                  </svg>
                              </div>
                          </div>
                          
                          <div style="flex: 1;">
                              <input type="file" class="input-field" name="nImagem" id="imagem" accept="image/*" style="padding-top: 8px; height: 40px; background: var(--white); cursor: pointer;">
                              <small style="display: block; font-size: 12px; color: var(--ash); margin-top: 6px;">Deixe em branco para manter a imagem atual.</small>
                          </div>
                      </div>
                  </div>

                  <div class="fg-12">
                      <label class="input-label">Descrição Curta</label>
                      <textarea class="input-field" name="nDescricao" id="descricao" rows="3" style="height: auto; padding: 12px; resize: none;"><?php echo htmlspecialchars($produto['descricao'] ?? ''); ?></textarea>
                  </div>

                  <div class="fg-6" style="margin-top: 8px;">
                      <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; user-select: none;">
                          <input type="checkbox" name="nAtivo" value="1" <?php echo (isset($produto['produto_ativo']) && $produto['produto_ativo'] == 1) ? 'checked' : ''; ?> style="width: 18px; height: 18px; accent-color: var(--cr); cursor: pointer;">
                          <span style="font-size: 13.5px; font-weight: 600; color: var(--ink);">Produto Ativo</span>
                      </label>
                  </div>
                  
                  <div class="fg-6" style="margin-top: 8px;">
                      <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; user-select: none;">
                          <input type="checkbox" name="nDestaque" value="1" <?php echo (isset($produto['destaque_cardapio']) && $produto['destaque_cardapio'] == 1) ? 'checked' : ''; ?> style="width: 18px; height: 18px; accent-color: #ffc107; cursor: pointer;">
                          <span style="font-size: 13.5px; font-weight: 600; color: var(--ink);">⭐ Destacar no Cardápio</span>
                      </label>
                  </div>

                  <div class="fg-12 form-actions" style="margin-top: 16px; display: flex; justify-content: flex-end;">
                      <button type="submit" class="btn-primary" id="btnSalvar" style="display: flex; align-items: center; gap: 8px; font-size: 14px; padding: 12px 24px;">
                          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 18px; height: 18px;">
                              <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                              <polyline points="17 21 17 13 7 13 7 21"></polyline>
                              <polyline points="7 3 7 8 15 8"></polyline>
                          </svg>
                          Salvar Alterações
                      </button>
                  </div>
              </form>
              
          </div>

      </main>
  </div>
</div>

<script src="JS/alertas.js"></script>
<script src="JS/Produto.js"></script>

<script>
  document.getElementById('imagem').addEventListener('change', function(e) {
      const preview = document.getElementById('preview-imagem');
      const placeholder = document.getElementById('sem-imagem-placeholder');
      const file = e.target.files[0];
      
      if (file) {
          const reader = new FileReader();
          reader.onload = function(event) {
              preview.src = event.target.result;
              preview.style.display = 'block';
              placeholder.style.display = 'none';
          }
          reader.readAsDataURL(file);
      }
  });

  document.getElementById('formProduto').addEventListener('submit', function(e) {
      const nome = document.getElementById('nomeProduto');
      const preco = document.getElementById('preco');
      const estoque = document.getElementById('estoque');
      const categoria = document.getElementById('categoria');
      
      if (!nome || nome.value.trim() === '') {
          e.preventDefault();
          alertas.warning('O campo Nome do Produto é obrigatório.', '⚠️ Campos Obrigatórios');
          nome.focus();
          return false;
      }
      
      if (!categoria || categoria.value === '') {
          e.preventDefault();
          alertas.warning('Selecione uma categoria para o produto.', '⚠️ Campos Obrigatórios');
          categoria.focus();
          return false;
      }
      
      if (!preco || preco.value.trim() === '') {
          e.preventDefault();
          alertas.warning('O campo Preço é obrigatório.', '⚠️ Campos Obrigatórios');
          preco.focus();
          return false;
      }
      
      const precoLimpo = preco.value.replace(',', '.');
      if (parseFloat(precoLimpo) <= 0) {
          e.preventDefault();
          alertas.warning('O Preço deve ser um valor válido maior que zero.', '⚠️ Preço Inválido');
          preco.focus();
          return false;
      }
      
      if (!estoque || estoque.value === '') {
          e.preventDefault();
          alertas.warning('O campo Estoque é obrigatório.', '⚠️ Campos Obrigatórios');
          estoque.focus();
          return false;
      }
      
      if (parseInt(estoque.value) < 0) {
          e.preventDefault();
          alertas.warning('O Estoque não pode ser negativo.', '⚠️ Estoque Inválido');
          estoque.focus();
          return false;
      }
  });

  document.getElementById('date').textContent = new Date().toLocaleDateString('pt-BR',{weekday:'long',day:'numeric',month:'long'}).replace(/^\w/,c=>c.toUpperCase());
  function toggleTheme(){ 
    const d = document.documentElement; const t = d.getAttribute('data-theme') === 'dark' ? 'light' : 'dark'; 
    d.setAttribute('data-theme', t); localStorage.setItem('theme', t); 
  }
  (()=>{ 
    const s = localStorage.getItem('theme'); 
    if(s === 'dark' || (!s && window.matchMedia('(prefers-color-scheme: dark)').matches)) document.documentElement.setAttribute('data-theme','dark'); 
  })();
</script>
</body>
</html>