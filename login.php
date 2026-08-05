<?php
session_start();

// Isso garante que a tela de login sempre apareça
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    session_unset();
    session_destroy();
    session_start(); // Reinicia uma sessão limpa para o novo login
}

$host = 'localhost';
$db   = 'padaria_grao_massa'; 
$user = 'root';               
$pass = '';                   
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Erro de conexão com o banco de dados: " . $e->getMessage());
}


//PROCESSAMENTO DO FORMULÁRIO DE LOGIN 

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cpf_digitado = trim($_POST['cpf'] ?? '');
    
    // Cria uma versão limpa do CPF (só números)
    $cpf_limpo = preg_replace('/[^0-9]/', '', $cpf_digitado);
    $senha = trim($_POST['senha'] ?? '');

    if (empty($cpf_digitado) || empty($senha)) {
        $erro = 'Por favor, preencha todos os campos.';
    } else {
        $stmt = $pdo->prepare("SELECT id, nome_completo, senha, cargo FROM funcionarios WHERE cpf = :cpf_digitado OR cpf = :cpf_limpo LIMIT 1");
        $stmt->execute([
            'cpf_digitado' => $cpf_digitado,
            'cpf_limpo' => $cpf_limpo
        ]);
        $funcionario = $stmt->fetch();

        // Verifica a senha rigorosamente em MD5
        if ($funcionario && md5($senha) === $funcionario['senha']) {
            
            $_SESSION['usuario_id'] = $funcionario['id'];
            $_SESSION['usuario_nome'] = $funcionario['nome_completo'];
            $_SESSION['usuario_cargo'] = $funcionario['cargo'];
            
            // Somente após validar a senha correta ele vai para o index
            header("Location: index.php");
            exit();
        } else {
            $erro = 'CPF ou senha incorretos. Tente novamente.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grão & Massa — Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=DM+Serif+Display&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="CSS/login.css?v=<?php echo time(); ?>">
</head>
<body class="login-body">

    <div class="login-box">
        <div class="theme-toggle-login">
            <button class="theme-toggle-btn light-btn" onclick="toggleTheme('light')" title="Ativar Tema Claro">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
                </svg>
            </button>
            <button class="theme-toggle-btn dark-btn" onclick="toggleTheme('dark')" title="Ativar Tema Escuro">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
                </svg>
            </button>
        </div>

        <div class="login-logo">
            <img src="uploads/logo.jpg" alt="Logo Grão & Massa" class="logo-img">
            <h1>Grão & Massa</h1>
            <span>Acesso Restrito</span>
        </div>

        <?php if (!empty($erro)): ?>
            <div class="alert-box">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 18px; height: 18px; flex-shrink: 0;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <?php echo $erro; ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST" autocomplete="off">
            
            <div class="form-group">
                <label for="cpf" class="input-label">CPF DO FUNCIONÁRIO</label>
                <div class="input-wrapper">
                    <input type="text" id="cpf" name="cpf" class="input-field" placeholder="000.000.000-00" maxlength="14" required autofocus>
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
                    </svg>
                </div>
            </div>

            <div class="form-group">
                <label for="senha" class="input-label">SENHA DE ACESSO</label>
                <div class="input-wrapper">
                    <input type="password" id="senha" name="senha" class="input-field" placeholder="••••••••" required>
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/>
                    </svg>
                </div>
            </div>

            <button type="submit" class="btn-primary">Acessar Sistema</button>
        </form>
    </div>

    <script>
        function toggleTheme(theme) {
            document.documentElement.setAttribute('data-theme', theme);
            localStorage.setItem('gm-theme', theme);
        }

        (() => { 
            const savedTheme = localStorage.getItem('gm-theme'); 
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            let theme = 'light';
            if (savedTheme) {
                theme = savedTheme;
            } else if (prefersDark) {
                theme = 'dark';
            }
            toggleTheme(theme);
        })();

        document.getElementById('cpf').addEventListener('input', function (e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 3) value = value.replace(/^(\d{3})(\d)/, '$1.$2');
            if (value.length > 6) value = value.replace(/^(\d{3})\.(\d{3})(\d)/, '$1.$2.$3');
            if (value.length > 9) value = value.replace(/^(\d{3})\.(\d{3})\.(\d{3})(\d)/, '$1.$2.$3-$4');
            e.target.value = value;
        });
    </script>
</body>
</html>
