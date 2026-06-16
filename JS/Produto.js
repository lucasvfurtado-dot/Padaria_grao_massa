// Aguarda o documento carregar completamente para rodar o JS
document.addEventListener("DOMContentLoaded", function() {

    // Máscara para o Preço (Formato decimal 0.00 para o banco de dados)
    document.getElementById('preco')?.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, ''); 
        if (value === "") {
            e.target.value = "";
            return;
        }
        value = (parseInt(value, 10) / 100).toFixed(2);
        e.target.value = value;
    });

    // Máscara para Estoque (Bloqueia letras e aceita apenas números inteiros)
    document.getElementById('estoque')?.addEventListener('input', function(e) {
        e.target.value = e.target.value.replace(/\D/g, ''); 
    });

    // Máscara para Código / Lote (Força todas as letras ficarem em Maiúsculo)
    document.getElementById('codigo')?.addEventListener('input', function(e) {
        e.target.value = e.target.value.toUpperCase();
    });

    // Data no header (Igual ao de cliente e fornecedor)
    const dateStr = new Date().toLocaleDateString('pt-BR', {
        weekday: 'long',
        day: 'numeric',
        month: 'long'
    });
    const dateElement = document.getElementById('date');
    if (dateElement) {
        dateElement.innerText = dateStr.charAt(0).toUpperCase() + dateStr.slice(1);
    }

});