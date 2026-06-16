<?php
include("php/funcoes.php");

// Pega o ID da URL e carrega os dados
$id_cliente = $_GET['id'] ?? 0;
$cliente = carregaCliente($id_cliente);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Alterar Cliente</title>
    <link rel="stylesheet" href="CDN/bootstrap-5.3.8-dist/bootstrap-5.3.8-dist/css/bootstrap.min.css">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">
    <div class="card border-0 shadow-sm rounded-4" style="width: 100%; max-width: 800px;">
        <div class="card-header bg-white border-bottom p-4 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-primary">Alterar Cliente (ID: <?php echo $id_cliente; ?>)</h5>
            <a href="Cadastrar_Cliente.php" class="btn btn-sm btn-outline-secondary">Voltar</a>
        </div>
        <div class="card-body p-4">
            <form class="row g-3" method="POST" action="php/salvaCliente.php?opcao=U&id=<?php echo $id_cliente; ?>">
                
                <div class="col-md-8">
                    <label class="form-label small fw-semibold text-secondary">Nome Completo / Razão Social</label>
                    <input type="text" class="form-control" name="nNome" value="<?php echo $cliente['nome_razao_social']; ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold text-secondary">CPF / CNPJ</label>
                    <input type="text" class="form-control" name="nCpfCnpj" value="<?php echo $cliente['cpf_cnpj']; ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold text-secondary">E-mail</label>
                    <input type="email" class="form-control" name="nEmail" value="<?php echo $cliente['email']; ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold text-secondary">Telefone / WhatsApp</label>
                    <input type="text" class="form-control" name="nTelefone" value="<?php echo $cliente['telefone_whatsapp']; ?>" required>
                </div>

                <div class="col-md-3 mt-3">
                    <label class="form-label small fw-semibold text-secondary">CEP</label>
                    <input type="text" class="form-control" name="nCep" value="<?php echo $cliente['cep']; ?>">
                </div>
                <div class="col-md-7 mt-3">
                    <label class="form-label small fw-semibold text-secondary">Logradouro</label>
                    <input type="text" class="form-control" name="nLogradouro" value="<?php echo $cliente['logradouro']; ?>">
                </div>
                <div class="col-md-2 mt-3">
                    <label class="form-label small fw-semibold text-secondary">Número</label>
                    <input type="text" class="form-control" name="nNumero" value="<?php echo $cliente['numero']; ?>">
                </div>
                <div class="col-md-5 mt-3">
                    <label class="form-label small fw-semibold text-secondary">Bairro</label>
                    <input type="text" class="form-control" name="nBairro" value="<?php echo $cliente['bairro']; ?>">
                </div>
                <div class="col-md-5 mt-3">
                    <label class="form-label small fw-semibold text-secondary">Cidade</label>
                    <input type="text" class="form-control" name="nCidade" value="<?php echo $cliente['cidade']; ?>">
                </div>
                <div class="col-md-2 mt-3">
                    <label class="form-label small fw-semibold text-secondary">UF</label>
                    <input type="text" class="form-control" name="nUf" value="<?php echo $cliente['uf']; ?>" maxlength="2">
                </div>

                <div class="col-12 mt-4 text-end">
                    <button type="submit" class="btn btn-primary px-4 fw-medium">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>