<?php
include("php/funcoes.php");

// 🔥 ADICIONE ESTAS FUNÇÕES AQUI TAMBÉM
function formatarCNPJ($cnpj) {
    $cnpj = preg_replace('/[^0-9]/', '', $cnpj);
    if (strlen($cnpj) == 14) {
        return substr($cnpj, 0, 2) . '.' . substr($cnpj, 2, 3) . '.' . 
               substr($cnpj, 5, 3) . '/' . substr($cnpj, 8, 4) . '-' . substr($cnpj, 12, 2);
    }
    return $cnpj;
}

function formatarTelefone($telefone) {
    $telefone = preg_replace('/[^0-9]/', '', $telefone);
    if (strlen($telefone) == 11) {
        return '(' . substr($telefone, 0, 2) . ') ' . substr($telefone, 2, 5) . '-' . substr($telefone, 7, 4);
    } elseif (strlen($telefone) == 10) {
        return '(' . substr($telefone, 0, 2) . ') ' . substr($telefone, 2, 4) . '-' . substr($telefone, 6, 4);
    }
    return $telefone;
}

function formatarCEP($cep) {
    $cep = preg_replace('/[^0-9]/', '', $cep);
    if (strlen($cep) == 8) {
        return substr($cep, 0, 5) . '-' . substr($cep, 5, 3);
    }
    return $cep;
}

if(!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: Cadastrar_Fornecedor.php");
    exit;
}

$id = $_GET['id'];

include("php/conexao.php");
$sql = "SELECT * FROM fornecedores WHERE id = $id";
$result = $conn->query($sql);

if($result->num_rows == 0) {
    header("Location: Cadastrar_Fornecedor.php");
    exit;
}

$fornecedor = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="pt-BR" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Fornecedor - Grão & Massa</title>
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
    <li><a href="index.php" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>Dashboard</a></li>
    <li><a href="vendas.php" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 001.97-1.67L23 6H6"/></svg>Caixa / Vendas</a></li>
    <li><a href="cadastrar_pedidos.php" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>Pedidos</a></li>
    <li><a href="Relatorios.php" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>Relatórios</a></li>
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
              <a href="Cadastrar_Fornecedor.php" class="btn-back" title="Cancelar e Voltar">
                  <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
              </a>
              <div>
                  <h3 class="page-title">
                      <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 7h-9"/><path d="M14 17H5"/><circle cx="17" cy="17" r="3"/><circle cx="7" cy="7" r="3"/></svg>
                      Alterar Fornecedor
                  </h3>
                  <span class="page-desc">Atualize as informações do fornecedor abaixo (ID: <?php echo $id; ?>).</span>
              </div>
          </div>

          <form id="fornecedorForm" method="POST" action="php/salvaFornecedor.php?opcao=E&id=<?php echo $id; ?>" class="content-card">
              
              <div class="form-section-title">Dados da Empresa</div>
              <div class="form-grid">
                  <div class="fg-8">
                      <label class="input-label">Razão Social / Nome da Empresa</label>
                      <input type="text" class="input-field" name="nNomeEmpresa" value="<?php echo htmlspecialchars($fornecedor['nome_empresa'] ?? ''); ?>" required>
                  </div>
                  <div class="fg-4">
                      <label class="input-label">CNPJ</label>
                      <input type="text" class="input-field" name="nCnpj" id="cnpj" value="<?php echo formatarCNPJ($fornecedor['cnpj'] ?? ''); ?>" maxlength="18" required>
                  </div>
                  <div class="fg-5">
                      <label class="input-label">E-mail</label>
                      <input type="email" class="input-field" name="nEmail" value="<?php echo htmlspecialchars($fornecedor['email'] ?? ''); ?>">
                  </div>
                  <div class="fg-4">
                      <label class="input-label">Telefone / WhatsApp</label>
                      <input type="text" class="input-field" name="nTelefone" id="telefone" value="<?php echo formatarTelefone($fornecedor['telefone'] ?? ''); ?>" maxlength="15" required>
                  </div>
                  <div class="fg-3">
                      <label class="input-label">Categoria</label>
                      <select class="input-field" name="nCategoria">
                          <option value="">Selecione</option>
                          <option <?php echo (isset($fornecedor['categoria']) && $fornecedor['categoria'] == 'Farinhas') ? 'selected' : ''; ?>>Farinhas</option>
                          <option <?php echo (isset($fornecedor['categoria']) && $fornecedor['categoria'] == 'Laticínios') ? 'selected' : ''; ?>>Laticínios</option>
                          <option <?php echo (isset($fornecedor['categoria']) && $fornecedor['categoria'] == 'Bebidas') ? 'selected' : ''; ?>>Bebidas</option>
                          <option <?php echo (isset($fornecedor['categoria']) && $fornecedor['categoria'] == 'Embalagens') ? 'selected' : ''; ?>>Embalagens</option>
                          <option <?php echo (isset($fornecedor['categoria']) && $fornecedor['categoria'] == 'Grãos') ? 'selected' : ''; ?>>Grãos</option>
                          <option <?php echo (isset($fornecedor['categoria']) && $fornecedor['categoria'] == 'Massas') ? 'selected' : ''; ?>>Massas</option>
                      </select>
                  </div>
              </div>

              <div class="form-section-title mt-24">Endereço</div>
              <div class="form-grid">
                  <div class="fg-3">
                      <label class="input-label">CEP</label>
                      <input type="text" class="input-field" name="nCep" id="cep" value="<?php echo formatarCEP($fornecedor['cep'] ?? ''); ?>">
                  </div>
                  <div class="fg-7">
                      <label class="input-label">Logradouro (Rua, Av.)</label>
                      <input type="text" class="input-field" name="nLogradouro" id="logradouro" value="<?php echo htmlspecialchars($fornecedor['logradouro'] ?? ''); ?>">
                  </div>
                  <div class="fg-2">
                      <label class="input-label">Número</label>
                      <input type="text" class="input-field" name="nNumero" id="numero" value="<?php echo htmlspecialchars($fornecedor['numero'] ?? ''); ?>">
                  </div>
                  <div class="fg-4">
                      <label class="input-label">Complemento</label>
                      <input type="text" class="input-field" name="nComplemento" value="<?php echo htmlspecialchars($fornecedor['complemento'] ?? ''); ?>">
                  </div>
                  <div class="fg-4">
                      <label class="input-label">Bairro</label>
                      <input type="text" class="input-field" name="nBairro" id="bairro" value="<?php echo htmlspecialchars($fornecedor['bairro'] ?? ''); ?>">
                  </div>
                  <div class="fg-3">
                      <label class="input-label">Cidade</label>
                      <input type="text" class="input-field" name="nCidade" id="cidade" value="<?php echo htmlspecialchars($fornecedor['cidade'] ?? ''); ?>">
                  </div>
                  <div class="fg-1">
                      <label class="input-label">UF</label>
                      <input type="text" class="input-field" name="nUf" id="uf" value="<?php echo htmlspecialchars($fornecedor['uf'] ?? ''); ?>" maxlength="2">
                  </div>
              </div>

              <div class="form-actions" style="justify-content: flex-end;">
                  <button type="submit" class="btn-primary">
                      <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 18px; height: 18px;">
                          <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                          <polyline points="17 21 17 13 7 13 7 21"></polyline>
                          <polyline points="7 3 7 8 15 8"></polyline>
                      </svg>
                      Atualizar Fornecedor
                  </button>
              </div>

          </form>
      </main>
  </div>
</div>

<script src="JS/Fornecedores.js"></script>
<script src="JS/FornecedorValidate.js"></script>
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