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

include("php/funcaoProduto.php");
?>
<!DOCTYPE html>
<html lang="pt-BR" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grão & Massa - Cadastrar Produto</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=DM+Serif+Display&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="CSS/alertas.css">
    <style>
        .upload-box {
            border: 2px dashed var(--border, #ccc);
            border-radius: 8px;
            padding: 32px 16px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background-color: var(--bg, #f8f9fa);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 12px;
            position: relative;
        }
        .upload-box:hover {
            border-color: var(--brand, #d97706);
            background-color: var(--bg-hover, #fffbeb);
        }
        .upload-box svg {
            width: 36px;
            height: 36px;
            color: var(--text-muted, #6b7280);
        }
        .upload-box input[type="file"] {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }
        .upload-text {
            color: var(--text-dark, #374151);
            font-size: 0.9rem;
            font-weight: 500;
        }
        .upload-text span {
            color: var(--brand, #d97706);
            text-decoration: underline;
        }
        #preview-imagem {
            max-height: 160px;
            border-radius: 6px;
            object-fit: cover;
            display: none;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        }
        .upload-box.has-image {
            padding: 16px;
            border-style: solid;
        }
        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            font-size: 0.9rem;
            color: var(--text-dark, #374151);
            font-weight: 500;
        }
        .checkbox-label input {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: var(--brand, #d97706);
        }

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
    <li><a href="gerenciar_estoque.php" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>Estoque</a></li>
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
          
          <?php if(isset($_GET['msg'])): ?>
              <div class="alert-box alert-success">
                  <?php 
                  if($_GET['msg'] == 'sucesso') echo ' Produto cadastrado com sucesso!';
                  if($_GET['msg'] == 'atualizado') echo ' Produto atualizado com sucesso!';
                  if($_GET['msg'] == 'excluido') echo ' Produto excluído com sucesso!';
                  ?>
              </div>
          <?php endif; ?>

          <div class="page-header">
              <a href="index.php" class="btn-back">
                  <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
              </a>
              <div>
                  <h3 class="page-title">
                      <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 7h-9"/><path d="M14 17H5"/><circle cx="17" cy="17" r="3"/><circle cx="7" cy="7" r="3"/></svg>
                      Cadastrar Produto
                  </h3>
                  <span class="page-desc">Preencha os dados abaixo para registrar um novo Produto no estoque.</span>
              </div>
          </div>

          <div class="content-card">
              <form method="POST" action="php/salvaProdutos.php?opcao=I" enctype="multipart/form-data" id="formProduto">
                  
                  <div class="form-section-title">Informações Básicas</div>
                  <div class="form-grid">
                      <div class="fg-8">
                          <label class="input-label">Nome do Produto <span class="text-danger">*</span></label>
                          <input type="text" class="input-field" name="nProduto" id="nomeProduto" placeholder="Ex: Pão de Queijo Tradicional" required>
                      </div>
                      <div class="fg-4">
                          <label class="input-label">Código / Lote</label>
                          <input type="text" class="input-field" name="nCodigo" id="codigo" placeholder="Ex: COD001">
                      </div>
                      
                      <div class="fg-4">
                          <label class="input-label">Categoria <span class="text-danger">*</span></label>
                          <select class="input-field" name="nCategoria" id="categoria" required>
                              <option value="" disabled selected>Selecione...</option>
                              <option value="Bebidas">Bebidas</option>
                              <option value="Bolos">Bolos</option>
                              <option value="Salgados">Salgados</option>
                              <option value="Doces">Doces</option>
                              <option value="Pães">Pães</option>
                          </select>
                      </div>
                      <div class="fg-4">
                          <label class="input-label">Preço (R$) <span class="text-danger">*</span></label>
                          <input type="text" class="input-field" name="nPreco" id="preco" placeholder="0,00" required>
                      </div>
                      <div class="fg-4">
                          <label class="input-label">Estoque Inicial <span class="text-danger">*</span></label>
                          <input type="number" class="input-field" name="nEstoque" id="estoque" placeholder="0" required>
                      </div>
                  </div>

                  <div class="form-section-title mt-24">Detalhes e Imagem</div>
                  <div class="form-grid">
                      <div class="fg-12">
                          <label class="input-label">Imagem do Produto</label>
                          <div class="upload-box" id="dropzone">
                              <input type="file" name="nImagem" id="imagem" accept="image/*" onchange="previewImage(event)">
                              
                              <img id="preview-imagem" src="" alt="Pré-visualização">
                              
                              <div id="dropzone-text" class="upload-text text-center">
                                  <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                                  <p style="margin: 8px 0 0 0;">Arraste e solte uma imagem ou <span>clique para procurar</span></p>
                                  <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 4px; font-weight: normal;">Formatos suportados: JPG, PNG, WEBP</p>
                              </div>
                          </div>
                      </div>

                      <div class="fg-12 mt-16">
                          <label class="input-label">Descrição Curta</label>
                          <textarea class="input-field" name="nDescricao" id="descricao" rows="3" placeholder="Detalhes dos ingredientes, tamanho, etc..."></textarea>
                      </div>

                      <div class="fg-6 mt-16">
                          <label class="checkbox-label">
                              <input type="checkbox" id="nAtivo" name="nAtivo" value="1" checked>
                              Produto Ativo para Vendas
                          </label>
                      </div>
                      <div class="fg-6 mt-16">
                          <label class="checkbox-label">
                              <input type="checkbox" id="nDestaque" name="nDestaque" value="1">
                              Destacar no Cardápio / PDV
                          </label>
                      </div>
                  </div>

                  <div class="form-actions mt-24">
                      <button type="reset" class="btn-outline" onclick="resetUpload()">Limpar</button>
                      <button type="submit" class="btn-primary" id="btnSalvar">
                          <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:18px;height:18px;"><path d="M5 13l4 4L19 7"/></svg>
                          Salvar Produto
                      </button>
                  </div>
              </form>
          </div>

          <div class="content-card">
              <div class="card-header">
                  Últimos Produtos Cadastrados (Total: <?php echo function_exists('qtdProdutos') ? qtdProdutos() : '0'; ?>)
              </div>
              <div class="card-body-table">
                  <table class="data-table">
                      <thead>
                          <tr>
                              <th>Produto</th>
                              <th>Categoria</th>
                              <th>Preço</th>
                              <th>Estoque</th>
                              <th class="text-end">Ações</th>
                          </tr>
                      </thead>
                      <tbody>
                          <?php 
                              if(function_exists('listaProdutos')){
                                  echo listaProdutos(); 
                              } else {
                                  echo "<tr><td colspan='5' class='text-center py-3'>Nenhum produto listado ainda.</td></tr>";
                              }
                          ?>
                      </tbody>
                  </table>
              </div>
          </div>

      </main>
  </div>
</div>

<script src="JS/alertas.js"></script>
<script src="JS/Produto.js"></script>
<script>
  document.getElementById('date').textContent = new Date().toLocaleDateString('pt-BR',{weekday:'long',day:'numeric',month:'long'}).replace(/^\w/,c=>c.toUpperCase());
  function toggleTheme(){ 
    const d = document.documentElement; const t = d.getAttribute('data-theme') === 'dark' ? 'light' : 'dark'; 
    d.setAttribute('data-theme', t); localStorage.setItem('theme', t); 
  }
  (()=>{ 
    const s = localStorage.getItem('theme'); 
    if(s === 'dark' || (!s && window.matchMedia('(prefers-color-scheme: dark)').matches)) document.documentElement.setAttribute('data-theme','dark'); 
  })();

  function previewImage(event) {
      const input = event.target;
      const preview = document.getElementById('preview-imagem');
      const defaultText = document.getElementById('dropzone-text');
      const dropzone = document.getElementById('dropzone');

      if (input.files && input.files[0]) {
          const reader = new FileReader();
          reader.onload = function(e) {
              preview.src = e.target.result;
              preview.style.display = 'block';
              defaultText.style.display = 'none';
              dropzone.classList.add('has-image');
          }
          reader.readAsDataURL(input.files[0]);
      }
  }

  function resetUpload() {
      document.getElementById('preview-imagem').style.display = 'none';
      document.getElementById('preview-imagem').src = '';
      document.getElementById('dropzone-text').style.display = 'block';
      document.getElementById('dropzone').classList.remove('has-image');
  }

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

  // --- Toggle do menu de Usuário (Sair) ---
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
</script>
</body>
</html>