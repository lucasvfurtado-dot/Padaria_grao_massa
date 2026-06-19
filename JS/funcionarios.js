// ===================================================================
// Grão & Massa — Script Global
// ===================================================================

// Data Dinâmica
const dateEl = document.getElementById('date');
if (dateEl) {
    const d = new Date();
    dateEl.textContent = d
        .toLocaleDateString('pt-BR', { weekday: 'long', day: 'numeric', month: 'long' })
        .replace(/^\w/, c => c.toUpperCase());
}

// Toggle Tema utilizando o atributo nativo data-theme
function toggleTheme() {
    const html = document.documentElement;
    const isDark = html.getAttribute('data-theme') === 'dark';
    const next = isDark ? 'light' : 'dark';
    html.setAttribute('data-theme', next);
    localStorage.setItem('theme', next);
}

// Recupera o tema do LocalStorage ou preferência do sistema
(function () {
    const saved = localStorage.getItem('theme');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    if (saved === 'dark' || (!saved && prefersDark)) {
        document.documentElement.setAttribute('data-theme', 'dark');
    }
})();

// Dropdown simples do menu de usuário (substitui o dropdown do Bootstrap)
document.addEventListener('DOMContentLoaded', () => {
    const trigger = document.querySelector('[data-dropdown-trigger]');
    const menu = document.querySelector('[data-dropdown-menu]');

    if (trigger && menu) {
        trigger.addEventListener('click', (e) => {
            e.stopPropagation();
            menu.classList.toggle('show');
        });

        document.addEventListener('click', () => {
            menu.classList.remove('show');
        });
    }
});
