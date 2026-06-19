<?php
include("php/funcaoFornecedor.php");

// Pega o ID da URL
$id = $_GET['id'] ?? 0;

// Carrega os dados do fornecedor
$fornecedor = carregaFornecedor($id);

// Se não encontrar, volta para a lista
if (!$fornecedor) {
    header("Location: Cadastrar_Fornecedor.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grão & Massa - Visualizar Fornecedor</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="CDN/bootstrap-5.3.8-dist/bootstrap-5.3.8-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="CSS/index.css">
    <link rel="stylesheet" href="CSS/sidebar.css">
</head>
<body class="bg-light vh-100 d-flex overflow-hidden text-dark">

<!-- ============================================ -->
<!-- SIDEBAR -->
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

        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="Cadastrar_Fornecedor.php" class="btn-back">
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
                    Visualizar Fornecedor
                </h3>
                <span class="text-secondary small">ID: <?php echo $id; ?></span>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">

                <div class="row g-4">
                    
                    <!-- Coluna da Esquerda - Dados da Empresa -->
                    <div class="col-md-6">
                        <h6 class="fw-bold border-bottom pb-2 text-dark mb-3">Dados da Empresa</h6>
                        
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Razão Social / Nome da Empresa</label>
                            <p class="fw-bold text-dark mb-0"><?php echo $fornecedor['nome_empresa']; ?></p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">CNPJ</label>
                            <p class="fw-bold text-dark mb-0"><?php echo formatarCNPJ($fornecedor['cnpj']); ?></p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">E-mail</label>
                            <p class="fw-bold text-dark mb-0"><?php echo $fornecedor['email'] ?: 'Não informado'; ?></p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Telefone / WhatsApp</label>
                            <p class="fw-bold text-dark mb-0"><?php echo formatarTelefone($fornecedor['telefone']); ?></p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Categoria</label>
                            <p class="fw-bold text-dark mb-0"><?php echo $fornecedor['categoria'] ?: 'Não definida'; ?></p>
                        </div>
                    </div>

                    <!-- Coluna da Direita - Endereço -->
                    <div class="col-md-6">
                        <h6 class="fw-bold border-bottom pb-2 text-dark mb-3">Endereço</h6>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">CEP</label>
                            <p class="fw-bold text-dark mb-0"><?php echo formatarCEP($fornecedor['cep']); ?></p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Logradouro</label>
                            <p class="fw-bold text-dark mb-0"><?php echo $fornecedor['logradouro'] ?: 'Não informado'; ?></p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Número</label>
                            <p class="fw-bold text-dark mb-0"><?php echo $fornecedor['numero'] ?: 'Não informado'; ?></p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Complemento</label>
                            <p class="fw-bold text-dark mb-0"><?php echo $fornecedor['complemento'] ?: 'Não informado'; ?></p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Bairro</label>
                            <p class="fw-bold text-dark mb-0"><?php echo $fornecedor['bairro'] ?: 'Não informado'; ?></p>
                        </div>

                        <div class="row">
                            <div class="col-8">
                                <label class="form-label small fw-semibold text-secondary">Cidade</label>
                                <p class="fw-bold text-dark mb-0"><?php echo $fornecedor['cidade'] ?: 'Não informado'; ?></p>
                            </div>
                            <div class="col-4">
                                <label class="form-label small fw-semibold text-secondary">UF</label>
                                <p class="fw-bold text-dark mb-0"><?php echo $fornecedor['uf'] ?: '--'; ?></p>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Botões de Ação -->
                <div class="mt-4 pt-3 border-top d-flex gap-2 justify-content-end">
                    <a href="alterarFornecedor.php?id=<?php echo $id; ?>" class="btn btn-primary px-4 fw-medium">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                            <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                        Editar
                    </a>
                    <a href="Cadastrar_Fornecedor.php" class="btn btn-light border px-4 fw-medium text-secondary">Voltar</a>
                </div>

            </div>
        </div>

    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="JS/Fornecedores.js"></script>
</body>
</html>