<?php
include("conexao.php");

// RECEBIMENTO DOS DADOS DO FORMULÁRIO
$opcao = $_GET['opcao'] ?? ''; 
$id    = isset($_GET['id']) ? intval($_GET['id']) : 0; 

$nome_produto = trim($_POST['nProduto'] ?? '');
$codigo       = trim($_POST['nCodigo'] ?? '');
$categoria    = trim($_POST['nCategoria'] ?? '');
$preco_raw    = $_POST['nPreco'] ?? '0';
$estoque      = intval($_POST['nEstoque'] ?? 0);
$descricao    = trim($_POST['nDescricao'] ?? '');

// Tratamento dos checkboxes (0 se desmarcado, 1 se marcado)
$produto_ativo     = isset($_POST['nAtivo']) ? 1 : 0;
$destaque_cardapio = isset($_POST['nDestaque']) ? 1 : 0;

// Tratamento do preço (converte "5,50" para 5.50)
$preco = str_replace(',', '.', $preco_raw);
$preco = floatval($preco);

// 2. TRATAMENTO E UPLOAD DA IMAGEM
$imagem_url = ''; 

if (isset($_FILES['nImagem']) && $_FILES['nImagem']['error'] === UPLOAD_ERR_OK) {
    $extensao    = strtolower(pathinfo($_FILES['nImagem']['name'], PATHINFO_EXTENSION));
    $nomeArquivo = uniqid("prod_") . "." . $extensao;
    
    // Caminho físico onde a imagem será gravada
    $pastaDestino = "../uploads/"; 
    if (!is_dir($pastaDestino)) {
        mkdir($pastaDestino, 0755, true); 
    }

    if (move_uploaded_file($_FILES['nImagem']['tmp_name'], $pastaDestino . $nomeArquivo)) {
        $imagem_url = $nomeArquivo; 
    }
} else {
    // Se não enviou imagem nova (no UPDATE)
    $imagem_antiga = $_POST['nImagemUrl'] ?? '';
    // Remove qualquer "uploads/" antigo que possa vir do campo oculto
    $imagem_url = str_replace(['uploads/', '../uploads/'], '', $imagem_antiga);
}

// 3. EXECUÇÃO DAS OPERAÇÕES (INSERIR, ATUALIZAR, EXCLUIR)
$msg = '';

if ($opcao == 'I') {
    $stmt = mysqli_prepare($conn, "INSERT INTO produtos (nome_produto, codigo, categoria, preco, estoque, descricao, imagem_url, produto_ativo, destaque_cardapio) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sssdis sii", $nome_produto, $codigo, $categoria, $preco, $estoque, $descricao, $imagem_url, $produto_ativo, $destaque_cardapio);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    $msg = 'sucesso';

} elseif ($opcao == 'U' && $id > 0) {
    $stmt = mysqli_prepare($conn, "UPDATE produtos SET nome_produto = ?, codigo = ?, categoria = ?, preco = ?, estoque = ?, descricao = ?, imagem_url = ?, produto_ativo = ?, destaque_cardapio = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "sssdis siii", $nome_produto, $codigo, $categoria, $preco, $estoque, $descricao, $imagem_url, $produto_ativo, $destaque_cardapio, $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    $msg = 'atualizado';

} elseif ($opcao == 'D' && $id > 0) {
    $stmt = mysqli_prepare($conn, "DELETE FROM produtos WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    $msg = 'excluido';
}

mysqli_close($conn);

// 4. REDIRECIONAMENTO COM MENSAGEM
$url_redirecionamento = '../Cadastrar_Produto.php';
if ($msg !== '') {
    $url_redirecionamento .= '?msg=' . $msg;
}

header("Location: " . $url_redirecionamento);
exit();
?>