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
    <title>Visualizar Produto</title>
    <link rel="stylesheet" href="CDN/bootstrap-5.3.8-dist/bootstrap-5.3.8-dist/css/bootstrap.min.css">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">
    <div class="card border-0 shadow-sm rounded-4" style="width: 100%; max-width: 900px; max-height: 90vh;">
        
        <div class="card-header bg-white border-bottom p-4 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-secondary">Visualizar Produto (ID: <?php echo $id_produto; ?>)</h5>
            <a href="Cadastrar_Produto.php" class="btn btn-sm btn-outline-secondary">Voltar</a>
        </div>
        
        <div class="card-body p-4 overflow-auto">
            
            <div class="row g-4 align-items-start">
                
                <div class="col-md-4 text-center d-flex flex-column">
                    <label class="form-label small fw-semibold text-secondary text-start">Imagem do Produto</label>
                    <?php if (!empty($produto['imagem_url'])): ?>
                        <img src="<?php echo $produto['imagem_url']; ?>" alt="Imagem do Produto" class="img-fluid rounded-3 border shadow-sm" style="max-height: 250px; object-fit: cover; width: 100%;">
                    <?php else: ?>
                        <div class="bg-light border rounded-3 d-flex align-items-center justify-content-center text-secondary" style="height: 250px; width: 100%;">
                            <div class="text-center">
                                <svg style="width: 48px; height: 48px; margin-bottom: 10px;" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                    <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                    <polyline points="21 15 16 10 5 21"></polyline>
                                </svg><br>
                                <small>Sem Imagem</small>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-md-8">
                    <form class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label small fw-semibold text-secondary">Nome do Produto</label>
                            <input type="text" class="form-control bg-light" value="<?php echo $produto['nome_produto']; ?>" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-secondary">Código / Lote</label>
                            <input type="text" class="form-control bg-light" value="<?php echo $produto['codigo']; ?>" readonly>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-secondary">Categoria</label>
                            <input type="text" class="form-control bg-light" value="<?php echo $produto['categoria']; ?>" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-secondary">Preço (R$)</label>
                            <input type="text" class="form-control bg-light" value="<?php echo number_format($produto['preco'], 2, ',', '.'); ?>" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-secondary">Estoque</label>
                            <input type="text" class="form-control bg-light" value="<?php echo $produto['estoque']; ?> unid." readonly>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label small fw-semibold text-secondary">Descrição Curta</label>
                            <textarea class="form-control bg-light" rows="3" readonly><?php echo $produto['descricao']; ?></textarea>
                        </div>
                        
                        <div class="col-md-12 mt-3 d-flex gap-2">
                            <?php if($produto['produto_ativo'] == 1): ?>
                                <span class="badge bg-success px-3 py-2">Produto Ativo</span>
                            <?php else: ?>
                                <span class="badge bg-danger px-3 py-2">Produto Inativo</span>
                            <?php endif; ?>

                            <?php if($produto['destaque_cardapio'] == 1): ?>
                                <span class="badge bg-warning text-dark px-3 py-2">⭐ Em Destaque</span>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</body>
</html>