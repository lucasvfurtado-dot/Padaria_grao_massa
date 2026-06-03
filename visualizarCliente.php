<?php
include("php/funcoes.php");

$id_cliente = $_GET['id'] ?? 0;
$cliente = carregaCliente($id_cliente);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Visualizar Cliente</title>
    <link rel="stylesheet" href="CDN/bootstrap-5.3.8-dist/bootstrap-5.3.8-dist/css/bootstrap.min.css">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">
    <div class="card border-0 shadow-sm rounded-4" style="width: 100%; max-width: 800px;">
        <div class="card-header bg-white border-bottom p-4 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-secondary">Visualizar Cliente (ID: <?php echo $id_cliente; ?>)</h5>
            <a href="Cadastrar_Cliente.php" class="btn btn-sm btn-outline-secondary">Voltar</a>
        </div>
        <div class="card-body p-4">
            
            <form class="row g-3"> <div class="col-md-8">
                    <label class="form-label small fw-semibold text-secondary">Nome Completo / Razão Social</label>
                    <input type="text" class="form-control bg-light" value="<?php echo $cliente['nome_razao_social']; ?>" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold text-secondary">CPF / CNPJ</label>
                    <input type="text" class="form-control bg-light" value="<?php echo $cliente['cpf_cnpj']; ?>" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold text-secondary">E-mail</label>
                    <input type="email" class="form-control bg-light" value="<?php echo $cliente['email']; ?>" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold text-secondary">Telefone / WhatsApp</label>
                    <input type="text" class="form-control bg-light" value="<?php echo $cliente['telefone_whatsapp']; ?>" readonly>
                </div>

                <div class="col-md-12 mt-3 text-secondary small">
                    Endereço: <?php echo $cliente['logradouro'].", ".$cliente['numero']." - ".$cliente['bairro'].", ".$cliente['cidade']."/".$cliente['uf']; ?>
                </div>

            </form>
        </div>
    </div>
</body>
</html>