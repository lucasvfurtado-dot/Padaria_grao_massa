<?php
include("php/conexao.php");

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $sql = "DELETE FROM fornecedores WHERE id = $id";
    
    if(mysqli_query($conn, $sql)) {
        header("Location: Cadastrar_Fornecedor.php?msg=excluido");
        exit();
    } else {
        echo "Erro ao excluir: " . mysqli_error($conn);
    }
} else {
    header("Location: Cadastrar_Fornecedor.php");
    exit();
}
?>