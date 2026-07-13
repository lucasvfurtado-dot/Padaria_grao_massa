<?php
include("php/funcoes.php");
?>
<!DOCTYPE html>
<html lang="pt-BR" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grão & Massa - Cadastrar Cliente</title>
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
    <li><a href="gerenciar_estoque.php" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>Estoque</a></li>
    <li><a href="Relatorios.php" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>Relatórios</a></li>
  </ul>

  <p class="sb-label">Cadastros</p>
  <ul class="sb-nav">
    <li><a href="Cadastrar_Cliente.php" class="sb-link on"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>Clientes<span class="sb-dot"></span></a></li>
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
        <div class="badge-icon"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg></div>
        Clientes
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
                  if($_GET['msg'] == 'sucesso') echo '✅ Cliente cadastrado com sucesso!';
                  if($_GET['msg'] == 'atualizado') echo '✅ Cliente atualizado com sucesso!';
                  if($_GET['msg'] == 'excluido') echo '✅ Cliente excluído com sucesso!';
                  ?>
              </div>
          <?php endif; ?>

          <div class="page-header">
              <a href="index.php" class="btn-back">
                  <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
              </a>
              <div>
                  <h3 class="page-title">
                      <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                          <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                          <circle cx="9" cy="7" r="4"/>
                          <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
                      </svg>
                      Cadastrar Cliente
                  </h3>
                  <span class="page-desc">Preencha os dados abaixo para registrar um novo Cliente.</span>
              </div>
          </div>

          <div class="content-card">
              <form method="POST" action="php/salvaCliente.php?opcao=I">
                  
                  <div class="form-section-title">Dados Pessoais</div>
                  <div class="form-grid">
                      <div class="fg-8">
                          <label class="input-label">Nome Completo / Razão Social <span class="text-danger">*</span></label>
                          <input type="text" class="input-field" name="nNome" placeholder="Ex: João da Silva" required>
                      </div>
                      <div class="fg-4">
                          <label class="input-label">CPF / CNPJ <span class="text-danger">*</span></label>
                          <input type="text" class="input-field" name="nCpfCnpj" id="cpfCnpj" placeholder="000.000.000-00" maxlength="18" required>
                      </div>
                      <div class="fg-6">
                          <label class="input-label">E-mail</label>
                          <input type="email" class="input-field" name="nEmail" placeholder="joao@email.com">
                      </div>
                      <div class="fg-6">
                          <label class="input-label">Telefone / WhatsApp <span class="text-danger">*</span></label>
                          <input type="text" class="input-field" name="nTelefone" id="telefone" placeholder="(00) 00000-0000" maxlength="15" required>
                      </div>
                  </div>

                  <div class="form-section-title mt-24">Endereço</div>
                  <div class="form-grid">
                      <div class="fg-3">
                          <label class="input-label">CEP</label>
                          <input type="text" class="input-field" name="nCep" id="cep" placeholder="00000-000" maxlength="9">
                      </div>
                      <div class="fg-7">
                          <label class="input-label">Logradouro (Rua, Av.)</label>
                          <input type="text" class="input-field" name="nLogradouro" id="logradouro" placeholder="Rua das Flores">
                      </div>
                      <div class="fg-2">
                          <label class="input-label">Número</label>
                          <input type="text" class="input-field" name="nNumero" id="numero" placeholder="123">
                      </div>
                      <div class="fg-4">
                          <label class="input-label">Complemento</label>
                          <input type="text" class="input-field" name="nComplemento" placeholder="Apto 12, Bloco B">
                      </div>
                      <div class="fg-4">
                          <label class="input-label">Bairro</label>
                          <input type="text" class="input-field" name="nBairro" id="bairro" placeholder="Centro">
                      </div>
                      <div class="fg-3">
                          <label class="input-label">Cidade</label>
                          <input type="text" class="input-field" name="nCidade" id="cidade" placeholder="São Paulo">
                      </div>
                      <div class="fg-1">
                          <label class="input-label">UF</label>
                          <input type="text" class="input-field" name="nUf" id="uf" placeholder="SP" maxlength="2">
                      </div>
                  </div>

                  <div class="form-actions">
                      <button type="reset" class="btn-outline">Limpar</button>
                      <button type="submit" class="btn-primary">
                          Salvar Cliente
                      </button>
                  </div>
              </form>
          </div>

          <div class="content-card">
              <div class="card-header">
                  Últimos Clientes Cadastrados (Total: <?php echo qtdClientes(); ?>)
              </div>
              <div class="card-body-table">
                  <table class="data-table">
                      <thead>
                          <tr>
                              <th>Nome</th>
                              <th>Contato</th>
                              <th>Cidade/UF</th>
                              <th class="text-end">Ações</th>
                          </tr>
                      </thead>
                      <tbody>
                          <?php echo listaClientes(); ?>
                      </tbody>
                  </table>
              </div>
          </div>

      </main>
  </div>
</div>

<script src="JS/alertas.js"></script>
<script src="JS/Clientes.js"></script>
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

  // --- MÁSCARA CPF/CNPJ ---
  document.getElementById('cpfCnpj').addEventListener('input', function (e) {
      let value = e.target.value.replace(/\D/g, '');
      if (value.length > 3) value = value.replace(/^(\d{3})(\d)/, '$1.$2');
      if (value.length > 6) value = value.replace(/^(\d{3})\.(\d{3})(\d)/, '$1.$2.$3');
      if (value.length > 9) value = value.replace(/^(\d{3})\.(\d{3})\.(\d{3})(\d)/, '$1.$2.$3-$4');
      e.target.value = value;
  });

  // --- MÁSCARA TELEFONE ---
  document.getElementById('telefone').addEventListener('input', function (e) {
      let value = e.target.value.replace(/\D/g, '');
      if (value.length > 2) value = '(' + value.substring(0,2) + ') ' + value.substring(2);
      if (value.length > 10) value = value.substring(0,10) + '-' + value.substring(10);
      e.target.value = value;
  });

  // --- MÁSCARA CEP ---
  document.getElementById('cep').addEventListener('input', function (e) {
      let value = e.target.value.replace(/\D/g, '');
      if (value.length > 5) value = value.substring(0,5) + '-' + value.substring(5);
      e.target.value = value;
  });
</script>
</body>
</html>