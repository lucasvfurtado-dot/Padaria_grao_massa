<?php
include("PHP/funcaoFuncionario.php");
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grão & Massa — Cadastrar Funcionário</title>
    <link rel="stylesheet" href="CSS/funcionario.css">
</head>
<body>

    <div class="app-shell">

        <nav class="sidebar">
            <div class="sidebar-brand">
                <div class="logo-icon">
                    <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
                <div class="brand-text">
                    Grão &amp; Massa
                    <span class="brand-sub">Padaria &amp; Café</span>
                </div>
            </div>

            <div class="sidebar-scroll">
                <p class="nav-section-title">Menu</p>
                <ul class="nav-list">
                    <li><a href="index.html" class="nav-link-custom">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                        Dashboard<span class="nav-dot"></span></a></li>
                    <li><a href="vendas.html" class="nav-link-custom">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 001.97-1.67L23 6H6"/></svg>
                        Caixa / Vendas<span class="nav-dot"></span></a></li>
                    <li><a href="#" class="nav-link-custom">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
                        Estoque<span class="nav-dot"></span></a></li>
                    <li><a href="#" class="nav-link-custom">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                        Relatórios<span class="nav-dot"></span></a></li>
                </ul>

                <p class="nav-section-title">Cadastros</p>
                <ul class="nav-list">
                    <li><a href="Cadastrar_Cliente.php" class="nav-link-custom">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
                        Clientes<span class="nav-dot"></span></a></li>
                    <li><a href="Cadastrar_Funcionario.php" class="nav-link-custom active">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        Funcionários<span class="nav-dot"></span></a></li>
                    <li><a href="Cadastrar_Fornecedor.php" class="nav-link-custom">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/></svg>
                        Fornecedores<span class="nav-dot"></span></a></li>
                    <li><a href="Cadastrar_Produto.php" class="nav-link-custom">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>
                        Produtos<span class="nav-dot"></span></a></li>
                </ul>
            </div>

            <div class="sidebar-footer">
                <div class="user-card">
                    <div class="user-avatar">AS</div>
                    <div>
                        <div class="user-name">Admin</div>
                        <div class="user-role">Administrador</div>
                    </div>
                    <svg width="14" height="14" class="dots" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/><circle cx="5" cy="12" r="1"/></svg>
                </div>
            </div>
        </nav>

        <div class="main-area">

            <header class="topbar">
                <div class="topbar-title">
                    <div class="topbar-icon-box">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </div>
                    Funcionários
                    <span class="topbar-sep">•</span>
                    <span class="topbar-date" id="date"></span>
                </div>

                <div class="topbar-actions">
                    <button class="icon-btn">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 01-3.46 0"/></svg>
                    </button>
                    <button class="icon-btn" onclick="toggleTheme()">
                        <svg width="15" height="15" class="icon-moon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
                        <svg width="15" height="15" class="icon-sun" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
                    </button>

                    <div class="header-user">
                        <div class="header-user-trigger" data-dropdown-trigger>
                            <div class="header-user-avatar">AS</div>
                            <span>Admin</span>
                            <svg width="14" height="14" class="chevron" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
                        </div>
                        <div class="dropdown-menu" data-dropdown-menu>
                            <a class="dropdown-item" href="#">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg> Perfil
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item danger" href="#">
                                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg> Sair
                            </a>
                        </div>
                    </div>
                </div>
            </header>

            <main class="content">

                <div class="page-header">
                    <a href="index.html" class="back-btn">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
                    </a>
                    <div>
                        <h3>
                            <svg width="26" height="26" class="text-brand" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 7h-9"/><path d="M14 17H5"/><circle cx="17" cy="17" r="3"/><circle cx="7" cy="7" r="3"/></svg>
                            Cadastrar Funcionários
                        </h3>
                        <span class="subtitle">Preencha os dados abaixo para registrar um novo Funcionário.</span>
                    </div>
                </div>

                <?php
                $msg = $_GET['msg'] ?? '';
                if ($msg == 'sucesso') {
                    echo '<div class="alert alert-success">Funcionário cadastrado com sucesso!</div>';
                } elseif ($msg == 'cpf_duplicado') {
                    echo '<div class="alert alert-danger">⚠️ Erro: Este CPF já está cadastrado no sistema!</div>';
                } elseif ($msg == 'atualizado') {
                    echo '<div class="alert alert-info">Dados atualizados com sucesso!</div>';
                } elseif ($msg == 'excluido') {
                    echo '<div class="alert alert-warning">Funcionário removido com sucesso!</div>';
                }
                ?>

                <div class="card mb-4">
                    <div class="card-body">
                        <form class="form-grid" method="POST" action="PHP/salva_Funcionario.php?opcao=I">

                            <h6 class="form-section-title">Dados Pessoais &amp; Profissionais</h6>

                            <div class="field col-md-6">
                                <label>Nome Completo <span class="req">*</span></label>
                                <input type="text" name="nNomeCompleto" placeholder="Ex: Victor Silva" required>
                            </div>
                            <div class="field col-md-3">
                                <label>CPF <span class="req">*</span></label>
                                <input type="text" name="nCpf" id="cpf" placeholder="000.000.000-00" required>
                            </div>
                            <div class="field col-md-3">
                                <label>Cargo <span class="req">*</span></label>
                                <input type="text" name="nCargo" placeholder="Ex: Padeiro" required>
                            </div>
                            <div class="field col-md-6">
                                <label>E-mail</label>
                                <input type="email" name="nEmail" placeholder="joao@email.com">
                            </div>
                            <div class="field col-md-6">
                                <label>Telefone / WhatsApp <span class="req">*</span></label>
                                <input type="text" name="nTelefone" id="telefone" placeholder="(00) 00000-0000" required>
                            </div>

                            <h6 class="form-section-title">Endereço</h6>

                            <div class="field col-md-3">
                                <label>CEP</label>
                                <input type="text" name="nCep" id="cep" placeholder="00000-000">
                            </div>
                            <div class="field col-md-7">
                                <label>Logradouro (Rua, Av.)</label>
                                <input type="text" name="nLogradouro" id="logradouro" placeholder="Rua das Flores">
                            </div>
                            <div class="field col-md-2">
                                <label>Número</label>
                                <input type="text" name="nNumero" id="numero" placeholder="123">
                            </div>
                            <div class="field col-md-4">
                                <label>Complemento</label>
                                <input type="text" name="nComplemento" placeholder="Apto 12, Bloco B">
                            </div>
                            <div class="field col-md-4">
                                <label>Bairro</label>
                                <input type="text" name="nBairro" id="bairro" placeholder="Centro">
                            </div>
                            <div class="field col-md-3">
                                <label>Cidade</label>
                                <input type="text" name="nCidade" id="cidade" placeholder="São Paulo">
                            </div>
                            <div class="field col-md-1">
                                <label>UF</label>
                                <input type="text" name="nUf" id="uf" placeholder="SP" maxlength="2">
                            </div>

                            <div class="form-actions">
                                <button type="reset" class="btn btn-light">Limpar</button>
                                <button type="submit" class="btn btn-brand">Salvar Funcionário</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h6>Últimos Funcionários Cadastrados (Total: <?php echo qtdFuncionarios(); ?>)</h6>
                    </div>
                    <div class="card-body" style="padding: 0;">
                        <div class="table-wrap">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th style="padding-left:24px;">Nome</th>
                                        <th>Cargo</th>
                                        <th>Contato</th>
                                        <th class="text-end" style="padding-right:24px;">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php echo listaFuncionarios(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <script src="JS/funcionarios.js"></script>
</body>
</html>
