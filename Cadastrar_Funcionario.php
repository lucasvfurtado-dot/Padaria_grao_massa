<?php
include("PHP/funcaoFuncionario.php");
?>
<!DOCTYPE html>
<html lang="pt-BR" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grão & Massa - Funcionários</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>

  <aside class="sb">
    <div class="sb-brand">
      <div class="sb-icon">
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
      </div>
      <h1 class="sb-name">Grão &amp; Massa<span>Padaria &amp; Café</span></h1>
    </div>

    <p class="sb-label">Menu</p>
    <ul class="sb-nav">
      <li><a href="index.php" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>Dashboard</a></li>
      <li><a href="vendas.html" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 001.97-1.67L23 6H6"/></svg>Caixa / Vendas</a></li>
    </ul>

    <p class="sb-label">Cadastros</p>
    <ul class="sb-nav">
      <li><a href="Cadastrar_Cliente.php" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>Clientes</a></li>
      <li><a href="Cadastrar_Funcionario.php" class="sb-link on"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>Funcionários<span class="sb-dot"></span></a></li>
    </ul>

    <div class="sb-foot">
      <div class="sb-user">
        <div class="sb-av">AD</div>
        <div>
          <div class="sb-uname">Admin</div>
          <div class="sb-urole">Administrador</div>
        </div>
      </div>
    </div>
  </aside>

  <main class="main">
    <header class="top">
      <div class="top-l">
        <div class="badge-pg">
          <div class="badge-icon">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          </div>
          Funcionários
        </div>
        <span class="sep">•</span>
        <span class="date-chip" id="date-chip"></span>
      </div>
      <div class="top-r">
        <button class="tb-btn" onclick="toggleTheme()" title="Alternar Tema">
          <svg class="icon-moon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
          <svg class="icon-sun" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/></svg>
        </button>
      </div>
    </header>

    <div style="flex: 1; padding: 24px; overflow-y: auto; display: flex; flex-direction: column; gap: 24px;">
      
      <?php if(isset($_GET['msg'])): ?>
          <div class="alert-box <?php echo ($_GET['msg'] == 'cpf_duplicado') ? 'alert-danger' : 'alert-success'; ?>">
              <span>
                <?php 
                if($_GET['msg'] == 'sucesso') echo 'Funcionário cadastrado com sucesso!';
                if($_GET['msg'] == 'cpf_duplicado') echo 'Erro: Este CPF já está cadastrado no sistema!';
                if($_GET['msg'] == 'atualizado') echo 'Dados atualizados com sucesso!';
                if($_GET['msg'] == 'excluido') echo 'Funcionário removido com sucesso!';
                ?>
              </span>
          </div>
      <?php endif; ?>

      <div class="page-header">
          <a href="index.php" class="btn-back">
              <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
          </a>
          <div>
              <h2 class="page-title">Cadastrar Funcionário</h2>
              <p class="page-desc">Preencha as informações para registrar um novo colaborador.</p>
          </div>
      </div>

      <section class="content-card">
          <form method="POST" action="PHP/salva_Funcionario.php?opcao=I">
              <div class="form-grid">
                  <div class="form-section-title fg-12">Dados Pessoais e Profissionais</div>
                  
                  <div class="fg-6">
                      <label class="input-label">Nome Completo <span class="text-danger">*</span></label>
                      <input type="text" name="nNomeCompleto" class="input-field" placeholder="Ex: Victor Silva" required>
                  </div>
                  <div class="fg-3">
                      <label class="input-label">CPF <span class="text-danger">*</span></label>
                      <input type="text" name="nCpf" id="cpf" class="input-field" placeholder="000.000.000-00" required>
                  </div>
                  <div class="fg-3">
                      <label class="input-label">Cargo <span class="text-danger">*</span></label>
                      <input type="text" name="nCargo" class="input-field" placeholder="Ex: Padeiro" required>
                  </div>
                  <div class="fg-6">
                      <label class="input-label">E-mail</label>
                      <input type="email" name="nEmail" class="input-field" placeholder="exemplo@email.com">
                  </div>
                  <div class="fg-6">
                      <label class="input-label">Telefone <span class="text-danger">*</span></label>
                      <input type="text" name="nTelefone" id="telefone" class="input-field" placeholder="(00) 00000-0000" required>
                  </div>

                  <div class="form-section-title fg-12 mt-24">Endereço</div>
                  
                  <div class="fg-3">
                      <label class="input-label">CEP</label>
                      <input type="text" name="nCep" id="cep" class="input-field" placeholder="00000-000">
                  </div>
                  <div class="fg-7">
                      <label class="input-label">Logradouro</label>
                      <input type="text" name="nLogradouro" id="logradouro" class="input-field" placeholder="Rua, Avenida...">
                  </div>
                  <div class="fg-2">
                      <label class="input-label">Número</label>
                      <input type="text" name="nNumero" id="numero" class="input-field" placeholder="123">
                  </div>
                  <div class="fg-4">
                      <label class="input-label">Bairro</label>
                      <input type="text" name="nBairro" id="bairro" class="input-field" placeholder="Bairro">
                  </div>
                  <div class="fg-5">
                      <label class="input-label">Cidade</label>
                      <input type="text" name="nCidade" id="cidade" class="input-field" placeholder="Cidade">
                  </div>
                  <div class="fg-3">
                      <label class="input-label">UF</label>
                      <input type="text" name="nUf" id="uf" class="input-field" placeholder="SP" maxlength="2">
                  </div>
              </div>

              <div class="form-actions">
                  <button type="reset" class="btn-outline">Limpar Campos</button>
                  <button type="submit" class="btn-primary">Salvar Colaborador</button>
              </div>
          </form>
      </section>

      <section class="content-card">
          <div class="card-header">
              Colaboradores Registrados (Total: <?php echo qtdFuncionarios(); ?>)
          </div>
          <div class="card-body-table">
              <table class="data-table">
                  <thead>
                      <tr>
                          <th>Nome Completo</th>
                          <th>Cargo Profissional</th>
                          <th>Contato / WhatsApp</th>
                          <th class="text-end">Ações</th>
                      </tr>
                  </thead>
                  <tbody>
                      <?php echo listaFuncionarios(); ?>
                  </tbody>
              </table>
          </div>
      </section>

    </div>
  </main>

<script>
  // Script para renderizar data por extenso de forma amigável no Header
  document.getElementById('date-chip').textContent = new Date().toLocaleDateString('pt-BR', {weekday: 'long', day: 'numeric', month: 'long'}).replace(/^\w/, c => c.toUpperCase());

  // Lógica nativa de Alternar Tema (Dark/Light) associada ao seu CSS
  function toggleTheme(){ 
    const d = document.documentElement; 
    const t = d.getAttribute('data-theme') === 'dark' ? 'light' : 'dark'; 
    d.setAttribute('data-theme', t); 
    localStorage.setItem('theme', t); 
  }
  
  (() => { 
    const s = localStorage.getItem('theme'); 
    if(s === 'dark' || (!s && window.matchMedia('(prefers-color-scheme: dark)').matches)) document.documentElement.setAttribute('data-theme','dark'); 
  })();

  // Autopreenchimento de Endereço via API do ViaCEP
  document.getElementById('cep').addEventListener('blur', function() {
      let cep = this.value.replace(/\D/g, ''); 
      if (cep.length === 8) {
          fetch(`https://viacep.com.br/ws/${cep}/json/`)
              .then(response => response.json())
              .then(data => {
                  if (!data.erro) {
                      document.getElementById('logradouro').value = data.logradouro;
                      document.getElementById('bairro').value = data.bairro;
                      document.getElementById('cidade').value = data.localidade;
                      document.getElementById('uf').value = data.uf;
                      document.getElementById('numero').focus();
                  }
              }).catch(err => console.error('Erro de requisição CEP:', err));
      }
  });
</script>
</body>
</html>