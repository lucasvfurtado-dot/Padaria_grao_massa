<?php
include("conexao.php");

function formatarCNPJ($cnpj) {
    // Se for número, converte para string
    $cnpj = (string) $cnpj;
    
    // Se tiver menos que 14 dígitos, completa com zeros à esquerda
    $cnpj = str_pad($cnpj, 14, '0', STR_PAD_LEFT);
    
    // Aplica a máscara
    return substr($cnpj, 0, 2) . '.' . 
           substr($cnpj, 2, 3) . '.' . 
           substr($cnpj, 5, 3) . '/' . 
           substr($cnpj, 8, 4) . '-' . 
           substr($cnpj, 12, 2);
}

// FUNÇÃO PARA FORMATAR TELEFONE COM MÁSCARA
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

// FUNÇÃO PARA FORMATAR CEP COM MÁSCARA
function formatarCEP($cep) {
    $cep = preg_replace('/[^0-9]/', '', $cep);
    if (strlen($cep) == 8) {
        return substr($cep, 0, 5) . '-' . substr($cep, 5, 3);
    }
    return $cep;
}

// LISTAR FORNECEDORES
function listaFornecedores() {
    global $conn;
    $sql = "SELECT * FROM fornecedores ORDER BY id DESC LIMIT 10";
    $result = $conn->query($sql);
    
    $html = '';
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $cnpj_formatado = formatarCNPJ($row['cnpj']);
            $telefone_formatado = formatarTelefone($row['telefone']);
            
            $html .= '<tr>';
            $html .= '<td class="px-4 fw-semibold">' . $row['nome_empresa'] . '</td>';
            $html .= '<td>' . $telefone_formatado . '</td>';
            $html .= '<td>' . $row['categoria'] . '</td>';
            $html .= '<td>' . $row['cidade'] . '/' . $row['uf'] . '</td>';
            $html .= '<td class="text-end px-4">
                        <div class="d-flex gap-1 justify-content-end">
                            <a href="visualizarFornecedor.php?id=' . $row['id'] . '" class="btn btn-sm btn-outline-secondary" title="Visualizar">
                                <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            </a>
                            <a href="alterarFornecedor.php?id=' . $row['id'] . '" class="btn btn-sm btn-outline-primary" title="Editar">
                                <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </a>
                            <a href="excluirFornecedor.php?id=' . $row['id'] . '" class="btn btn-sm btn-outline-danger" title="Excluir" onclick="return confirm(\'Tem certeza que deseja excluir este fornecedor?\')">
                                <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                            </a>
                        </div>
                    </td>';
            $html .= '</tr>';
        }
    } else {
        $html .= '<tr><td colspan="5" class="text-center py-3 text-secondary">Nenhum fornecedor cadastrado.</td></tr>';
    }
    return $html;
}

// CARREGAR UM FORNECEDOR ESPECÍFICO
function carregaFornecedor($id) {
    global $conn;
    $sql = "SELECT * FROM fornecedores WHERE id = $id";
    $result = $conn->query($sql);
    return $result->fetch_assoc();
}

// QUANTIDADE DE FORNECEDORES
function qtdFornecedores() {
    global $conn;
    $sql = "SELECT COUNT(*) as total FROM fornecedores";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['total'];
}
?>