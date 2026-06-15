<?php
include("php/funcoes.php");

// Verifica se tem ID para editar
if(!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: Cadastrar_Fornecedor.php");
    exit;
}

$id = $_GET['id'];

// Busca os dados do fornecedor
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
    <link rel="stylesheet" href="CSS/index.css">
    <link rel="stylesheet" href="CDN/bootstrap-5.3.8-dist/bootstrap-5.3.8-dist/css/bootstrap.min.css">
</head>
<body class="bg-light vh-100 d-flex overflow-hidden text-dark">

    <nav class="sidebar d-flex flex-column flex-shrink-0 text-white">
        <div class="p-4 text-center border-bottom border-secondary border-opacity-25 d-flex justify-content-center align-items-center gap-2">
            <svg class="text-brand" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            <h4 class="m-0 fw-bold">Grão & Massa</h4>
        </div>
        <ul class="nav nav-pills flex-column mb-auto p-3 gap-1">
            <li class="nav-item">
                <a href="#" class="nav-link nav-link-custom d-flex align-items-center gap-3 py-2 px-3">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21.21 15.89A10 10 0 118 2.83M22 12A10 10 0 0012 2v10z"/></svg> Dashboard
                </a>
            </li>
            <li>
                <a href="#" class="nav-link nav-link-custom d-flex align-items-center gap-3 py-2 px-3">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg> Vendas / PDV
                </a>
            </li>
            <li>
                <a href="#" class="nav-link nav-link-custom d-flex align-items-center gap-3 py-2 px-3">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg> Estoque
                </a>
            </li>
            <li>
                <a href="#" class="nav-link nav-link-custom d-flex align-items-center gap-3 py-2 px-3">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg> Relatórios
                </a>
            </li>
        </ul>
    </nav>

    <div class="d-flex flex-column flex-grow-1 overflow-hidden">
        <header class="bg-white border-bottom px-4 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2 text-secondary small fw-semibold">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
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
            <div class="d-flex align-items-center gap-3 mb-4">
                <a href="Cadastrar_Fornecedor.php" class="btn bg-white border shadow-sm rounded-3 p-2">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <polyline points="15 18 9 12 15 6"/>
                    </svg>
                </a>
                <div>
                    <h3 class="fw-bold m-0 d-flex align-items-center gap-2">
                        <svg class="text-brand" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
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
                    <form class="row g-3" method="POST" action="php/salvaFornecedor.php?opcao=E&id=<?php echo $id; ?>">
                        <div class="col-12 mb-2">
                            <h6 class="fw-bold border-bottom pb-2 text-dark">Dados da Empresa</h6>
                        </div>

                        <div class="col-md-8">
                            <label class="form-label small fw-semibold text-secondary">Razão Social / Nome da Empresa <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nNomeEmpresa" value="<?php echo $fornecedor['nome_empresa']; ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-secondary">CNPJ <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nCnpj" id="cnpj" value="<?php echo $fornecedor['cnpj']; ?>" maxlength="18" required>                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-secondary">E-mail</label>
                            <input type="email" class="form-control" name="nEmail" value="<?php echo $fornecedor['email']; ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-secondary">Telefone / WhatsApp <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nTelefone" id="telefone" value="<?php echo $fornecedor['telefone']; ?>" maxlength="15" required>                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-secondary">Categoria</label>
                            <select class="form-control" name="nCategoria">
                                <option value="">Selecione</option>
                                <option <?php echo ($fornecedor['categoria'] == 'Farinhas') ? 'selected' : ''; ?>>Farinhas</option>
                                <option <?php echo ($fornecedor['categoria'] == 'Laticínios') ? 'selected' : ''; ?>>Laticínios</option>
                                <option <?php echo ($fornecedor['categoria'] == 'Bebidas') ? 'selected' : ''; ?>>Bebidas</option>
                                <option <?php echo ($fornecedor['categoria'] == 'Embalagens') ? 'selected' : ''; ?>>Embalagens</option>
                                <option <?php echo ($fornecedor['categoria'] == 'Grãos') ? 'selected' : ''; ?>>Grãos</option>
                                <option <?php echo ($fornecedor['categoria'] == 'Massas') ? 'selected' : ''; ?>>Massas</option>
                            </select>
                        </div>

                        <div class="col-12 mt-4 mb-2">
                            <h6 class="fw-bold border-bottom pb-2 text-dark">Endereço</h6>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label small fw-semibold text-secondary">CEP</label>
                            <input type="text" class="form-control" name="nCep" id="cep" value="<?php echo $fornecedor['cep']; ?>">
                        </div>
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

                        <div class="col-12 mt-4 d-flex justify-content-end gap-2">
                            <a href="Cadastrar_Fornecedor.php" class="btn btn-light border px-4 fw-medium text-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-brand px-4 fw-medium d-flex align-items-center gap-2">Atualizar Fornecedor</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
    <script src="JS/Fornecedores.js"></script>
</body>
</html>