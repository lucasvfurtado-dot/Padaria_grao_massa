<?php
include("conexao.php");

// FUNÇÕES DE FORNECEDORES

function qtdFornecedores() {
    global $conn;
    $sql = "SELECT COUNT(*) as total FROM fornecedores";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['total'];
}

function listaFornecedores() {
    global $conn;
    $sql = "SELECT * FROM fornecedores ORDER BY id DESC LIMIT 5";
    $result = $conn->query($sql);
    $html = '';
    while($row = $result->fetch_assoc()) {
        $html .= '<tr>
            <td class="px-4">' . htmlspecialchars($row['nome_empresa']) . '</td>
            <td>' . htmlspecialchars($row['telefone']) . '</td>
            <td>' . htmlspecialchars($row['categoria']) . '</td>
            <td>' . htmlspecialchars($row['cidade']) . '/' . htmlspecialchars($row['uf']) . '</td>
            <td class="text-end px-4">
                <div class="d-flex gap-2 justify-content-end">
                    <a href="alterarFornecedor.php?id=' . $row['id'] . '" class="btn btn-sm btn-outline-warning" title="Alterar">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 14.66V20a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h5.34"/><polygon points="18 2 22 6 12 16 8 16 8 12 18 2"/></svg>
                    </a>
                    <a href="excluirFornecedor.php?id=' . $row['id'] . '" class="btn btn-sm btn-outline-danger" title="Excluir" onclick="return confirm(\'Tem certeza que deseja excluir este fornecedor?\')">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                    </a>
                </div>
            </td>
        </tr>';
    }
    return $html;
}

function carregaFornecedor($id) {
    global $conn;
    $sql = "SELECT * FROM fornecedores WHERE id = $id";
    $result = $conn->query($sql);
    if($result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return [];
}
?>