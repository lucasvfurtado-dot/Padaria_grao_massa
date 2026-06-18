// JS/FornecedorValidate.js
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('fornecedorForm');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            clearValidationErrors();
            
            const campos = {
                nomeEmpresa: document.querySelector('input[name="nNomeEmpresa"]'),
                cnpj: document.querySelector('input[name="nCnpj"]'),
                telefone: document.querySelector('input[name="nTelefone"]')
            };
            
            const erros = [];
            
            if (!campos.nomeEmpresa || campos.nomeEmpresa.value.trim() === '') {
                erros.push('O campo "Razão Social / Nome da Empresa" é obrigatório');
                markInvalid(campos.nomeEmpresa);
            }
            
            if (!campos.cnpj || campos.cnpj.value.trim() === '') {
                erros.push('O campo "CNPJ" é obrigatório');
                markInvalid(campos.cnpj);
            } else if (!isValidCNPJ(campos.cnpj.value)) {
                erros.push('CNPJ inválido. Use o formato: 00.000.000/0000-00');
                markInvalid(campos.cnpj);
            }
            
            if (!campos.telefone || campos.telefone.value.trim() === '') {
                erros.push('O campo "Telefone / WhatsApp" é obrigatório');
                markInvalid(campos.telefone);
            } else if (!isValidPhone(campos.telefone.value)) {
                erros.push('Telefone inválido. Use o formato: (00) 00000-0000');
                markInvalid(campos.telefone);
            }
            
            if (erros.length > 0) {
                showValidationAlert(erros);
                form.scrollIntoView({ behavior: 'smooth', block: 'start' });
            } else {
                form.submit();
            }
        });
        
        document.querySelectorAll('.form-control, .form-select').forEach(campo => {
            campo.addEventListener('input', function() {
                this.classList.remove('is-invalid-custom');
            });
        });
    }
});

function markInvalid(element) {
    if (element) {
        element.classList.add('is-invalid-custom');
    }
}

function clearValidationErrors() {
    document.querySelectorAll('.is-invalid-custom').forEach(el => {
        el.classList.remove('is-invalid-custom');
    });
    
    const alert = document.getElementById('validationAlert');
    if (alert) {
        alert.classList.remove('show');
    }
}

function showValidationAlert(erros) {
    const alert = document.getElementById('validationAlert');
    if (alert) {
        alert.innerHTML = '';
        
        const list = document.createElement('ul');
        erros.forEach(erro => {
            const li = document.createElement('li');
            li.textContent = erro;
            list.appendChild(li);
        });
        
        alert.appendChild(document.createTextNode('⚠️ Por favor, corrija os seguintes campos:'));
        alert.appendChild(list);
        alert.classList.add('show');
    }
}

function isValidCNPJ(cnpj) {
    cnpj = cnpj.replace(/[^\d]/g, '');
    if (cnpj.length !== 14) return false;
    if (/^(\d)\1+$/.test(cnpj)) return false;
    
    let soma = 0;
    let peso = 5;
    for (let i = 0; i < 12; i++) {
        soma += parseInt(cnpj.charAt(i)) * peso;
        peso = (peso === 2) ? 9 : peso - 1;
    }
    let digito1 = 11 - (soma % 11);
    digito1 = (digito1 > 9) ? 0 : digito1;
    
    soma = 0;
    peso = 6;
    for (let i = 0; i < 13; i++) {
        soma += parseInt(cnpj.charAt(i)) * peso;
        peso = (peso === 2) ? 9 : peso - 1;
    }
    let digito2 = 11 - (soma % 11);
    digito2 = (digito2 > 9) ? 0 : digito2;
    
    return (parseInt(cnpj.charAt(12)) === digito1 && parseInt(cnpj.charAt(13)) === digito2);
}

function isValidPhone(phone) {
    phone = phone.replace(/[^\d]/g, '');
    return phone.length === 10 || phone.length === 11;
}