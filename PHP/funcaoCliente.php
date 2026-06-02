<?php
function nomeCliente($id){
    $nome = "";
    $sql = "SELECT nome_razao_social FROM clientes WHERE id = $id;";
    
    include("conexao.php");
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);

    if(mysqli_num_rows($result) > 0){
        foreach($result as $coluna){
            $nome = $coluna['nome_razao_social'];
        }
    }
    return $nome;
}

function qtdClientes(){
    $qtd = 0;
    $sql = "SELECT COUNT(*) as qtd FROM clientes;";
    
    include("conexao.php");
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);

    if(mysqli_num_rows($result) > 0){
        foreach($result as $coluna){
            $qtd = $coluna['qtd'];
        }
    }
    return $qtd;
}

function listaClientes(){
    $html = "";
    $sql = "SELECT * FROM clientes ORDER BY id DESC"; // Lista os mais recentes primeiro
    
    include("conexao.php");
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);

    if(mysqli_num_rows($result) > 0){
        foreach($result as $coluna){
            // Montando o HTML igual ao seu template Bootstrap
            $html .= "<tr>
                        <td class='px-4 fw-medium'>".$coluna['nome_razao_social']."</td>
                        <td>".$coluna['telefone_whatsapp']."</td>
                        <td>".$coluna['cidade']."/".$coluna['uf']."</td>
                        <td class='text-end px-4'>
                            <a href='apagarCliente.php?id=".$coluna['id']."' class='btn btn-sm btn-danger text-white' title='Apagar'>Apagar</a>
                        </td>
                      </tr>";
        }
    } else {
        $html .= "<tr><td colspan='4' class='text-center'>Nenhum cliente cadastrado.</td></tr>";
    }

    return $html;
}
?>