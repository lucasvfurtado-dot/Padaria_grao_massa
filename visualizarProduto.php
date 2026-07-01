<?php
include("php/funcaoProduto.php");

// Pega o ID da URL e carrega os dados do produto
$id_produto = $_GET['id'] ?? 0;
$produto = carregaProduto($id_produto);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar Produto - Grão & Massa</title>
    <link rel="stylesheet" href="CSS/index.css">
    <link rel="stylesheet" href="CDN/bootstrap-5.3.8-dist/bootstrap-5.3.8-dist/css/bootstrap.min.css">
    <style>
        /* Ajustes específicos para a barra lateral manter o padrão da primeira imagem */
        .sidebar {
            width: 260px;
            background-color: #212529; /* Fundo escuro */
        }
        .nav-link {
            color: #adb5bd;
            border-radius: 8px;
            padding: 10px 16px;
            font-weight: 500;
        }
        .nav-link:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
        }
        .nav-link.active {
            background-color: #ffc107 !important; /* Amarelo/Mostarda */
            color: #212529 !important;
        }
        .text-brand {
            color: #ffc107;
        }
        /* Ajustes para os inputs de visualização */
        .form-control[readonly], .form-select[disabled] {
            background-color: #f8f9fa;
            opacity: 1;
            color: #495057;
            border-color: #dee2e6;
        }
        .input-label {
            font-size: 13px;
            font-weight: 600;
            color: #6c757d;
            margin-bottom: 4px;
        }
    </style>
</head>
<body class="bg-light vh-100 d-flex overflow-hidden text-dark">

    <nav class="sidebar d-flex flex-column flex-shrink-0 text-white h-100">
        <div class="p-4 text-center border-bottom border-secondary border-opacity-25 d-flex justify-content-center align-items-center gap-2">
            <svg class="text-brand" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="24" height="24">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
            </svg>
            <h5 class="m-0 fw-bold">Grão & Massa</h5>
        </div>
        <ul class="nav nav-pills flex-column mb-auto p-3 gap-2">
            <li>
                <a href="#" class="nav-link d-flex align-items-center gap-3">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path></svg>
                    Início
                </a>
            </li>
            <li>
                <a href="Cadastrar_Produto.php" class="nav-link active d-flex align-items-center gap-3">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path></svg>
                    Produtos
                </a>
            </li>
            <li>
                <a href="#" class="nav-link d-flex align-items-center gap-3">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    Clientes
                </a>
            </li>
        </ul>
        <div class="p-3 border-top border-secondary border-opacity-25">
            <a href="#" class="nav-link d-flex align-items-center gap-3 text-danger">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                Sair
            </a>
        </div>
    </nav>

    <main class="flex-grow-1 overflow-auto d-flex flex-column bg-light">
        
        <header class="bg-white shadow-sm px-4 py-3 d-flex justify-content-between align-items-center">
            <h5 class="m-0 fw-bold text-dark">Visualizar Produto</h5>
            <span class="text-secondary small fw-medium">ID do Produto: #<?php echo $id_produto; ?></span>
        </header>

        <div class="p-4 d-flex justify-content-center">
            <div class="card border-0 shadow-sm rounded-4 w-100" style="max-width: 950px;">
                
                <div class="card-header bg-white border-bottom px-4 py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold text-secondary">Detalhes Cadastrados</h6>
                    <a href="Cadastrar_Produto.php" class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-2 px-3">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                        Voltar
                    </a>
                </div>
                
                <div class="card-body p-4">
                    <div class="row g-4 align-items-start">
                        
                        <div class="col-md-4 text-center">
                            <label class="input-label d-block text-start">Imagem do Produto</label>
                            <?php if (!empty($produto['imagem_url'])): ?>
                                <img src="<?php echo $produto['imagem_url']; ?>" alt="Imagem do Produto" class="img-fluid rounded-3 border" style="width: 100%; height: 220px; object-fit: cover;">
                            <?php else: ?>
                                <div class="bg-light border rounded-3 d-flex flex-column align-items-center justify-content-center text-secondary" style="height: 220px; width: 100%; border-style: dashed !important;">
                                    <svg style="width: 40px; height: 40px; margin-bottom: 10px; opacity: 0.5;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                        <polyline points="21 15 16 10 5 21"></polyline>
                                    </svg>
                                    <small class="fw-semibold">Sem Imagem</small>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-8">
                            <form class="row g-3" onsubmit="return false;">
                                
                                <div class="col-md-8">
                                    <label class="input-label">Nome do Produto</label>
                                    <input type="text" class="form-control" value="<?php echo $produto['nome_produto']; ?>" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label class="input-label">Código / Lote</label>
                                    <input type="text" class="form-control" value="<?php echo $produto['codigo']; ?>" readonly>
                                </div>
                                
                                <div class="col-md-4">
                                    <label class="input-label">Categoria</label>
                                    <input type="text" class="form-control" value="<?php echo $produto['categoria']; ?>" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label class="input-label">Preço (R$)</label>
                                    <input type="text" class="form-control" value="<?php echo number_format($produto['preco'], 2, ',', '.'); ?>" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label class="input-label">Estoque</label>
                                    <input type="text" class="form-control" value="<?php echo $produto['estoque']; ?> unid." readonly>
                                </div>

                                <div class="col-md-12">
                                    <label class="input-label">Descrição Curta</label>
                                    <textarea class="form-control" rows="3" readonly style="resize: none;"><?php echo $produto['descricao']; ?></textarea>
                                </div>
                                
                                <div class="col-md-12 mt-3 d-flex gap-2">
                                    <?php if($produto['produto_ativo'] == 1): ?>
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 fw-semibold rounded-2">Produto Ativo</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-2 fw-semibold rounded-2">Produto Inativo</span>
                                    <?php endif; ?>

                                    <?php if($produto['destaque_cardapio'] == 1): ?>
                                        <span class="badge bg-warning bg-opacity-10 text-dark border border-warning border-opacity-50 px-3 py-2 fw-semibold rounded-2">⭐ Em Destaque</span>
                                    <?php endif; ?>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </main>

</body>
</html>