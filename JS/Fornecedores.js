// JS/Fornecedores.js
document.addEventListener('DOMContentLoaded', function() {
    
    // 🔥 MÁSCARA DO CNPJ
    const cnpjInput = document.getElementById('cnpj');
    if (cnpjInput) {
        cnpjInput.addEventListener('input', function(e) {
            let value = this.value.replace(/\D/g, '');
            
            // Limita a 14 dígitos
            if (value.length > 14) {
                value = value.substring(0, 14);
            }
            
            // Aplica a máscara: 00.000.000/0000-00
            let formatted = '';
            for (let i = 0; i < value.length; i++) {
                if (i === 2 || i === 5) {
                    formatted += '.';
                } else if (i === 8) {
                    formatted += '/';
                } else if (i === 12) {
                    formatted += '-';
                }
                formatted += value[i];
            }
            
            this.value = formatted;
        });
    }
    
    // 🔥 MÁSCARA DO TELEFONE
    const telefoneInput = document.getElementById('telefone');
    if (telefoneInput) {
        telefoneInput.addEventListener('input', function(e) {
            let value = this.value.replace(/\D/g, '');
            
            // Limita a 11 dígitos
            if (value.length > 11) {
                value = value.substring(0, 11);
            }
            
            // Aplica a máscara: (00) 00000-0000
            let formatted = '';
            for (let i = 0; i < value.length; i++) {
                if (i === 0) {
                    formatted += '(';
                } else if (i === 2) {
                    formatted += ') ';
                } else if (i === 7) {
                    formatted += '-';
                }
                formatted += value[i];
            }
            
            this.value = formatted;
        });
    }
    
    // 🔥 MÁSCARA DO CEP
    const cepInput = document.getElementById('cep');
    if (cepInput) {
        cepInput.addEventListener('input', function(e) {
            let value = this.value.replace(/\D/g, '');
            
            if (value.length > 8) {
                value = value.substring(0, 8);
            }
            
            if (value.length > 5) {
                this.value = value.substring(0, 5) + '-' + value.substring(5);
            } else {
                this.value = value;
            }
        });
    }
});

// 🔥 FUNÇÃO PARA BUSCAR ENDEREÇO POR CEP
function buscarEndereco() {
    const cep = document.getElementById('cep').value.replace(/\D/g, '');
    
    if (cep.length === 8) {
        fetch(`https://viacep.com.br/ws/${cep}/json/`)
            .then(response => response.json())
            .then(data => {
                if (!data.erro) {
                    document.getElementById('logradouro').value = data.logradouro || '';
                    document.getElementById('bairro').value = data.bairro || '';
                    document.getElementById('cidade').value = data.localidade || '';
                    document.getElementById('uf').value = data.uf || '';
                }
            })
            .catch(error => console.error('Erro ao buscar CEP:', error));
    }
}