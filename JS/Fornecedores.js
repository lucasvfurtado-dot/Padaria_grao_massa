// JS/Fornecedores.js - Máscara do CNPJ
document.addEventListener('DOMContentLoaded', function() {
    // Máscara do CNPJ
    const cnpjInput = document.getElementById('cnpj');
    if (cnpjInput) {
        cnpjInput.addEventListener('input', function(e) {
            let value = this.value.replace(/\D/g, '');
            
            // Aplica a máscara: 00.000.000/0000-00
            if (value.length > 2) {
                value = value.substring(0, 2) + '.' + value.substring(2);
            }
            if (value.length > 6) {
                value = value.substring(0, 6) + '.' + value.substring(6);
            }
            if (value.length > 10) {
                value = value.substring(0, 10) + '/' + value.substring(10);
            }
            if (value.length > 15) {
                value = value.substring(0, 15) + '-' + value.substring(15, 17);
            }
            
            this.value = value;
        });
    }

    // Máscara do Telefone
    const telefoneInput = document.getElementById('telefone');
    if (telefoneInput) {
        telefoneInput.addEventListener('input', function(e) {
            let value = this.value.replace(/\D/g, '');
            
            if (value.length > 2) {
                value = '(' + value.substring(0, 2) + ') ' + value.substring(2);
            }
            if (value.length > 10) {
                value = value.substring(0, 10) + '-' + value.substring(10, 15);
            }
            
            this.value = value;
        });
    }
});