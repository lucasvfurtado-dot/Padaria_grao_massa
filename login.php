<?php
// =========================================================================
// 1. INICIALIZAÇÃO DA SESSÃO E CONEXÃO COM O BANCO DE DADOS
// =========================================================================
session_start();

if (isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
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

// =========================================================================
// 2. PROCESSAMENTO DO FORMULÁRIO DE LOGIN (APENAS CPF)
// =========================================================================
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cpf = trim($_POST['cpf'] ?? '');
    $senha = trim($_POST['senha'] ?? '');

    if (empty($cpf) || empty($senha)) {
        $erro = 'Por favor, preencha todos os campos.';
    } else {
        $stmt = $pdo->prepare("SELECT id, nome_completo, senha, cargo FROM funcionarios WHERE cpf = :cpf LIMIT 1");
        $stmt->execute(['cpf' => $cpf]);
        $funcionario = $stmt->fetch();

        // AQUI ESTÁ A CORREÇÃO PARA O MD5!
        if ($funcionario && md5($senha) === $funcionario['senha']) {
            $_SESSION['usuario_id'] = $funcionario['id'];
            $_SESSION['usuario_nome'] = $funcionario['nome_completo'];
            $_SESSION['usuario_cargo'] = $funcionario['cargo'];
            
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
    
    <style>
        /* VARIÁVEIS ORIGINAIS E AJUSTES DE TEMA CLARO */
        :root {
           --cr: #b00000; --cr2: #8a0000; --cr-bg: rgba(176,0,0,.08);
           --night: #111318; --ink: #212529; --ash: #6c757d;
           --border: #e5e8ed; --surface: #f7f8fa; --white: #fff;
           --r: 10px; --r2: 16px; --t: .15s ease;
           
           /* Novas variáveis para o Glassmorphism claro */
           --glass-bg: rgba(255, 255, 255, 0.7);
           --glass-border: rgba(255, 255, 255, 0.15);
           --text-on-glass: var(--ink);
           --label-on-glass: var(--ash);
           --input-bg-glass: rgba(255, 255, 255, 0.5);
           --shadow-glass: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
           --alert-bg: rgba(220, 53, 69, 0.1);
           --alert-text: #842029;
           --alert-border: rgba(220, 53, 69, 0.2);
           --input-focused: var(--white);
           --icon-color: var(--ash);
        }
        
        /* VARIÁVEIS PARA TEMA ESCURO DO LOGIN */
        [data-theme=dark] {
           --ink:#e8eaf0; --ash:#8b93a0; --border:#2a2f3e;
           --surface:#161b27; --white:#1e2335; --night:#0d1018;
           --cr-bg:rgba(176,0,0,.15);
           
           /* Ajustes para Glassmorphism escuro */
           --glass-bg: rgba(30, 35, 53, 0.6);
           --glass-border: rgba(255, 255, 255, 0.08);
           --text-on-glass: #fff;
           --label-on-glass: var(--ash);
           --input-bg-glass: rgba(0, 0, 0, 0.25);
           --shadow-glass: 0 25px 50px -12px rgba(0, 0, 0, 0.6);
           --alert-bg: rgba(220, 53, 69, 0.15);
           --alert-text: #ff9ca6;
           --alert-border: rgba(220, 53, 69, 0.3);
           --input-focused: rgba(0, 0, 0, 0.4);
           --icon-color: rgba(255, 255, 255, 0.3);
        }
        
        * { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { height: 100%; font-family: Inter, system-ui, sans-serif; }
        
        /* FUNDO COM IMAGEM DE PADARIA E OVERLAY AJUSTÁVEL */
        body.login-body { 
            /* Imagem desfocada de padaria artesanal via Unsplash */
            background: linear-gradient(rgba(247, 248, 250, 0.8), rgba(247, 248, 250, 0.95)), 
                        url('https://images.unsplash.com/photo-1598373182133-52452f7691ef?q=80&w=1920&auto=format&fit=crop') center/cover no-repeat;
            display: flex; 
            align-items: center; 
            justify-content: center; 
            color: var(--ink);
            transition: background var(--t);
        }
        
        /* Overlays para os temas */
        [data-theme=dark] body.login-body {
            background: linear-gradient(rgba(13, 16, 24, 0.85), rgba(13, 16, 24, 0.95)), 
                        url('https://images.unsplash.com/photo-1598373182133-52452f7691ef?q=80&w=1920&auto=format&fit=crop') center/cover no-repeat;
        }
        
        /* CAIXA COM GLASSMORPHISM (EFEITO VIDRO REFINADO) */
        .login-box {
            width: 100%;
            max-width: 420px;
            background: var(--glass-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 48px 36px;
            box-shadow: var(--shadow-glass);
            animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
        }

        @keyframes slideUpFade {
            0% { opacity: 0; transform: translateY(30px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        /* Botão alternador de tema na caixa */
        .theme-toggle-login {
            position: absolute;
            top: 24px;
            right: 24px;
            display: flex;
            gap: 6px;
            background: var(--input-bg-glass);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 4px;
        }
        .theme-toggle-btn {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--ash);
            transition: all var(--t);
            border: none;
            background: none;
        }
        .theme-toggle-btn svg {
            width: 16px;
            height: 16px;
            stroke: currentColor;
        }
        [data-theme=light] .theme-toggle-btn.light-btn { background: var(--white); color: var(--cr); box-shadow: 0 2px 6px rgba(176,0,0,0.15); }
        [data-theme=dark] .theme-toggle-btn.dark-btn { background: var(--cr); color: #fff; box-shadow: 0 2px 6px rgba(176,0,0,0.4); }

        .login-logo {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            margin-bottom: 36px;
        }

        .login-logo .sb-icon {
            width: 56px;
            height: 56px;
            background: var(--cr);
            border-radius: 14px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            box-shadow: 0 8px 24px rgba(176, 0, 0, 0.4);
        }

        .login-logo .sb-icon svg {
            width: 28px;
            height: 28px;
            stroke: currentColor;
        }

        .login-logo h1 {
            font-family: 'DM Serif Display', serif;
            font-size: 32px;
            color: var(--text-on-glass);
            line-height: 1.2;
            text-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }

        [data-theme=dark] .login-logo h1 { text-shadow: 0 2px 4px rgba(0,0,0,0.5); }

        .login-logo span {
            display: block;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .15em;
            text-transform: uppercase;
            color: var(--label-on-glass);
            margin-top: 6px;
        }

        /* INPUTS COM ÍCONES REFINADOS */
        .form-group {
            margin-bottom: 20px;
        }

        .input-label { 
            display: block; 
            font-size: 12px; 
            font-weight: 600; 
            color: var(--label-on-glass); 
            margin-bottom: 8px; 
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper svg {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            width: 18px;
            height: 18px;
            color: var(--ash);
            pointer-events: none;
            transition: color var(--t);
        }

        .input-field { 
            width: 100%; 
            height: 48px; 
            border: 1px solid var(--glass-border); 
            border-radius: 10px; 
            background: var(--input-bg-glass); 
            padding: 0 16px 0 42px; 
            font-size: 14px; 
            font-family: inherit; 
            color: var(--text-on-glass); 
            outline: none; 
            transition: all var(--t); 
        }

        .input-field::placeholder { color: var(--ash); opacity: 0.5; }
        .input-field:focus { 
            border-color: var(--cr); 
            background: var(--input-focused);
            box-shadow: 0 0 0 4px var(--cr-bg); 
        }
        .input-field:focus + svg { color: var(--cr); }
        
        .btn-primary { 
            width: 100%; 
            height: 50px; 
            background: var(--cr); 
            border: none; 
            border-radius: 10px; 
            font-size: 15px; 
            font-weight: 600; 
            color: #fff; 
            cursor: pointer; 
            transition: all var(--t); 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            gap: 8px; 
            box-shadow: 0 4px 15px rgba(176, 0, 0, 0.3); 
            margin-top: 12px; 
        }
        .btn-primary:hover { 
            background: var(--cr2); 
            transform: translateY(-2px); 
            box-shadow: 0 8px 25px rgba(176, 0, 0, 0.4);
        }
        .btn-primary:active { transform: translateY(0); }

        .alert-box { 
            padding: 14px 16px; 
            border-radius: 10px; 
            font-size: 13px; 
            font-weight: 500; 
            margin-bottom: 24px; 
            display: flex; 
            align-items: center; 
            gap: 10px; 
            background: var(--alert-bg); 
            color: var(--alert-text); 
            border: 1px solid var(--alert-border); 
            backdrop-filter: blur(4px);
        }
    </style>
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
            <div class="sb-icon">
                <svg fill="none" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            </div>
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
        // Função para alternar o tema e salvar no localStorage
        function toggleTheme(theme) {
            document.documentElement.setAttribute('data-theme', theme);
            localStorage.setItem('theme', theme);
        }

        // Recupera o tema salvo ou prefere o do sistema
        (() => { 
            const savedTheme = localStorage.getItem('theme'); 
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            
            let theme = 'light'; // Padrão
            if (savedTheme) {
                theme = savedTheme;
            } else if (prefersDark) {
                theme = 'dark';
            }
            toggleTheme(theme);
        })();

        // Formatação simples de CPF automática no input
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