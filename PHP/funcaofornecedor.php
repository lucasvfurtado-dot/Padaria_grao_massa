<?php

// FUNÇÃO PARA FORMATAR CNPJ (aceita qualquer tamanho)
function formatarCNPJ($cnpj) {
    $cnpj = preg_replace('/[^0-9]/', '', $cnpj);
    
    // Se tiver 14 dígitos, aplica máscara completa
    if (strlen($cnpj) == 14) {
        return substr($cnpj, 0, 2) . '.' . 
               substr($cnpj, 2, 3) . '.' . 
               substr($cnpj, 5, 3) . '/' . 
               substr($cnpj, 8, 4) . '-' . 
               substr($cnpj, 12, 2);
    }
    
    // Se tiver menos de 14 dígitos, retorna só os números
    return $cnpj;
}

// FUNÇÃO PARA FORMATAR TELEFONE
function formatarTelefone($telefone) {
    $telefone = preg_replace('/[^0-9]/', '', $telefone);
    if (strlen($telefone) == 11) {
        return '(' . substr($telefone, 0, 2) . ') ' . 
               substr($telefone, 2, 5) . '-' . 
               substr($telefone, 7, 4);
    } elseif (strlen($telefone) == 10) {
        return '(' . substr($telefone, 0, 2) . ') ' . 
               substr($telefone, 2, 4) . '-' . 
               substr($telefone, 6, 4);
    }
    return $telefone;
}

// FUNÇÃO PARA FORMATAR CEP
function formatarCEP($cep) {
    $cep = preg_replace('/[^0-9]/', '', $cep);
    if (strlen($cep) == 8) {
        return substr($cep, 0, 5) . '-' . substr($cep, 5, 3);
    }
    return $cep;
}

// LISTAR FORNECEDORES
function listaFornecedores() {
    $html = "";
    $sql = "SELECT * FROM fornecedores ORDER BY id DESC LIMIT 10;"; 
    
    include("conexao.php");
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);

    if(mysqli_num_rows($result) > 0){
        foreach($result as $coluna){
            $telefoneFormatado = formatarTelefone($coluna['telefone']);
            
            // HTML da linha da tabela limpo, usando as variáveis nativas do CSS
            $html .= "<tr>
                        <td style='font-weight: 600; color: var(--ink);'>".$coluna['nome_empresa']."</td>
                        <td style='color: var(--ash);'>".$telefoneFormatado."</td>
                        <td style='color: var(--ash);'>".$coluna['categoria']."</td>
                        <td style='color: var(--ash);'>".$coluna['cidade']."/".$coluna['uf']."</td>
                        <td class='text-end'>
                            <div style='display: flex; justify-content: flex-end; gap: 8px;'>
                                
                                <a href='visualizarFornecedor.php?id=".$coluna['id']."' class='btn-action btn-view' title='Visualizar'>
                                    <svg fill='none' stroke='currentColor' stroke-width='2' viewBox='0 0 24 24'>
                                        <path d='M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z'></path>
                                        <circle cx='12' cy='12' r='3'></circle>
                                    </svg>
                                </a>
                                
                                <a href='alterarFornecedor.php?id=".$coluna['id']."' class='btn-action btn-edit' title='Editar'>
                                    <svg fill='none' stroke='currentColor' stroke-width='2' viewBox='0 0 24 24'>
                                        <path d='M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7'></path>
                                        <path d='M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z'></path>
                                    </svg>
                                </a>
                                
                                <a href='excluirFornecedor.php?id=".$coluna['id']."' class='btn-action btn-delete' title='Apagar' onclick=\"return confirm('Tem certeza que deseja excluir este fornecedor?')\">
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
        $html .= "<tr><td colspan='5' style='text-align: center; color: var(--ash); padding: 32px 0;'>Nenhum fornecedor cadastrado ainda.</td></tr>";
    }

    return $html;
}

// CARREGAR UM FORNECEDOR ESPECÍFICO
function carregaFornecedor($id) {
    $sql = "SELECT * FROM fornecedores WHERE id = $id;";

    include("conexao.php");
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);

    return mysqli_fetch_array($result);
}

// QUANTIDADE DE FORNECEDORES
function qtdFornecedores() {
    $qtd = 0;
    $sql = "SELECT COUNT(*) as qtd FROM fornecedores;";
    
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
?>