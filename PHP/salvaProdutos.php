<?php
    $opcao = $_GET['opcao'] ?? ''; 
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0; 
    
    // Pegando os dados do formulário de Produtos
    $nome_produto     = $_POST['nProduto'] ?? '';
    $codigo           = $_POST['nCodigo'] ?? '';
    $categoria        = $_POST['nCategoria'] ?? '';
    $preco            = $_POST['nPreco'] ?? 0.00;
    $estoque          = $_POST['nEstoque'] ?? 0;
    $descricao        = $_POST['nDescricao'] ?? '';
    $imagem_url       = $_POST['nImagemUrl'] ?? '';
    
    // Para campos booleanos/checkboxes (1 para ativo/sim, 0 para inativo/não)
    $produto_ativo    = isset($_POST['nAtivo']) ? intval($_POST['nAtivo']) : 1;
    $destaque_cardapio = isset($_POST['nDestaque']) ? intval($_POST['nDestaque']) : 0;

    // Tratamento para garantir que o preço use o ponto como separador decimal no banco de dados
    $preco = str_replace(',', '.', str_replace('.', '', $preco));
    $preco = floatval($preco);

    // montar sql
    if($opcao == 'I'){
        $sql = "INSERT INTO produtos (nome_produto, codigo, categoria, preco, estoque, descricao, imagem_url, produto_ativo, destaque_cardapio)
                VALUES ('$nome_produto', '$codigo', '$categoria', $preco, $estoque, '$descricao', '$imagem_url', $produto_ativo, $destaque_cardapio);";
                
    } elseif ($opcao == 'U') {
        $sql = "UPDATE produtos SET 
                nome_produto = '$nome_produto',
                codigo = '$codigo',
                categoria = '$categoria',
                preco = $preco,
                estoque = $estoque,
                descricao = '$descricao',
                imagem_url = '$imagem_url',
                produto_ativo = $produto_ativo,
                destaque_cardapio = $destaque_cardapio
                WHERE id = $id;";
        
    } elseif($opcao == 'D'){
        $sql = "DELETE FROM produtos WHERE id = $id;";
    }

    include ("conexao.php");
    if(isset($sql) && $sql != "") {
        mysqli_query($conn, $sql);
    }
    mysqli_close($conn);

    // Redireciona de volta para a tela de listagem/cadastro de produtos
    header ('location: ../Cadastrar_Produto.php');
?>