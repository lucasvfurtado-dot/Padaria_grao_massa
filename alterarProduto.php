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
    <title>Alterar Produto</title>
    <link rel="stylesheet" href="CDN/bootstrap-5.3.8-dist/bootstrap-5.3.8-dist/css/bootstrap.min.css">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">
    <div class="card border-0 shadow-sm rounded-4" style="width: 100%; max-width: 800px;">
        
        <div class="card-header bg-white border-bottom p-4 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-primary">Alterar Produto (ID: <?php echo $id_produto; ?>)</h5>
            <a href="Cadastrar_Produto.php" class="btn btn-sm btn-outline-secondary">Voltar</a>
        </div>
        
        <div class="card-body p-4 overflow-auto" style="max-height: 80vh;">
            
            <form class="row g-3" method="POST" action="php/salvaProdutos.php?opcao=U&id=<?php echo $id_produto; ?>" enctype="multipart/form-data">
                
                <input type="hidden" name="nImagemUrl" value="<?php echo $produto['imagem_url']; ?>">

                <div class="col-md-8">
                    <label class="form-label small fw-semibold text-secondary">Nome do Produto</label>
                    <input type="text" class="form-control" name="nProduto" value="<?php echo $produto['nome_produto']; ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold text-secondary">Código / Lote</label>
                    <input type="text" class="form-control" name="nCodigo" id="codigo" value="<?php echo $produto['codigo']; ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label small fw-semibold text-secondary">Categoria</label>
                    <select class="form-select" name="nCategoria" required>
                        <option value="Bebidas" <?php echo ($produto['categoria'] == 'Bebidas') ? 'selected' : ''; ?>>Bebidas</option>
                        <option value="Bolos" <?php echo ($produto['categoria'] == 'Bolos') ? 'selected' : ''; ?>>Bolos</option>
                        <option value="Salgados" <?php echo ($produto['categoria'] == 'Salgados') ? 'selected' : ''; ?>>Salgados</option>
                        <option value="Doces" <?php echo ($produto['categoria'] == 'Doces') ? 'selected' : ''; ?>>Doces</option>
                        <option value="Pães" <?php echo ($produto['categoria'] == 'Pães') ? 'selected' : ''; ?>>Pães</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold text-secondary">Preço (R$)</label>
                    <input type="text" class="form-control" name="nPreco" id="preco" value="<?php echo $produto['preco']; ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold text-secondary">Estoque</label>
                    <input type="number" class="form-control" name="nEstoque" id="estoque" value="<?php echo $produto['estoque']; ?>" required>
                </div>

                <div class="col-md-12 mt-4">
                    <label class="form-label small fw-semibold text-secondary">Nova Imagem do Produto</label>
                    <div class="mb-2">
                        <?php 
                            // Verifica se já tem imagem salva. Se tiver, exibe; se não, esconde.
                            $temImagem = !empty($produto['imagem_url']);
                            $display = $temImagem ? 'block' : 'none';
                            $src = $temImagem ? $produto['imagem_url'] : '';
                        ?>
                        <img id="preview-imagem" src="<?php echo $src; ?>" alt="Pré-visualização" style="max-width: 150px; border-radius: 8px; display: <?php echo $display; ?>; border: 1px solid #ccc;">
                    </div>
                    <input type="file" class="form-control" name="nImagem" id="imagem" accept="image/*">
                    <small class="text-muted">Deixe em branco para manter a imagem atual.</small>
                </div>

                <div class="col-md-12">
                    <label class="form-label small fw-semibold text-secondary">Descrição Curta</label>
                    <textarea class="form-control" name="nDescricao" rows="3"><?php echo $produto['descricao']; ?></textarea>
                </div>

                <div class="col-md-6 mt-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="nAtivo" name="nAtivo" value="1" <?php echo ($produto['produto_ativo'] == 1) ? 'checked' : ''; ?>>
                        <label class="form-check-label small fw-semibold text-secondary" for="nAtivo">Produto Ativo</label>
                    </div>
                </div>
                <div class="col-md-6 mt-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="nDestaque" name="nDestaque" value="1" <?php echo ($produto['destaque_cardapio'] == 1) ? 'checked' : ''; ?>>
                        <label class="form-check-label small fw-semibold text-secondary" for="nDestaque">Destacar no Cardápio</label>
                    </div>
                </div>

                <div class="col-12 mt-4 text-end">
                    <button type="submit" class="btn btn-primary px-4 fw-medium">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>

    <script src="JS/Produto.js"></script>
</body>
</html>