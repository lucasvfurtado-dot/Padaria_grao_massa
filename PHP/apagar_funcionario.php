<?php
include("php/funcoes.php");

// Pega o ID da URL e usa a função do funcionário para carregar os dados
$id_funcionario = $_GET['id'] ?? 0;
$funcionario = carregaFuncionario($id_funcionario); 
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Apagar Funcionário</title>
    <link rel="stylesheet" href="CDN/bootstrap-5.3.8-dist/bootstrap-5.3.8-dist/css/bootstrap.min.css">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">
    
    <div class="card border-0 shadow-sm rounded-4 text-center p-5" style="max-width: 500px; width: 100%;">
        
        <div class="mb-4 text-danger d-flex justify-content-center">
            <svg style="width: 72px; height: 72px;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <polyline points="3 6 5 6 21 6"></polyline>
                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                <line x1="10" y1="11" x2="10" y2="17"></line>
                <line x1="14" y1="11" x2="14" y2="17"></line>
            </svg>
        </div>
        
        <h4 class="fw-bold text-dark mb-3">Deseja realmente apagar este funcionário?</h4>
        
        <p class="text-secondary fs-5 mb-4">
            Funcionário: <strong class="text-dark"><?php echo $funcionario['nome_completo']; ?></strong><br>
            <span class="small">(ID: <?php echo $id_funcionario; ?>)</span>
        </p>
        
        <form method="POST" action="php/salvaFuncionario.php?opcao=D&id=<?php echo $id_funcionario; ?>" class="d-flex justify-content-center gap-3">
            <a href="Cadastrar_Funcionario.php" class="btn btn-light border px-4 py-2 fw-medium text-secondary">Cancelar</a>
            <button type="submit" class="btn btn-danger px-4 py-2 fw-medium">Sim, Apagar</button>
        </form>
        
    </div>

</body>
</html>