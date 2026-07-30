<?php
// ==========================================
// 1. VERIFICAÇÃO DE SESSÃO E CONEXÃO
// ==========================================
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$nome_usuario  = $_SESSION['usuario_nome'] ?? 'Usuário';
$cargo_usuario = $_SESSION['usuario_cargo'] ?? 'Funcionário';

$partes_nome = explode(' ', trim($nome_usuario));
$iniciais    = strtoupper(substr($partes_nome[0], 0, 1));
if (count($partes_nome) > 1) {
    $iniciais .= strtoupper(substr(end($partes_nome), 0, 1));
}

// Conexão com o banco de dados
include("php/conexao.php"); // Certifique-se de que o caminho do seu arquivo de conexão está correto

// 2. BUSCA OS DADOS DO PRODUTO PELO ID
$id_produto = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_produto <= 0) {
    header("Location: Cadastrar_Produto.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM produtos WHERE id = ?");
$stmt->bind_param("i", $id_produto);
$stmt->execute();
$result = $stmt->get_result();
$produto = $result->fetch_assoc();

if (!$produto) {
    header("Location: Cadastrar_Produto.php");
    exit();
}

// 3. TRATAMENTO DO CAMINHO DA IMAGEM
$nome_imagem = trim($produto['imagem_url'] ?? '');
// Remove prefixos antigos para evitar duplication (ex: uploads/uploads/foto.jpg)
$nome_limpo = str_replace(['uploads/', '../uploads/'], '', $nome_imagem);
// Monta o caminho relativo correto para a view
$caminho_imagem = !empty($nome_limpo) ? 'uploads/' . $nome_limpo : '';
?>
<!DOCTYPE html>
<html lang="pt-BR" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grão & Massa - Visualizar Produto</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="CSS/style.css">
    <style>
        .badge-status {
            display: inline-flex;
            align-items: center;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .badge-status.ativo {
            background-color: rgba(34, 197, 94, 0.15);
            color: #22c55e;
            border: 1px solid rgba(34, 197, 94, 0.3);
        }
        .badge-status.inativo {
            background-color: rgba(239, 68, 68, 0.15);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }
    </style>
</head>
<body>

<nav class="sb">
  <div class="sb-brand">
    <div class="sb-icon" style="background: transparent; border: none; padding: 0;">
      <img src="uploads/logo.jpg" alt="Logo Grão & Massa" style="width: 100%; height: 100%; object-fit: contain; border-radius: 6px;">
    </div>
    <div class="sb-name">Grão &amp; Massa<span>Padaria &amp; Café</span></div>
  </div>

  <p class="sb-label">Menu</p>
  <ul class="sb-nav">
    <li><a href="index.php" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>Dashboard</a></li>
    <li><a href="vendas.php" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 001.97-1.67L23 6H6"/></svg>Caixa / Vendas</a></li>
    <li><a href="cadastrar_pedido.php" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>Pedidos</a></li>
    <li><a href="gerenciar_estoque.php" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>Estoque</a></li>
    <li><a href="Relatorios.php" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>Relatórios</a></li>
  </ul>

  <p class="sb-label">Cadastros</p>
  <ul class="sb-nav">
    <li><a href="Cadastrar_Cliente.php" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>Clientes</a></li>
    <li><a href="Cadastrar_Funcionario.php" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>Funcionários</a></li>
    <li><a href="Cadastrar_Fornecedor.php" class="sb-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/></svg>Fornecedores</a></li>
    <li><a href="Cadastrar_Produto.php" class="sb-link on"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>Produtos<span class="sb-dot"></span></a></li>
  </ul>

  <div class="sb-foot">
    <div class="sb-user">
      <div class="sb-av"><?php echo $iniciais; ?></div>
      <div style="flex: 1; min-width: 0;">
        <div class="sb-uname" title="<?php echo htmlspecialchars($nome_usuario); ?>"><?php echo htmlspecialchars($nome_usuario); ?></div>
        <div class="sb-urole"><?php echo htmlspecialchars($cargo_usuario); ?></div>
      </div>
    </div>
  </div>
</nav>

<div class="main">
  <header class="top">
    <div class="top-l">
      <div class="badge-pg">
        <div class="badge-icon"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg></div>
        Produtos
      </div>
      <span class="sep">•</span>
      <span class="date-chip" id="date"></span>
    </div>
  </header>

  <div class="content">
    <main class="dash-main">
      <div class="page-header">
          <a href="Cadastrar_Produto.php" class="btn-back">
              <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
          </a>
          <div>
              <h3 class="page-title">
                  <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                  Visualizar Produto
              </h3>
              <span class="page-desc">Consultando as informações registradas do produto (ID: <?php echo $produto['id']; ?>).</span>
          </div>
      </div>

      <div class="content-card">
          <div class="form-section-title">Detalhes do Produto</div>
          <div class="form-grid">
              
              <div class="fg-4" style="display: flex; flex-direction: column;">
                  <label class="input-label">Imagem do Produto</label>
                  <?php if (!empty($caminho_imagem) && file_exists($caminho_imagem)): ?>
                      <img src="<?php echo htmlspecialchars($caminho_imagem); ?>" alt="Imagem do Produto" style="width: 100%; height: 260px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border);">
                  <?php else: ?>
                      <div style="height: 260px; background: var(--surface, #161b27); border: 1px dashed var(--border, #2a2f3e); border-radius: 8px; display: flex; flex-direction: column; align-items: center; justify-content: center; color: var(--ash, #6b7280);">
                          <svg style="width: 48px; height: 48px; margin-bottom: 12px; opacity: 0.5;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                              <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                              <circle cx="8.5" cy="8.5" r="1.5"></circle>
                              <polyline points="21 15 16 10 5 21"></polyline>
                          </svg>
                          <span style="font-size: 13px; font-weight: 500;">Sem Imagem Disponível</span>
                      </div>
                  <?php endif; ?>
              </div>

              <div class="fg-8">
                  <div class="form-grid">
                      <div class="fg-8">
                          <label class="input-label">Nome do Produto</label>
                          <input type="text" class="input-field" value="<?php echo htmlspecialchars($produto['nome_produto']); ?>" readonly>
                      </div>
                      <div class="fg-4">
                          <label class="input-label">Código / Lote</label>
                          <input type="text" class="input-field" value="<?php echo htmlspecialchars($produto['codigo'] ?? 'N/A'); ?>" readonly>
                      </div>
                      <div class="fg-4 mt-16">
                          <label class="input-label">Categoria</label>
                          <input type="text" class="input-field" value="<?php echo htmlspecialchars($produto['categoria']); ?>" readonly>
                      </div>
                      <div class="fg-4 mt-16">
                          <label class="input-label">Preço (R$)</label>
                          <input type="text" class="input-field" value="<?php echo number_format($produto['preco'], 2, ',', '.'); ?>" readonly>
                      </div>
                      <div class="fg-4 mt-16">
                          <label class="input-label">Estoque</label>
                          <input type="text" class="input-field" value="<?php echo $produto['estoque'] . ' unid.'; ?>" readonly>
                      </div>
                      <div class="fg-12 mt-16">
                          <label class="input-label">Descrição Curta</label>
                          <textarea class="input-field" rows="3" readonly><?php echo htmlspecialchars($produto['descricao'] ?? ''); ?></textarea>
                      </div>
                      <div class="fg-12 mt-16">
                          <?php if ($produto['produto_ativo'] == 1): ?>
                              <span class="badge-status ativo">✓ Produto Ativo</span>
                          <?php else: ?>
                              <span class="badge-status inativo">✕ Produto Inativo</span>
                          <?php endif; ?>
                      </div>
                  </div>
              </div>

          </div>
      </div>
    </main>
  </div>
</div>

<script>
  document.getElementById('date').textContent = new Date().toLocaleDateString('pt-BR',{weekday:'long',day:'numeric',month:'long'}).replace(/^\w/,c=>c.toUpperCase());
</script>
</body>
</html>