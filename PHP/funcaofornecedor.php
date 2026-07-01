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
    include("conexao.php");
    $sql = "SELECT * FROM fornecedores ORDER BY id DESC LIMIT 10";
    $result = $conn->query($sql);
    
    $html = '';
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            // Formata o telefone
            $telefone = $row['telefone'];
            if (strlen($telefone) == 11) {
                $telefone = '(' . substr($telefone, 0, 2) . ') ' . substr($telefone, 2, 5) . '-' . substr($telefone, 7, 4);
            } elseif (strlen($telefone) == 10) {
                $telefone = '(' . substr($telefone, 0, 2) . ') ' . substr($telefone, 2, 4) . '-' . substr($telefone, 6, 4);
            }
            
            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($row['nome_empresa']) . '</td>';
            $html .= '<td>' . htmlspecialchars($telefone) . '</td>';
            $html .= '<td>' . htmlspecialchars($row['categoria']) . '</td>';
            $html .= '<td>' . htmlspecialchars($row['cidade']) . '/' . htmlspecialchars($row['uf']) . '</td>';
            $html .= '<td class="text-end" style="display: flex; gap: 8px; justify-content: flex-end; align-items: center; padding: 8px 12px;">
                        <a href="visualizarFornecedor.php?id=' . $row['id'] . '" class="btn-action btn-view" title="Visualizar">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:18px;height:18px;"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </a>
                        <a href="alterarFornecedor.php?id=' . $row['id'] . '" class="btn-action btn-edit" title="Editar">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:18px;height:18px;"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        </a>
                        <a href="excluirFornecedor.php?id=' . $row['id'] . '" class="btn-action btn-delete" title="Excluir">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:18px;height:18px;"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                        </a>
                      </td>';
            $html .= '</tr>';
        }
    } else {
        $html .= '<tr><td colspan="5" style="text-align: center; padding: 20px; color: #6c757d;">Nenhum fornecedor cadastrado.</td></tr>';
    }
    return $html;
}

// CARREGAR UM FORNECEDOR ESPECÍFICO
function carregaFornecedor($id) {
    include("conexao.php");
    $id = intval($id);
    $sql = "SELECT * FROM fornecedores WHERE id = $id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return null;
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