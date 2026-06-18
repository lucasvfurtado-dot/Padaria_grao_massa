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
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grão & Massa - Editar Fornecedor</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="CDN/bootstrap-5.3.8-dist/bootstrap-5.3.8-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="CSS/index.css">
    <link rel="stylesheet" href="CSS/sidebar.css">
    <link rel="stylesheet" href="CSS/fornecedor.css">
</head>
<body class="bg-light vh-100 d-flex overflow-hidden text-dark">

<!-- ============================================ -->
<!-- SIDEBAR IGUAL À DO CADASTRO (copie a mesma) -->
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
    <header class="bg-white border-bottom px-4 d-flex justify-content-between align-items-center" style="height: 60px;">
        <div class="d-flex align-items-center gap-2 text-secondary small fw-semibold">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                <rect x="3" y="4" width="18" height="18" rx="2"/>
                <line x1="16" y1="2" x2="16" y2="6"/>
                <line x1="8" y1="2" x2="8" y2="6"/>
                <line x1="3" y1="10" x2="21" y2="10"/>
            </svg>
            <span id="date"></span>
        </div>
        <div class="d-flex align-items-center gap-2">
            <div class="bg-brand text-white rounded-circle d-flex align-items-center justify-content-center" style="width:38px;height:38px;">AS</div>
            <span class="fw-semibold">Admin</span>
        </div>
    </header>

    <main class="flex-grow-1 overflow-auto p-4">
        
        <div id="validationAlert" class="validation-alert"></div>

        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="Cadastrar_Fornecedor.php" class="btn-back">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
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
                    Editar Fornecedor
                </h3>
                <span class="text-secondary small">Altere os dados do fornecedor.</span>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <form id="fornecedorForm" class="row g-3" method="POST" action="php/salvaFornecedor.php?opcao=E&id=<?php echo $id; ?>" novalidate>
                    
                    <div class="col-12 mb-3">
                        <h6 class="fw-bold border-bottom pb-2 text-dark">Dados da Empresa</h6>
                    </div>

                    <div class="col-md-8">
                        <label class="form-label small fw-semibold text-secondary required-field">Razão Social / Nome da Empresa</label>
                        <input type="text" class="form-control" name="nNomeEmpresa" value="<?php echo $fornecedor['nome_empresa']; ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-secondary required-field">CNPJ</label>
                        <input type="text" class="form-control" name="nCnpj" id="cnpj" value="<?php echo formatarCNPJ($fornecedor['cnpj']); ?>" maxlength="18" required>                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-secondary">E-mail</label>
                        <input type="email" class="form-control" name="nEmail" value="<?php echo $fornecedor['email']; ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-secondary required-field">Telefone / WhatsApp</label>
                        <input type="text" class="form-control" name="nTelefone" id="telefone" value="<?php echo formatarTelefone($fornecedor['telefone']); ?>" maxlength="15" required>                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-secondary">Categoria</label>
                        <select class="form-select" name="nCategoria">
                            <option value="">Selecione</option>
                            <option <?php echo ($fornecedor['categoria'] == 'Farinhas') ? 'selected' : ''; ?>>Farinhas</option>
                            <option <?php echo ($fornecedor['categoria'] == 'Laticínios') ? 'selected' : ''; ?>>Laticínios</option>
                            <option <?php echo ($fornecedor['categoria'] == 'Bebidas') ? 'selected' : ''; ?>>Bebidas</option>
                            <option <?php echo ($fornecedor['categoria'] == 'Embalagens') ? 'selected' : ''; ?>>Embalagens</option>
                            <option <?php echo ($fornecedor['categoria'] == 'Grãos') ? 'selected' : ''; ?>>Grãos</option>
                            <option <?php echo ($fornecedor['categoria'] == 'Massas') ? 'selected' : ''; ?>>Massas</option>
                        </select>
                    </div>

                    <div class="col-12 mt-4 mb-3">
                        <h6 class="fw-bold border-bottom pb-2 text-dark">Endereço</h6>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small fw-semibold text-secondary">CEP</label>
                        <input type="text" class="form-control" name="nCep" id="cep" value="<?php echo formatarCEP($fornecedor['cep']); ?>">                    </div>
                    <div class="col-md-7">
                        <label class="form-label small fw-semibold text-secondary">Logradouro (Rua, Av.)</label>
                        <input type="text" class="form-control" name="nLogradouro" id="logradouro" value="<?php echo $fornecedor['logradouro']; ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold text-secondary">Número</label>
                        <input type="text" class="form-control" name="nNumero" id="numero" value="<?php echo $fornecedor['numero']; ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-secondary">Complemento</label>
                        <input type="text" class="form-control" name="nComplemento" value="<?php echo $fornecedor['complemento']; ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-secondary">Bairro</label>
                        <input type="text" class="form-control" name="nBairro" id="bairro" value="<?php echo $fornecedor['bairro']; ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold text-secondary">Cidade</label>
                        <input type="text" class="form-control" name="nCidade" id="cidade" value="<?php echo $fornecedor['cidade']; ?>">
                    </div>
                    <div class="col-md-1">
                        <label class="form-label small fw-semibold text-secondary">UF</label>
                        <input type="text" class="form-control" name="nUf" id="uf" value="<?php echo $fornecedor['uf']; ?>" maxlength="2">
                    </div>

                    <div class="col-12 mt-4">
                        <div class="action-buttons">
                            <a href="Cadastrar_Fornecedor.php" class="btn btn-light border px-4 fw-medium text-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-brand px-4 fw-medium d-flex align-items-center gap-2" style="background-color: var(--bs-primary); color: white;">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                    <path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/>
                                    <polyline points="17 21 17 13 7 13 7 21"/>
                                    <polyline points="7 3 7 8 15 8"/>
                                </svg>
                                Atualizar Fornecedor
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="JS/Fornecedores.js"></script>
<script src="JS/FornecedorValidate.js"></script>
</body>
</html>