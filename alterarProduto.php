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
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>
    <main class="dash-main" style="align-items: center; justify-content: center; height: 100vh; background: var(--background);">
        <div style="width: 100%; max-width: 800px;">
            
            <div class="page-header">
                <a href="Cadastrar_Produto.php" class="btn-back" title="Voltar">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                </a>
                <div>
                    <h1 class="page-title">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                        Alterar Produto
                    </h1>
                    <span class="page-desc">Editando informações do ID: <strong>#<?php echo $id_produto; ?></strong></span>
                </div>
            </div>
            
            <div class="content-card">
                <form class="form-grid" method="POST" action="php/salvaProdutos.php?opcao=U&id=<?php echo $id_produto; ?>" enctype="multipart/form-data">
                    
                    <input type="hidden" name="nImagemUrl" value="<?php echo $produto['imagem_url']; ?>">

                    <div class="fg-8">
                        <label class="input-label">Nome do Produto</label>
                        <input type="text" class="input-field" name="nProduto" value="<?php echo $produto['nome_produto']; ?>" required>
                    </div>
                    
                    <div class="fg-4">
                        <label class="input-label">Código / Lote</label>
                        <input type="text" class="input-field" name="nCodigo" id="codigo" value="<?php echo $produto['codigo']; ?>">
                    </div>

                    <div class="fg-4">
                        <label class="input-label">Categoria</label>
                        <select class="input-field" name="nCategoria" required style="padding: 0 8px; cursor: pointer;">
                            <option value="Bebidas" <?php echo ($produto['categoria'] == 'Bebidas') ? 'selected' : ''; ?>>Bebidas</option>
                            <option value="Bolos" <?php echo ($produto['categoria'] == 'Bolos') ? 'selected' : ''; ?>>Bolos</option>
                            <option value="Salgados" <?php echo ($produto['categoria'] == 'Salgados') ? 'selected' : ''; ?>>Salgados</option>
                            <option value="Doces" <?php echo ($produto['categoria'] == 'Doces') ? 'selected' : ''; ?>>Doces</option>
                            <option value="Pães" <?php echo ($produto['categoria'] == 'Pães') ? 'selected' : ''; ?>>Pães</option>
                        </select>
                    </div>
                    
                    <div class="fg-4">
                        <label class="input-label">Preço (R$)</label>
                        <input type="text" class="input-field" name="nPreco" id="preco" value="<?php echo $produto['preco']; ?>" required>
                    </div>
                    
                    <div class="fg-4">
                        <label class="input-label">Estoque</label>
                        <input type="number" class="input-field" name="nEstoque" id="estoque" value="<?php echo $produto['estoque']; ?>" required>
                    </div>

                    <div class="fg-12" style="margin-top: 8px;">
                        <label class="input-label">Imagem do Produto</label>
                        
                        <div style="display: flex; gap: 16px; align-items: center; background: var(--surface); padding: 12px; border: 1px solid var(--border); border-radius: 8px;">
                            <div>
                                <?php 
                                    $temImagem = !empty($produto['imagem_url']);
                                    $display = $temImagem ? 'block' : 'none';
                                    $src = $temImagem ? $produto['imagem_url'] : '';
                                ?>
                                <img id="preview-imagem" src="<?php echo $src; ?>" alt="Pré-visualização" style="width: 80px; height: 80px; object-fit: cover; border-radius: 6px; display: <?php echo $display; ?>; border: 1px solid var(--border);">
                                
                                <div id="sem-imagem-placeholder" style="width: 80px; height: 80px; background: var(--white); border: 1px dashed var(--border); border-radius: 6px; display: <?php echo $temImagem ? 'none' : 'flex'; ?>; align-items: center; justify-content: center; color: var(--ash);">
                                    <svg style="width: 24px; height: 24px; opacity: 0.5;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                        <polyline points="21 15 16 10 5 21"></polyline>
                                    </svg>
                                </div>
                            </div>
                            
                            <div style="flex: 1;">
                                <input type="file" class="input-field" name="nImagem" id="imagem" accept="image/*" style="padding-top: 8px; height: 40px; background: var(--white); cursor: pointer;">
                                <small style="display: block; font-size: 11px; color: var(--ash); margin-top: 4px;">Deixe em branco para manter a imagem atual.</small>
                            </div>
                        </div>
                    </div>

                    <div class="fg-12">
                        <label class="input-label">Descrição Curta</label>
                        <textarea class="input-field" name="nDescricao" rows="3" style="height: auto; padding: 12px; resize: none;"><?php echo $produto['descricao']; ?></textarea>
                    </div>

                    <div class="fg-6" style="margin-top: 8px;">
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; user-select: none;">
                            <input type="checkbox" name="nAtivo" value="1" <?php echo ($produto['produto_ativo'] == 1) ? 'checked' : ''; ?> style="width: 18px; height: 18px; accent-color: var(--cr); cursor: pointer;">
                            <span style="font-size: 13px; font-weight: 600; color: var(--ink);">Produto Ativo</span>
                        </label>
                    </div>
                    
                    <div class="fg-6" style="margin-top: 8px;">
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; user-select: none;">
                            <input type="checkbox" name="nDestaque" value="1" <?php echo ($produto['destaque_cardapio'] == 1) ? 'checked' : ''; ?> style="width: 18px; height: 18px; accent-color: var(--cr); cursor: pointer;">
                            <span style="font-size: 13px; font-weight: 600; color: var(--ink);">⭐ Destacar no Cardápio</span>
                        </label>
                    </div>

                    <div class="fg-12 form-actions">
                        <button type="submit" class="btn-primary">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 16px; height: 16px;">
                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                <polyline points="7 3 7 8 15 8"></polyline>
                            </svg>
                            Salvar Alterações
                        </button>
                    </div>
                </form>
            </div>
            
        </div>
    </main>

    <script src="JS/Produto.js"></script>

    <script>
        document.getElementById('imagem').addEventListener('change', function(e) {
            const preview = document.getElementById('preview-imagem');
            const placeholder = document.getElementById('sem-imagem-placeholder');
            const file = e.target.files[0];
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    preview.src = event.target.result;
                    preview.style.display = 'block';
                    placeholder.style.display = 'none';
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>