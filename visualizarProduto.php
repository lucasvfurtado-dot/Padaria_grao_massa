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
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>
    <main class="dash-main" style="align-items: center; justify-content: center; height: 100vh; background: var(--background);">
        <div style="width: 100%; max-width: 900px;">
            
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
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                        Visualizar Produto
                    </h1>
                    <span class="page-desc">Visualizando detalhes do ID: <strong>#<?php echo $id_produto; ?></strong></span>
                </div>
            </div>
            
            <div class="content-card">
                <div class="form-grid">
                    
                    <div class="fg-4" style="display: flex; flex-direction: column;">
                        <label class="input-label">Imagem do Produto</label>
                        <?php if (!empty($produto['imagem_url'])): ?>
                            <img src="<?php echo $produto['imagem_url']; ?>" alt="Imagem do Produto" style="width: 100%; height: 250px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border);">
                        <?php else: ?>
                            <div style="height: 250px; background: var(--surface); border: 1px dashed var(--border); border-radius: 8px; display: flex; flex-direction: column; align-items: center; justify-content: center; color: var(--ash);">
                                <svg style="width: 48px; height: 48px; margin-bottom: 12px; opacity: 0.5;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                    <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                    <polyline points="21 15 16 10 5 21"></polyline>
                                </svg>
                                <span style="font-size: 13px; font-weight: 500;">Sem Imagem</span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="fg-8">
                        <form class="form-grid">
                            <div class="fg-8">
                                <label class="input-label">Nome do Produto</label>
                                <input type="text" class="input-field" value="<?php echo $produto['nome_produto']; ?>" readonly style="background: var(--surface);">
                            </div>
                            <div class="fg-4">
                                <label class="input-label">Código / Lote</label>
                                <input type="text" class="input-field" value="<?php echo $produto['codigo']; ?>" readonly style="background: var(--surface);">
                            </div>
                            
                            <div class="fg-4">
                                <label class="input-label">Categoria</label>
                                <input type="text" class="input-field" value="<?php echo $produto['categoria']; ?>" readonly style="background: var(--surface);">
                            </div>
                            <div class="fg-4">
                                <label class="input-label">Preço (R$)</label>
                                <input type="text" class="input-field" value="<?php echo number_format($produto['preco'], 2, ',', '.'); ?>" readonly style="background: var(--surface);">
                            </div>
                            <div class="fg-4">
                                <label class="input-label">Estoque</label>
                                <input type="text" class="input-field" value="<?php echo $produto['estoque']; ?> unid." readonly style="background: var(--surface);">
                            </div>

                            <div class="fg-12">
                                <label class="input-label">Descrição Curta</label>
                                <textarea class="input-field" style="height: auto; padding: 12px; resize: none; background: var(--surface);" rows="3" readonly><?php echo $produto['descricao']; ?></textarea>
                            </div>
                            
                            <div class="fg-12" style="display: flex; gap: 8px; margin-top: 8px;">
                                <?php if($produto['produto_ativo'] == 1): ?>
                                    <span style="padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; background: #d1e7dd; color: #0f5132; border: 1px solid #badbcc;">Produto Ativo</span>
                                <?php else: ?>
                                    <span style="padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; background: #f8d7da; color: #842029; border: 1px solid #f5c2c7;">Produto Inativo</span>
                                <?php endif; ?>

                                <?php if($produto['destaque_cardapio'] == 1): ?>
                                    <span style="padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; background: #fff3cd; color: #664d03; border: 1px solid #ffecb5;">⭐ Em Destaque</span>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
            
        </div>
    </main>
</body>
</html>