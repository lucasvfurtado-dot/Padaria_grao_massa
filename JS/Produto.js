document.addEventListener("DOMContentLoaded", function() {

    // 1. MÁSCARA PARA O PREÇO
    document.getElementById('preco')?.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, ''); 
        if (value === "") {
            e.target.value = "";
            return;
        }
        value = (parseInt(value, 10) / 100).toFixed(2);
        e.target.value = value;
    });

    // 2. MÁSCARA PARA ESTOQUE (Só números)
    document.getElementById('estoque')?.addEventListener('input', function(e) {
        e.target.value = e.target.value.replace(/\D/g, ''); 
    });

    // 3. MÁSCARA PARA CÓDIGO (Tudo maiúsculo)
    document.getElementById('codigo')?.addEventListener('input', function(e) {
        e.target.value = e.target.value.toUpperCase();
    });

    // 4. PRÉ-VISUALIZAÇÃO DA IMAGEM AO SELECIONAR ARQUIVO
    const inputImagem = document.getElementById('imagem');
    const previewImagem = document.getElementById('preview-imagem');

    if (inputImagem && previewImagem) {
        inputImagem.addEventListener('change', function(e) {
            const file = e.target.files[0]; 
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(evento) {
                    previewImagem.src = evento.target.result;
                    previewImagem.style.display = 'block'; 
                }
                reader.readAsDataURL(file);
            } else {
                previewImagem.src = '';
                previewImagem.style.display = 'none';
            }
        });
    }

    // 5. DATA NO HEADER
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