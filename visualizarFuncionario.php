<?php
// Puxa o arquivo unificado que você pediu para gerenciar os funcionários
include("PHP/funcaoFuncionario.php");

// Pega o ID do funcionário enviado pela URL
$id_funcionario = $_GET['id'] ?? 0;

// Carrega os dados do funcionário usando a função do banco de dados
$funcionario = carregaFuncionario($id_funcionario);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar Funcionário</title>
    <link rel="stylesheet" href="CDN/bootstrap-5.3.8-dist/bootstrap-5.3.8-dist/css/bootstrap.min.css">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">
    
    <div class="card border-0 shadow-sm rounded-4" style="width: 100%; max-width: 800px;">
        <div class="card-header bg-white border-bottom p-4 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-secondary">Visualizar Funcionário (ID: <?php echo $id_funcionario; ?>)</h5>
            <a href="Cadastrar_Funcionario.php" class="btn btn-sm btn-outline-secondary">Voltar</a>
        </div>
        <div class="card-body p-4">
            
            <form class="row g-3"> 
                
                <div class="col-md-5">
                    <label class="form-label small fw-semibold text-secondary">Nome Completo</label>
                    <input type="text" class="form-control bg-light" value="<?php echo $funcionario['nome_completo'] ?? 'Não informado'; ?>" readonly>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-secondary">CPF</label>
                    <input type="text" class="form-control bg-light" value="<?php echo $funcionario['cpf'] ?? 'Não informado'; ?>" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold text-secondary">Cargo</label>
                    <input type="text" class="form-control bg-light" value="<?php echo $funcionario['cargo'] ?? 'Não informado'; ?>" readonly>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label small fw-semibold text-secondary">E-mail</label>
                    <input type="email" class="form-control bg-light" value="<?php echo $funcionario['email'] ?? 'Não informado'; ?>" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold text-secondary">Telefone / WhatsApp</label>
                    <input type="text" class="form-control bg-light" value="<?php echo $funcionario['telefone_whatsapp'] ?? 'Não informado'; ?>" readonly>
                </div>

                <div class="col-md-12 mt-4 pt-3 border-top text-secondary small">
                    <strong>Endereço Residencial:</strong><br>
                    <?php 
                    if (!empty($funcionario['logradouro'])) {
                        echo ($funcionario['logradouro'] ?? '') . ", " . 
                             ($funcionario['numero'] ?? '') . " - " . 
                             ($funcionario['bairro'] ?? '') . ", " . 
                             ($funcionario['cidade'] ?? '') . "/" . 
                             ($funcionario['uf'] ?? '') . " | CEP: " . 
                             ($funcionario['cep'] ?? '');
                    } else {
                        echo "Nenhum endereço cadastrado para este funcionário.";
                    }
                    ?>
                </div>

            </form>
        </div>
    </div>

</body>
</html>