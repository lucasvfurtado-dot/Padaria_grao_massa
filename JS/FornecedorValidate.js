// JS/FornecedorValidate.js - SEM VALIDAÇÃO RIGOROSA DO CNPJ
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
            
            // Valida Nome da Empresa
            if (!campos.nomeEmpresa || campos.nomeEmpresa.value.trim() === '') {
                erros.push('O campo "Razão Social / Nome da Empresa" é obrigatório');
                markInvalid(campos.nomeEmpresa);
            }
            
            // 🔥 VALIDAÇÃO SIMPLES DO CNPJ - SÓ VERIFICA SE NÃO ESTÁ VAZIO
            if (!campos.cnpj || campos.cnpj.value.trim() === '') {
                erros.push('O campo "CNPJ" é obrigatório');
                markInvalid(campos.cnpj);
            } else {
                // Remove máscara para verificar
                const cnpjLimpo = campos.cnpj.value.replace(/[^\d]/g, '');
                
                // ⚠️ SÓ VERIFICA SE TEM PELO MENOS 1 DÍGITO
                if (cnpjLimpo.length === 0) {
                    erros.push('CNPJ inválido. Digite pelo menos 1 número.');
                    markInvalid(campos.cnpj);
                }
                // ❌ REMOVIDA A VALIDAÇÃO DE 14 DÍGITOS E DÍGITOS VERIFICADORES
            }
            
            // Valida Telefone
            if (!campos.telefone || campos.telefone.value.trim() === '') {
                erros.push('O campo "Telefone / WhatsApp" é obrigatório');
                markInvalid(campos.telefone);
            } else {
                const telefoneLimpo = campos.telefone.value.replace(/[^\d]/g, '');
                if (telefoneLimpo.length < 8 || telefoneLimpo.length > 11) {
                    erros.push('Telefone inválido. Digite entre 8 e 11 números.');
                    markInvalid(campos.telefone);
                }
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