<?php
include("php/funcaoProduto.php");
?>
<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Grão & Massa - Cadastrar Produto</title>
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
                <a href="#" class="nav-link nav-link-custom active d-flex align-items-center gap-3 py-2 px-3">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21.21 15.89A10 10 0 118 2.83M22 12A10 10 0 0012 2v10z"/></svg> Dashboard
                </a>
            </li>
            <li>
                <a href="vendas.html" class="nav-link nav-link-custom d-flex align-items-center gap-3 py-2 px-3">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg> Caixa
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
        
        <header class="bg-white border-bottom p-3 px-4 d-flex justify-content-between align-items-center shadow-sm z-1">
            <div class="text-secondary d-flex align-items-center gap-2 fw-medium small">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                <span id="date"></span>
            </div>
            
            <div class="dropdown">
                <div class="d-flex align-items-center gap-2 fw-semibold px-2 py-1 rounded" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;">
                    <div class="bg-brand text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; font-size: 14px;">AS</div>
                    <span class="text-dark small">Admin <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg></span>
                </div>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                    <li><a class="dropdown-item d-flex align-items-center gap-2 small py-2 text-dark" href="#">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg> Perfil
                    </a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger d-flex align-items-center gap-2 small py-2" href="#">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg> Sair
                    </a></li>
                </ul>
            </div>
        </header>


            <main class="flex-grow-1 overflow-auto p-4">

<div class="d-flex align-items-center gap-3 mb-4">

    <a href="index.html" class="btn bg-white border shadow-sm rounded-3 p-2">
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

            Cadastrar Produto
        </h3>

        <span class="text-secondary small">
            Preencha os dados abaixo para registrar um novo Produto no estoque.
        </span>
    </div>

</div>

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    
                    <form class="row g-3" method="POST" action="php/salvar_produto.php?opcao=I">
                        
                        <div class="col-12 mb-2">
                            <h6 class="fw-bold border-bottom pb-2 text-dark">Informações Básicas</h6>
                        </div>

                        <div class="col-md-8">
                            <label class="form-label small fw-semibold text-secondary">Nome do Produto <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nProduto" placeholder="Ex: Pão de Queijo Tradicional" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-secondary">Código / Lote</label>
                            <input type="text" class="form-control" name="nCodigo" placeholder="Ex: COD001">
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-secondary">Categoria <span class="text-danger">*</span></label>
                            <select class="form-select" name="nCategoria" required>
                                <option value="" disabled selected>Selecione...</option>
                                <option value="Bebidas">Bebidas</option>
                                <option value="Salgados">Salgados</option>
                                <option value="Doces">Doces</option>
                                <option value="Pães">Pães</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-secondary">Preço (R$) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nPreco" placeholder="0.00" required>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-secondary">Estoque Inicial <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="nEstoque" placeholder="0" required>
                        </div>

                        <div class="col-12 mt-4 mb-2">
                            <h6 class="fw-bold border-bottom pb-2 text-dark">Descrição e Ativos</h6>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label small fw-semibold text-secondary">Descrição Curta</label>
                            <textarea class="form-control" name="nDescricao" rows="3" placeholder="Detalhes dos ingredientes, tamanho, etc..."></textarea>
                        </div>

                        <div class="col-md-6 mt-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="nAtivo" name="nAtivo" value="1" checked>
                                <label class="form-check-label small fw-semibold text-secondary" for="nAtivo">Produto Ativo (Disponível no sistema)</label>
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="nDestaque" name="nDestaque" value="1">
                                <label class="form-check-label small fw-semibold text-secondary" for="nDestaque">Destacar no Cardápio</label>
                            </div>
                        </div>

                        <div class="col-12 mt-4 d-flex justify-content-end gap-2">
                            <button type="reset" class="btn btn-light border px-4 fw-medium text-secondary">Limpar</button>
                            <button type="submit" class="btn btn-brand px-4 fw-medium d-flex align-items-center gap-2" style="background-color: var(--bs-primary); color: white;">
                                Salvar Produto
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom p-3">
                    <h6 class="mb-0 fw-bold text-dark">
                        Últimos Produtos Cadastrados (Total: <?php echo function_exists('qtdProdutos') ? qtdProdutos() : '0'; ?>)
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light text-secondary small">
                                <tr>
                                    <th class="px-4 fw-semibold">Produto</th>
                                    <th class="fw-semibold">Categoria</th>
                                    <th class="fw-semibold">Preço</th>
                                    <th class="fw-semibold">Estoque</th>
                                    <th class="text-end px-4 fw-semibold">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="text-dark small">
                                <?php 
                                    if(function_exists('listaProdutos')){
                                        echo listaProdutos(); 
                                    } else {
                                        echo "<tr><td colspan='5' class='text-center py-3'>Nenhum produto listado ainda.</td></tr>";
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </main>
    </div>
    
    <script src="JS/Produto.js"></script>
</body>
</html>