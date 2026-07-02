<?php

function nomeFuncionario($id){
    $nome = "";
    $sql = "SELECT nome_completo FROM funcionarios WHERE id = $id;";
    
    include("conexao.php");
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);

    if(mysqli_num_rows($result) > 0){
        foreach($result as $coluna){
            $nome = $coluna['nome_completo'];
        }
    }
    return $nome;
}

function qtdFuncionarios(){
    $qtd = 0;
    $sql = "SELECT COUNT(*) as qtd FROM funcionarios;";
    
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

function listaFuncionarios(){
    $html = "";
    $sql = "SELECT * FROM funcionarios ORDER BY id DESC"; 
    
    include("conexao.php");
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);

    if(mysqli_num_rows($result) > 0){
        foreach($result as $coluna){
            $html .= "<tr>
                        <td>".$coluna['nome_completo']."</td>
                        <td>".$coluna['cargo']."</td>
                        <td>".$coluna['telefone_whatsapp']."</td>
                        <td class='text-end'>
                            <div style='display: flex; gap: 8px; justify-content: flex-end; align-items: center;'>
                                
                                <a href='visualizarFuncionario.php?id=".$coluna['id']."' class='btn-action btn-view' title='Visualizar'>
                                    <svg fill='none' stroke='currentColor' stroke-width='2' viewBox='0 0 24 24'>
                                        <path d='M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z'></path>
                                        <circle cx='12' cy='12' r='3'></circle>
                                    </svg>
                                </a>
                                
                                <a href='alterar_funcionario.php?id=".$coluna['id']."' class='btn-action btn-edit' title='Editar'>
                                    <svg fill='none' stroke='currentColor' stroke-width='2' viewBox='0 0 24 24'>
                                        <path d='M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7'></path>
                                        <path d='M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z'></path>
                                    </svg>
                                </a>
                                
                                <a href='apagar_funcionario.php?id=".$coluna['id']."' class='btn-action btn-delete' title='Apagar' onclick=\"return confirm('Tem certeza que deseja excluir?');\">
                                    <svg fill='none' stroke='currentColor' stroke-width='2' viewBox='0 0 24 24'>
                                        <polyline points='3 6 5 6 21 6'></polyline>
                                        <path d='M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2'></path>
                                        <line x1='10' y1='11' x2='10' y2='17'></line>
                                        <line x1='14' y1='11' x2='14' y2='17'></line>
                                    </svg>
                                </a>
                            </div>
                        </td>
                      </tr>";
        }
    } else {
        $html .= "<tr><td colspan='4' class='text-center py-4' style='color: var(--ash);'>Nenhum funcionário cadastrado ainda.</td></tr>";
    }

    return $html;
}

function carregaFuncionario($id){
    $sql = "SELECT * FROM funcionarios WHERE id = $id;";

    include("conexao.php");
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);

    return mysqli_fetch_array($result);
}
?>