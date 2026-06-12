<?php
include("php/funcoes.php");
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grão & Massa - Dashboard</title>
    <link rel="stylesheet" href="CSS/index.css">
    <link rel="stylesheet" href="CDN/bootstrap-5.3.8-dist/bootstrap-5.3.8-dist/css/bootstrap.min.css">
</head>
<body class="bg-light vh-100 d-flex overflow-hidden text-dark">

    <nav class="sidebar d-flex flex-column flex-shrink-0 text-white">
        <div class="p-4 text-center border-bottom border-secondary border-opacity-25 d-flex justify-content-center align-items-center gap-2">
            <svg class="text-brand" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 24px;"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            <h4 class="m-0 fw-bold">Grão & Massa</h4>
        </div>
        <ul class="nav nav-pills flex-column mb-auto p-3 gap-1">
            <li class="nav-item">
                <a href="#" class="nav-link nav-link-custom active d-flex align-items-center gap-3 py-2 px-3">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 16px;"><path d="M21.21 15.89A10 10 0 118 2.83M22 12A10 10 0 0012 2v10z"/></svg> Dashboard
                </a>
            </li>
            <li>
                <a href="#" class="nav-link nav-link-custom d-flex align-items-center gap-3 py-2 px-3">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 16px;"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg> Vendas / PDV
                </a>
            </li>
            <li>
                <a href="#" class="nav-link nav-link-custom d-flex align-items-center gap-3 py-2 px-3">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 16px;"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg> Estoque
                </a>
            </li>
            <li>
                <a href="#" class="nav-link nav-link-custom d-flex align-items-center gap-3 py-2 px-3">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 16px;"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg> Relatórios
                </a>
            </li>
        </ul>
    </nav>

    <div class="d-flex flex-column flex-grow-1 overflow-hidden">
        
        <header class="bg-white border-bottom p-3 px-4 d-flex justify-content-between align-items-center shadow-sm z-1">
            <div class="text-secondary d-flex align-items-center gap-2 fw-medium small">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 16px; height: 16px;"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                <span id="date"></span>
            </div>
            
            <div class="dropdown">
                <div class="d-flex align-items-center gap-2 fw-semibold px-2 py-1 rounded" style="cursor: pointer;">
                    <div class="bg-brand text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; font-size: 14px;">AS</div>
                    <span class="text-dark small">Admin Silva</span>
                </div>
            </div>
        </header>

        <main class="flex-grow-1 p-4 overflow-auto bg-light">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex align-items-center gap-3">
                    <a href="index.html" class="btn btn-white border shadow-sm d-flex align-items-center justify-content-center p-2 rounded-3 bg-white text-secondary" title="Voltar ao Dashboard">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 20px; height: 20px;"><polyline points="15 18 9 12 15 6"/></svg>
                    </a>
                    <div>
                        <h4 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                            <svg class="text-brand" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 24px; height: 24px;"><path d="M20 7h-3V4c0-1.1-.9-2-2-2H9c-1.1 0-2 .9-2 2v3H4c-1.1 0-2 .9-2 2v11c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V9c0-1.1-.9-2-2-2zM9 4h6v3H9V4zm11 16H4V9h16v11z"/></svg>
                            Cadastrar Produto
                        </h4>
                        <span class="text-secondary small">Preencha os dados abaixo para registrar um novo produto no estoque.</span>
                    </div>
                </div>
            </div>

            <div id="alert-container"></div>

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    
                    <form action="../back/salvar_produto.php" method="POST" class="row g-3">
                        
                        <div class="col-12 mb-2">
                            <h6 class="fw-bold border-bottom pb-2 text-dark">Informações do Produto</h6>
                        </div>

                        <div class="col-md-8">
                            <label class="form-label small fw-semibold text-secondary">Nome do Produto <span class="text-danger">*</span></label>
                            <input type="text" name="nome" class="form-control" placeholder="Ex: Pão de forma" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-secondary">Lote / Código Interno</label>
                            <input type="text" name="lote" class="form-control" placeholder="Ex: SKU001">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-secondary">Categoria <span class="text-danger">*</span></label>
                            <select name="categoria" class="form-select" required>
                                <option value="" disabled selected>Selecione uma categoria</option>
                                <option value="Bebidas">Bebidas</option>
                                <option value="Bolos">Bolos</option>
                                <option value="Salgados">Salgados</option>
                                <option value="Doces">Doces</option>
                                <option value="Pães">Pães</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold text-secondary">Preço <span class="text-danger">*</span></label>
                            <input type="text" name="preco" class="form-control" placeholder="0.00" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold text-secondary">Estoque Inicial <span class="text-danger">*</span></label>
                            <input type="number" name="estoque" class="form-control" placeholder="0" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-semibold text-secondary">Descrição</label>
                            <textarea name="descricao" class="form-control" rows="4" placeholder="Descrição detalhada do produto..."></textarea>
                        </div>

                        <div class="col-12 mt-4 d-flex justify-content-end gap-2">
                            <a href="index.html" class="btn btn-light border px-4 fw-medium text-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-brand px-4 fw-medium d-flex align-items-center gap-2" style="background-color: var(--bs-primary); color: white;">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 16px; height: 16px;"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                Salvar Produto
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            // Gerencia a data dinamicamente no cabeçalho
            const dateStr = new Date().toLocaleDateString('pt-BR', {weekday: 'long', day: 'numeric', month: 'long'});
            const formattedDate = dateStr.charAt(0).toUpperCase() + dateStr.slice(1);
            const dateEl = document.getElementById('date');
            if (dateEl) dateEl.innerText = formattedDate;

            // Verifica se o script PHP retornou status de resposta na URL
            const urlParams = new URLSearchParams(window.location.search);
            const status = urlParams.get('status');
            const container = document.getElementById('alert-container');

            if (status === 'sucesso') {
                container.innerHTML = "<div class='alert alert-success shadow-sm rounded-3 mb-4'>✨ Produto cadastrado com sucesso!</div>";
            } else if (status === 'erro') {
                const erroMsg = urlParams.get('msg') ? decodeURIComponent(urlParams.get('msg')) : 'Erro desconhecido.';
                container.innerHTML = "<div class='alert alert-danger shadow-sm rounded-3 mb-4'>❌ Erro ao salvar no banco: " + erroMsg + "</div>";
            }
        });
    </script>
</body>
</html>