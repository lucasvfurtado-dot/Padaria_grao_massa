    // Data Dinâmica
    const d = new Date();
    document.getElementById('date').textContent = d.toLocaleDateString('pt-BR',{weekday:'long',day:'numeric',month:'long'}).replace(/^\w/,c=>c.toUpperCase());
    
    // Toggle Tema utilizando o atributo nativo data-bs-theme do Bootstrap 5.3
    function toggleTheme(){
        const html = document.documentElement;
        const isDark = html.getAttribute('data-bs-theme') === 'dark';
        const next = isDark ? 'light' : 'dark';
        html.setAttribute('data-bs-theme', next);
        localStorage.setItem('theme', next);
    }
    
    // Recupera o tema do LocalStorage ou preferência do sistema
    (function(){
        const saved = localStorage.getItem('theme');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        if(saved === 'dark' || (!saved && prefersDark)){
            document.documentElement.setAttribute('data-bs-theme', 'dark');
        }
    })();