<?php
include("php/funcaoFornecedor.php");

// 🔥 DEBUG - Mostra os dados do POST se houver erro
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<div style='background:#ffc; padding:15px; margin:10px; border:2px solid #f90;'>";
    echo "<h4>🔍 DEBUG - Dados enviados:</h4>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    echo "</div>";
}

// 🔥 VERIFICA SE TEM MENSAGEM DE ERRO DO CNPJ
$msg = $_GET['msg'] ?? '';
if ($msg == 'cnpj_invalido') {
    echo '<div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <strong>⚠️ Erro!</strong> CNPJ inválido. Digite 14 números.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grão & Massa - Cadastrar Fornecedor</title>
    
    <!-- FONTES -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
    
    <!-- BOOTSTRAP -->
    <link rel="stylesheet" href="CDN/bootstrap-5.3.8-dist/bootstrap-5.3.8-dist/css/bootstrap.min.css">
    
    <!-- CSS PERSONALIZADOS -->
    <link rel="stylesheet" href="CSS/index.css">
    <link rel="stylesheet" href="CSS/sidebar.css">
    <link rel="stylesheet" href="CSS/fornecedor.css">
</head>
<body class="bg-light vh-100 d-flex overflow-hidden text-dark">

<!-- ============================================ -->
<!-- SIDEBAR CORRIGIDA - COPIE ESTA PARTE -->
<!-- ============================================ -->
<nav class="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
            </svg>
        </div>
        <div>
            <div class="sidebar-brand-name">Grão &amp; Massa</div>
            <span class="sidebar-brand-sub">Padaria &amp; Café</span>
        </div>
    </div>
    
    <div class="flex-grow-1 overflow-y-auto">
        <p class="sidebar-menu-label">Menu</p>
        <ul class="sidebar-nav">
            <li>
                <a href="index.html" class="sidebar-nav-item">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="3" y="3" width="7" height="7"/>
                        <rect x="14" y="3" width="7" height="7"/>
                        <rect x="14" y="14" width="7" height="7"/>
                        <rect x="3" y="14" width="7" height="7"/>
                    </svg>
                    <span>Dashboard</span>
                    <span class="nav-dot"></span>
                </a>
            </li>
            <li>
                <a href="vendas.html" class="sidebar-nav-item">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="9" cy="21" r="1"/>
                        <circle cx="20" cy="21" r="1"/>
                        <path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 001.97-1.67L23 6H6"/>
                    </svg>
                    <span>Caixa / Vendas</span>
                    <span class="nav-dot"></span>
                </a>
            </li>
            <li>
                <a href="#" class="sidebar-nav-item">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/>
                    </svg>
                    <span>Estoque</span>
                    <span class="nav-dot"></span>
                </a>
            </li>
            <li>
                <a href="#" class="sidebar-nav-item">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/>
                        <line x1="16" y1="17" x2="8" y2="17"/>
                    </svg>
                    <span>Relatórios</span>
                    <span class="nav-dot"></span>
                </a>
            </li>
        </ul>

        <p class="sidebar-menu-label">Cadastros</p>
        <ul class="sidebar-nav">
            <li>
                <a href="Cadastrar_Cliente.php" class="sidebar-nav-item">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
                    </svg>
                    <span>Clientes</span>
                    <span class="nav-dot"></span>
                </a>
            </li>
            <li>
                <a href="Cadastrar_Funcionario.php" class="sidebar-nav-item">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                    <span>Funcionários</span>
                    <span class="nav-dot"></span>
                </a>
            </li>
            <li>
                <a href="Cadastrar_Fornecedor.php" class="sidebar-nav-item active">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="2" y="7" width="20" height="14" rx="2"/>
                        <path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/>
                    </svg>
                    <span>Fornecedores</span>
                    <span class="nav-dot"></span>
                </a>
            </li>
            <li>
                <a href="Cadastrar_Produto.php" class="sidebar-nav-item">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="3" y="3" width="18" height="18" rx="2"/>
                        <line x1="3" y1="9" x2="21" y2="9"/>
                        <line x1="9" y1="21" x2="9" y2="9"/>
                    </svg>
                    <span>Produtos</span>
                    <span class="nav-dot"></span>
                </a>
            </li>
        </ul>
    </div>
    
    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="user-avatar">AS</div>
            <div>
                <div class="user-name">Admin</div>
                <div class="user-role">Administrador</div>
            </div>
            <svg class="user-menu-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="1"/>
                <circle cx="19" cy="12" r="1"/>
                <circle cx="5" cy="12" r="1"/>
            </svg>
        </div>
    </div>
</nav>
<!-- ============================================ -->
<!-- FIM DA SIDEBAR -->
<!-- ============================================ -->

<div class="d-flex flex-column flex-grow-1 overflow-hidden">
    
    <header class="bg-white border-bottom p-3 px-4 d-flex justify-content-between align-items-center shadow-sm z-1">
        <div class="text-secondary d-flex align-items-center gap-2 fw-medium small">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                <line x1="16" y1="2" x2="16" y2="6"/>
                <line x1="8" y1="2" x2="8" y2="6"/>
                <line x1="3" y1="10" x2="21" y2="10"/>
            </svg>
            <span id="date"></span>
        </div>
        
        <div class="dropdown">
            <div class="d-flex align-items-center gap-2 fw-semibold px-2 py-1 rounded" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;">
                <div class="bg-brand text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; font-size: 14px;">AS</div>
                <span class="text-dark small">Admin</span>
            </div>
        </div>
    </header>

    <main class="flex-grow-1 overflow-auto p-4">
        
        <?php if(isset($_GET['msg'])): ?>
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <?php 
                if($_GET['msg'] == 'sucesso') echo '✅ Fornecedor cadastrado com sucesso!';
                if($_GET['msg'] == 'atualizado') echo '✅ Fornecedor atualizado com sucesso!';
                if($_GET['msg'] == 'excluido') echo '✅ Fornecedor excluído com sucesso!';
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div id="validationAlert" class="validation-alert"></div>

        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="index.html" class="btn-back">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <polyline points="15 18 9 12 15 6"/>
                </svg>
                Voltar
            </a>
            <div>
                <h3 class="fw-bold m-0 d-flex align-items-center gap-2" style="font-size: 1.5rem;">
                    <svg class="text-brand" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 28px; height: 28px;">
                        <path d="M20 7h-9"/>
                        <path d="M14 17H5"/>
                        <circle cx="17" cy="17" r="3"/>
                        <circle cx="7" cy="7" r="3"/>
                    </svg>
                    Cadastrar Fornecedor
                </h3>
                <span class="text-secondary small">Preencha os dados abaixo para registrar um novo fornecedor.</span>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <form id="fornecedorForm" class="row g-3" method="POST" action="php/salvaFornecedor.php?opcao=I" novalidate>
                    
                    <div class="col-12 mb-3"> 
                        <h6 class="fw-bold border-bottom pb-2 text-dark">Dados da Empresa</h6>
                    </div>

                    <div class="col-md-8">
                        <label class="form-label small fw-semibold text-secondary required-field">Razão Social / Nome da Empresa</label>
                        <input type="text" class="form-control" name="nNomeEmpresa" placeholder="Ex: Distribuidora Pão Bom" required>
                    </div>
                    <div class="col-md-4">
                    <label class="form-label small fw-semibold text-secondary required-field">CNPJ</label>
                    <input type="text" class="form-control" name="nCnpj" id="cnpj" placeholder="00.000.000/0000-00" maxlength="18" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-secondary">E-mail</label>
                        <input type="email" class="form-control" name="nEmail" placeholder="empresa@email.com">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-secondary required-field">Telefone / WhatsApp</label>
                        <input type="text" class="form-control" name="nTelefone" id="telefone" placeholder="(00) 00000-0000" maxlength="15" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-secondary">Categoria</label>
                        <select class="form-select" name="nCategoria">
                            <option value="">Selecione</option>
                            <option>Farinhas</option>
                            <option>Laticínios</option>
                            <option>Bebidas</option>
                            <option>Embalagens</option>
                            <option>Grãos</option>
                            <option>Massas</option>
                        </select>
                    </div>

                    <div class="col-12 mt-4 mb-3">
                        <h6 class="fw-bold border-bottom pb-2 text-dark">Endereço</h6>
                    </div>

                    <div class="col-md-3">
                    <label class="form-label small fw-semibold text-secondary">CEP</label>
                    <input type="text" class="form-control" name="nCep" id="cep" placeholder="00000-000" onblur="buscarEndereco()">
                    </div>
                    <div class="col-md-7">
                        <label class="form-label small fw-semibold text-secondary">Logradouro (Rua, Av.)</label>
                        <input type="text" class="form-control" name="nLogradouro" id="logradouro" placeholder="Rua das Flores">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold text-secondary">Número</label>
                        <input type="text" class="form-control" name="nNumero" id="numero" placeholder="123">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-secondary">Complemento</label>
                        <input type="text" class="form-control" name="nComplemento" placeholder="Sala 10, Bloco A">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-secondary">Bairro</label>
                        <input type="text" class="form-control" name="nBairro" id="bairro" placeholder="Centro">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold text-secondary">Cidade</label>
                        <input type="text" class="form-control" name="nCidade" id="cidade" placeholder="São Paulo">
                    </div>
                    <div class="col-md-1">
                        <label class="form-label small fw-semibold text-secondary">UF</label>
                        <input type="text" class="form-control" name="nUf" id="uf" placeholder="SP" maxlength="2">
                    </div>

                    <div class="col-12 mt-4">
                        <div class="action-buttons">
                            <button type="reset" class="btn btn-light border px-4 fw-medium text-secondary">Limpar</button>
                            <button type="submit" class="btn btn-brand px-4 fw-medium d-flex align-items-center gap-2" style="background-color: var(--bs-primary); color: white;">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                    <path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/>
                                    <polyline points="17 21 17 13 7 13 7 21"/>
                                    <polyline points="7 3 7 8 15 8"/>
                                </svg>
                                Salvar Fornecedor
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-bottom p-3">
                <h6 class="mb-0 fw-bold text-dark">Últimos Fornecedores Cadastrados (Total: <?php echo qtdFornecedores(); ?>)</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light text-secondary small">
                            <tr>
                                <th class="px-4 fw-semibold">Empresa</th>
                                <th class="fw-semibold">Contato</th>
                                <th class="fw-semibold">Categoria</th>
                                <th class="fw-semibold">Cidade/UF</th>
                                <th class="text-end px-4 fw-semibold">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="text-dark small">
                            <?php echo listaFornecedores(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="JS/Fornecedores.js"></script>
<script src="JS/FornecedorValidate.js"></script>
</body>
</html>