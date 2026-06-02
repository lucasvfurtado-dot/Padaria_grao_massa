<?php
include("php/funcoes.php");
?>
<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Grão & Massa - Cadastrar Cliente</title>
        <link rel="stylesheet" href="CSS/index.css">
        <link rel="stylesheet" href="CDN/bootstrap-5.3.8-dist/bootstrap-5.3.8-dist/css/bootstrap.min.css">
    </head>
<body class="bg-light vh-100 d-flex overflow-hidden text-dark">

    <nav class="sidebar d-flex flex-column flex-shrink-0 text-white">
        <div class="p-4 text-center border-bottom border-secondary border-opacity-25 d-flex justify-content-center align-items-center gap-2">
            <h4 class="m-0 fw-bold">Grão & Massa</h4>
        </div>
    </nav>

    <div class="d-flex flex-column flex-grow-1 overflow-hidden">
        
        <main class="flex-grow-1 p-4 overflow-auto bg-light">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex align-items-center gap-3">
                    <a href="index.html" class="btn btn-white border shadow-sm d-flex align-items-center justify-content-center p-2 rounded-3 bg-white text-secondary">Voltar</a>
                    <div>
                        <h4 class="mb-0 fw-bold text-dark">Cadastrar Cliente</h4>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    
                    <form class="row g-3" method="POST" action="php/salvaCliente.php?opcao=I">
                        
                        <div class="col-12 mb-2">
                            <h6 class="fw-bold border-bottom pb-2 text-dark">Dados Pessoais</h6>
                        </div>

                        <div class="col-md-8">
                            <label class="form-label small fw-semibold text-secondary">Nome Completo / Razão Social <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nNome" placeholder="Ex: João da Silva" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-secondary">CPF / CNPJ <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nCpfCnpj" placeholder="000.000.000-00" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-secondary">E-mail</label>
                            <input type="email" class="form-control" name="nEmail" placeholder="joao@email.com">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-secondary">Telefone / WhatsApp <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nTelefone" placeholder="(00) 00000-0000" required>
                        </div>

                        <div class="col-12 mt-4 mb-2">
                            <h6 class="fw-bold border-bottom pb-2 text-dark">Endereço</h6>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label small fw-semibold text-secondary">CEP</label>
                            <input type="text" class="form-control" name="nCep" placeholder="00000-000">
                        </div>
                        <div class="col-md-7">
                            <label class="form-label small fw-semibold text-secondary">Logradouro (Rua, Av.)</label>
                            <input type="text" class="form-control" name="nLogradouro" placeholder="Rua das Flores">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-semibold text-secondary">Número</label>
                            <input type="text" class="form-control" name="nNumero" placeholder="123">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-secondary">Complemento</label>
                            <input type="text" class="form-control" name="nComplemento" placeholder="Apto 12, Bloco B">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-secondary">Bairro</label>
                            <input type="text" class="form-control" name="nBairro" placeholder="Centro">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold text-secondary">Cidade</label>
                            <input type="text" class="form-control" name="nCidade" placeholder="São Paulo">
                        </div>
                        <div class="col-md-1">
                            <label class="form-label small fw-semibold text-secondary">UF</label>
                            <input type="text" class="form-control" name="nUf" placeholder="SP" maxlength="2">
                        </div>

                        <div class="col-12 mt-4 d-flex justify-content-end gap-2">
                            <button type="reset" class="btn btn-light border px-4 fw-medium text-secondary">Limpar</button>
                            <button type="submit" class="btn btn-brand px-4 fw-medium d-flex align-items-center gap-2">
                                Salvar Cliente
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom p-3">
                    <h6 class="mb-0 fw-bold text-dark">
                        Últimos Clientes Cadastrados (Total: <?php echo qtdClientes(); ?>)
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light text-secondary small">
                                <tr>
                                    <th class="px-4 fw-semibold">Nome</th>
                                    <th class="fw-semibold">Contato</th>
                                    <th class="fw-semibold">Cidade/UF</th>
                                    <th class="text-end px-4 fw-semibold">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="text-dark small">
                                <?php echo listaClientes(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </main>
    </div>
</body>
</html>