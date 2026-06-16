// Aguarda o documento carregar completamente para rodar o JS
document.addEventListener("DOMContentLoaded", function() {

    // 1. MÁSCARA PARA O PREÇO (Formato 0.00 para facilitar salvar no banco)
    const inputPreco = document.querySelector('input[name="nPreco"]');
    if (inputPreco) {
        inputPreco.addEventListener('input', function (e) {
            // Remove tudo que não for número
            let value = e.target.value.replace(/\D/g, ''); 
            
            if (value === "") {
                e.target.value = "";
                return;
            }
            
            // Divide por 100 para criar as casas decimais e formata com ponto
            // Ex: digitou 1500 -> vira 15.00
            value = (parseInt(value, 10) / 100).toFixed(2);
            e.target.value = value;
        });
    }

    // 2. MÁSCARA PARA ESTOQUE (Permite apenas números inteiros)
    const inputEstoque = document.querySelector('input[name="nEstoque"]');
    if (inputEstoque) {
        inputEstoque.addEventListener('input', function (e) {
            // Remove qualquer letra ou caractere especial
            e.target.value = e.target.value.replace(/\D/g, ''); 
        });
    }

    // 3. MÁSCARA PARA CÓDIGO / LOTE (Deixa tudo em letras maiúsculas automaticamente)
    const inputCodigo = document.querySelector('input[name="nCodigo"]');
    if (inputCodigo) {
        inputCodigo.addEventListener('input', function (e) {
            // Converte a digitação imediatamente para MAIÚSCULO
            e.target.value = e.target.value.toUpperCase();
        });
    }

});