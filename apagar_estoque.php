<?php
include("php/conexao.php");

// Pega o ID que veio da URL
$id_produto = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Busca apenas o nome do produto no banco de dados para mostrar na tela
$sql = "SELECT nome_produto FROM produtos WHERE id = $id_produto";
$resultado = mysqli_query($conn, $sql);
$produto = mysqli_fetch_assoc($resultado);

// Se por algum motivo o produto não existir mais, ele bota esse nome padrão
$nome_produto = $produto ? htmlspecialchars($produto['nome_produto']) : 'Produto Desconhecido';
?>
<!DOCTYPE html>
<html lang="pt-BR" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apagar do Estoque - Grão & Massa</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=DM+Serif+Display&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>

<nav class="sb">
  <div class="sb-brand">
    <div class="sb-icon"><svg fill="none" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
    <div class="sb-name">Grão &amp; Massa<span>Padaria &amp; Café</span></div>
  </div>

  <p class="sb-label">Menu</p>
  <ul class="sb-nav">
    <li><a href="index.php" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>Dashboard</a></li>
    <li><a href="vendas.php" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 001.97-1.67L23 6H6"/></svg>Caixa / Vendas</a></li>
    <li><a href="cadastrar_pedido.php" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>Pedidos</a></li>
    <li><a href="estoque.php" class="sb-link on"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>Estoque<span class="sb-dot"></span></a></li>
    <li><a href="Relatorios.php" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>Relatórios</a></li>
  </ul>
</nav>

<div class="main">
  <header class="top">
    <div class="top-l">
      <div class="badge-pg" style="background: rgba(220, 38, 38, 0.1); color: #dc2626;">
        <div class="badge-icon" style="background: #dc2626;"><svg fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg></div>
        Excluir Produto
      </div>
      <span class="sep">•</span>
      <span class="date-chip" id="date"></span>
    </div>
  </header>

  <div class="content" style="padding: 24px; overflow-y: auto;">
      <main style="max-width: 600px; margin: 60px auto;">
          
          <div class="content-card" style="padding: 40px; text-align: center; border: 1px solid var(--border); border-radius: 16px; background: var(--white); box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
              
              <div style="width: 64px; height: 64px; border-radius: 50%; background: rgba(220, 38, 38, 0.1); color: #dc2626; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                  <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 32px; height: 32px;">
                      <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                      <line x1="12" y1="9" x2="12" y2="13"/>
                      <line x1="12" y1="17" x2="12.01" y2="17"/>
                  </svg>
              </div>

              <h2 style="color: var(--ink); margin-bottom: 12px; font-size: 22px;">Confirmar Exclusão</h2>
              
              <p style="color: var(--ash); margin-bottom: 32px; font-size: 15px; line-height: 1.6;">
                  Você está prestes a excluir o produto <br>
                  <strong style="color: var(--ink); font-size: 18px;"><?php echo $nome_produto; ?></strong> <br>
                  permanentemente do seu sistema. Esta ação não pode ser desfeita.
              </p>

              <div style="display: flex; gap: 16px; justify-content: center;">
                  <a href="estoque.php" style="text-decoration: none; padding: 12px 24px; border-radius: 8px; font-weight: 600; background: var(--surface); color: var(--ink); border: 1px solid var(--border); transition: 0.2s;">
                      Cancelar
                  </a>
                  <button onclick="confirmarExclusao(<?php echo $id_produto; ?>)" style="padding: 12px 24px; border-radius: 8px; font-weight: 600; background: #dc2626; color: white; border: none; cursor: pointer; transition: 0.2s;">
                      Sim, Apagar Produto
                  </button>
              </div>
              
          </div>

      </main>
  </div>
</div>

<script>
  // Data atual no topo
  document.getElementById('date').textContent = new Date().toLocaleDateString('pt-BR',{weekday:'long',day:'numeric',month:'long'}).replace(/^\w/,c=>c.toUpperCase());

  // Tema Dark Mode
  (()=>{ 
    const s = localStorage.getItem('theme'); 
    if(s === 'dark' || (!s && window.matchMedia('(prefers-color-scheme: dark)').matches)) document.documentElement.setAttribute('data-theme','dark'); 
  })();

  // --- A MÁGICA DE APAGAR ---
  function confirmarExclusao(id) {
      // Faz o botão mostrar "Apagando..." e desativa ele para evitar 2 cliques
      event.target.textContent = "Apagando...";
      event.target.disabled = true;

      // Conversa com o SEU MESMO arquivo acao_estoque.php!
      fetch('php/acao_estoque.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ acao: 'excluir', id: id })
      })
      .then(res => res.json())
      .then(data => {
          if(data.sucesso) {
              // Se o banco apagou, manda o usuário de volta pra tela de estoque
              window.location.href = 'estoque.php';
          } else {
              alert("Erro ao excluir: " + data.mensagem);
              event.target.textContent = "Sim, Apagar Produto";
              event.target.disabled = false;
          }
      })
      .catch(err => {
          console.error(err);
          alert("Ocorreu um erro na comunicação com o servidor.");
      });
  }
</script>
</body>
</html>