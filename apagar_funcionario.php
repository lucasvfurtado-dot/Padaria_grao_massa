<?php
include("PHP/funcaoFuncionario.php");

$id_funcionario = $_GET['id'] ?? 0;
$funcionario = carregaFuncionario($id_funcionario);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grão & Massa — Apagar Funcionário</title>
    <link rel="stylesheet" href="CSS/funcionario.css">
</head>
<body>

    <div class="confirm-shell">
        <div class="confirm-card">

            <div class="confirm-icon">
                <svg width="64" height="64" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    <line x1="10" y1="11" x2="10" y2="17"></line>
                    <line x1="14" y1="11" x2="14" y2="17"></line>
                </svg>
            </div>

            <h4>Deseja realmente apagar este funcionário?</h4>

            <p>
                Funcionário: <strong><?php echo isset($funcionario['nome_completo']) ? $funcionario['nome_completo'] : 'Não encontrado'; ?></strong><br>
                <span class="id-tag">(ID: <?php echo $id_funcionario; ?>)</span>
            </p>

            <form method="POST" action="PHP/salva_Funcionario.php?opcao=D&id=<?php echo $id_funcionario; ?>" class="confirm-actions">
                <a href="Cadastrar_Funcionario.php" class="btn btn-light">Cancelar</a>
                <button type="submit" class="btn btn-danger">Sim, Apagar</button>
            </form>

        </div>
    </div>

</body>
</html>
