<?php
function qtdFornecedores() {
    include("conexao.php");
    $sql = "SELECT COUNT(*) as total FROM fornecedores";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['total'];
}

function listaFornecedores() {
    include("conexao.php");
    $sql = "SELECT * FROM fornecedores ORDER BY id DESC LIMIT 5";
    $result = $conn->query($sql);
    $html = "";
    while($row = $result->fetch_assoc()) {
        $html .= "<tr>
                    <td class='px-4 fw-semibold'>{$row['nome_empresa']}</td>
                    <td>{$row['telefone']}</td>
                    <td>{$row['categoria']}</td>
                    <td>{$row['cidade']}/{$row['uf']}</td>
                    <td class='text-end px-4'>
                        <a href='alterarFornecedor.php?id={$row['id']}' class='btn btn-sm btn-outline-primary me-1' title='Editar'>
                            ✏️
                        </a>
                        <a href='excluirfornecedor.php?id={$row['id']}' class='btn btn-sm btn-outline-danger' title='Excluir' onclick='return confirm(\"Tem certeza que deseja excluir o fornecedor {$row['nome_empresa']}?\")'>
                            🗑️
                        </a>
                     </td>
                    </tr>";
    }
    return $html;
}
?>