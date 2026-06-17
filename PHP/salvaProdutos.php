<?php
include("conexao.php");

    $opcao = $_GET['opcao'] ?? ''; 
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0; 
    
    // Pegando os dados do formulário
    $nome_produto     = $_POST['nProduto'] ?? '';
    $codigo           = $_POST['nCodigo'] ?? '';
    $categoria        = $_POST['nCategoria'] ?? '';
    $preco            = $_POST['nPreco'] ?? 0.00;
    $estoque          = $_POST['nEstoque'] ?? 0;
    $descricao        = $_POST['nDescricao'] ?? '';
    
    // Checkboxes
    $produto_ativo    = isset($_POST['nAtivo']) ? intval($_POST['nAtivo']) : 1;
    $destaque_cardapio = isset($_POST['nDestaque']) ? intval($_POST['nDestaque']) : 0;

    // Formata o preço para o banco de dados (o JS já envia com ponto, ex: 15.50)
    // Apenas garantimos que se vier uma vírgula perdida, ela vira ponto. Removemos o código antigo que apagava os pontos.
    $preco = str_replace(',', '.', $preco);
    $preco = floatval($preco);

    // --- LÓGICA DE UPLOAD DA IMAGEM ---
    $imagem_url = ''; 
    if(isset($_FILES['nImagem']) && $_FILES['nImagem']['error'] === UPLOAD_ERR_OK) {
        $extensao = strtolower(pathinfo($_FILES['nImagem']['name'], PATHINFO_EXTENSION));
        $nomeArquivo = uniqid("prod_") . "." . $extensao;
        
        // Pasta onde as fotos serão guardadas
        $pastaDestino = "../uploads/"; 
        if (!is_dir($pastaDestino)) {
            mkdir($pastaDestino, 0777, true); // Cria a pasta se não existir
        }

        if(move_uploaded_file($_FILES['nImagem']['tmp_name'], $pastaDestino . $nomeArquivo)){
            $imagem_url = "uploads/" . $nomeArquivo; 
        }
    } else {
        $imagem_url = $_POST['nImagemUrl'] ?? ''; // Caso não envie, mantém a antiga (útil no update)
    }
    // --- FIM DO UPLOAD ---

    // Montar SQL
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

    if(isset($sql) && $sql != "") {
        mysqli_query($conn, $sql);
    }
    mysqli_close($conn);

    // Volta para a tela de produtos
    header ('location: ../Cadastrar_Produto.php');
?>