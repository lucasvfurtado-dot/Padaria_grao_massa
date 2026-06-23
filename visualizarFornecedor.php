<?php
include("php/funcaoFornecedor.php");

$id_fornecedor = $_GET['id'] ?? 0;
$fornecedor = carregaFornecedor($id_fornecedor);

// Se não encontrar, volta para a lista
if (!$fornecedor) {
    header("Location: Cadastrar_Fornecedor.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar Fornecedor - Grão & Massa</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=DM+Serif+Display&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="CSS/style.css">
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
    <li><a href="vendas.html" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 001.97-1.67L23 6H6"/></svg>Caixa / Vendas</a></li>
    <li><a href="#" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>Estoque</a></li>
    <li><a href="#" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>Relatórios</a></li>
  </ul>

  <p class="sb-label">Cadastros</p>
  <ul class="sb-nav">
    <li><a href="Cadastrar_Cliente.php" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>Clientes</a></li>
    <li><a href="Cadastrar_Funcionario.php" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>Funcionários</a></li>
    <li><a href="Cadastrar_Fornecedor.php" class="sb-link on"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/></svg>Fornecedores<span class="sb-dot"></span></a></li>
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
        <div class="badge-icon"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/></svg></div>
        Fornecedores
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
              <a href="Cadastrar_Fornecedor.php" class="btn-back" title="Voltar para a lista">
                  <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
              </a>
              <div>
                  <h3 class="page-title">
                      <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                      Visualizar Fornecedor
                  </h3>
                  <span class="page-desc">Consultando as informações registradas do fornecedor (ID: <?php echo $id_fornecedor; ?>).</span>
              </div>
          </div>

          <div class="content-card">
              <div class="form-section-title">Dados da Empresa</div>
              <div class="form-grid">
                  <div class="fg-8">
                      <label class="input-label">Nome da Empresa / Razão Social</label>
                      <input type="text" class="input-field" value="<?php echo htmlspecialchars($fornecedor['nome_empresa'] ?? ''); ?>" style="background: var(--surface); cursor: default;" readonly>
                  </div>
                  <div class="fg-4">
                      <label class="input-label">CNPJ</label>
                      <input type="text" class="input-field" value="<?php echo htmlspecialchars(isset($fornecedor['cnpj']) ? formatarCNPJ($fornecedor['cnpj']) : ''); ?>" style="background: var(--surface); cursor: default;" readonly>
                  </div>
                  <div class="fg-5">
                      <label class="input-label">E-mail</label>
                      <input type="email" class="input-field" value="<?php echo htmlspecialchars($fornecedor['email'] ?? ''); ?>" style="background: var(--surface); cursor: default;" readonly>
                  </div>
                  <div class="fg-4">
                      <label class="input-label">Telefone / WhatsApp</label>
                      <input type="text" class="input-field" value="<?php echo htmlspecialchars(isset($fornecedor['telefone']) ? formatarTelefone($fornecedor['telefone']) : ''); ?>" style="background: var(--surface); cursor: default;" readonly>
                  </div>
                  <div class="fg-3">
                      <label class="input-label">Categoria</label>
                      <input type="text" class="input-field" value="<?php echo htmlspecialchars($fornecedor['categoria'] ?? ''); ?>" style="background: var(--surface); cursor: default;" readonly>
                  </div>
              </div>

              <div class="form-section-title mt-24">Endereço Registrado</div>
              <div class="form-grid">
                  <div class="fg-3">
                      <label class="input-label">CEP</label>
                      <input type="text" class="input-field" value="<?php echo htmlspecialchars(isset($fornecedor['cep']) ? formatarCEP($fornecedor['cep']) : ''); ?>" style="background: var(--surface); cursor: default;" readonly>
                  </div>
                  <div class="fg-7">
                      <label class="input-label">Logradouro (Rua, Av.)</label>
                      <input type="text" class="input-field" value="<?php echo htmlspecialchars($fornecedor['logradouro'] ?? ''); ?>" style="background: var(--surface); cursor: default;" readonly>
                  </div>
                  <div class="fg-2">
                      <label class="input-label">Número</label>
                      <input type="text" class="input-field" value="<?php echo htmlspecialchars($fornecedor['numero'] ?? ''); ?>" style="background: var(--surface); cursor: default;" readonly>
                  </div>
                  <div class="fg-4">
                      <label class="input-label">Complemento</label>
                      <input type="text" class="input-field" value="<?php echo htmlspecialchars($fornecedor['complemento'] ?? ''); ?>" style="background: var(--surface); cursor: default;" readonly>
                  </div>
                  <div class="fg-4">
                      <label class="input-label">Bairro</label>
                      <input type="text" class="input-field" value="<?php echo htmlspecialchars($fornecedor['bairro'] ?? ''); ?>" style="background: var(--surface); cursor: default;" readonly>
                  </div>
                  <div class="fg-3">
                      <label class="input-label">Cidade</label>
                      <input type="text" class="input-field" value="<?php echo htmlspecialchars($fornecedor['cidade'] ?? ''); ?>" style="background: var(--surface); cursor: default;" readonly>
                  </div>
                  <div class="fg-1">
                      <label class="input-label">UF</label>
                      <input type="text" class="input-field" value="<?php echo htmlspecialchars($fornecedor['uf'] ?? ''); ?>" style="background: var(--surface); cursor: default;" readonly>
                  </div>
              </div>
          </div>

      </main>
  </div>
</div>

<script>
  // Script da data e dark mode (mantendo padronizado)
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