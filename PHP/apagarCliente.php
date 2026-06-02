<?php
include("php/funcoes.php")
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <title>Apagar Cliente</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="CDN/bootstrap-5.3.8-dist/bootstrap-5.3.8-dist/css/bootstrap.min.css">
    </head>
    <body class="bg-light d-flex align-items-center justify-content-center vh-100"> 
        <div class="text-center p-5 bg-white shadow-sm rounded-4">
            <h3 class="mb-4"> <a href="novoCliente.php" class="btn btn-sm btn-outline-secondary">Voltar</a></h3>
            
            <h2 class="text-danger">Deseja realmente apagar o cliente?</h2>
            <h4 class="mb-4 text-dark"><?php echo nomeCliente($_GET['id']);?> (ID = <?php echo $_GET['id'];?>)</h4>    
            
            <form method="POST" action="php/salvaCliente.php?opcao=D&id=<?php echo $_GET['id'];?>">
                <input type="submit" value="Sim, Apagar" class="btn btn-danger btn-lg px-5">
            </form>    
        </div>
    </body>
</html>