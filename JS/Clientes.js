// ==========================================
// JS/Clientes.js - FUNÇÕES DO CLIENTE
// ==========================================

// ===== MÁSCARAS =====

// MÁSCARA CPF/CNPJ
function mascaraCpfCnpj(input) {
    let value = input.value.replace(/\D/g, '');
    if (value.length > 3) value = value.replace(/^(\d{3})(\d)/, '$1.$2');
    if (value.length > 6) value = value.replace(/^(\d{3})\.(\d{3})(\d)/, '$1.$2.$3');
    if (value.length > 9) value = value.replace(/^(\d{3})\.(\d{3})\.(\d{3})(\d)/, '$1.$2.$3-$4');
    input.value = value;
}

// MÁSCARA TELEFONE
function mascaraTelefone(input) {
    let value = input.value.replace(/\D/g, '');
    if (value.length > 2) value = '(' + value.substring(0,2) + ') ' + value.substring(2);
    if (value.length > 10) value = value.substring(0,10) + '-' + value.substring(10);
    input.value = value;
}

// MÁSCARA CEP
function mascaraCep(input) {
    let value = input.value.replace(/\D/g, '');
    if (value.length > 5) value = value.substring(0,5) + '-' + value.substring(5);
    input.value = value;
}

// ===== BUSCA CEP (USANDO O SISTEMA DE ALERTAS) =====

// Esta função é chamada pelo evento blur do campo CEP
function buscarEnderecoCliente(cepInput) {
    const cep = cepInput.value.replace(/\D/g, '');
    
    if (cep.length !== 8) {
        if (cep.length > 0 && cep.length < 8) {
            alertas.warning('Digite um CEP válido com 8 dígitos.', 'CEP Inválido');
        }
        return;
    }

    // Referências aos campos
    const campos = {
        logradouro: document.getElementById('logradouro'),
        bairro: document.getElementById('bairro'),
        cidade: document.getElementById('cidade'),
        uf: document.getElementById('uf'),
        numero: document.getElementById('numero')
    };

    // Mostra loading
    if (campos.logradouro) campos.logradouro.value = 'Buscando...';
    if (campos.bairro) campos.bairro.value = 'Buscando...';
    if (campos.cidade) campos.cidade.value = 'Buscando...';
    if (campos.uf) campos.uf.value = '...';

    fetch(`https://viacep.com.br/ws/${cep}/json/`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Erro na comunicação com o serviço de CEP');
            }
            return response.json();
        })
        .then(data => {
            if (data.erro) {
                // CEP não encontrado - ALERTA BONITO (sem alert() do navegador!)
                alertas.error(
                    'Não foi possível encontrar o CEP informado. Verifique o número e tente novamente.',
                    '📍 CEP Não Encontrado',
                    5000
                );
                
                // Limpa os campos
                if (campos.logradouro) campos.logradouro.value = '';
                if (campos.bairro) campos.bairro.value = '';
                if (campos.cidade) campos.cidade.value = '';
                if (campos.uf) campos.uf.value = '';
                
                if (campos.numero) campos.numero.focus();
                return;
            }

            // Preenche os campos
            if (campos.logradouro) campos.logradouro.value = data.logradouro || '';
            if (campos.bairro) campos.bairro.value = data.bairro || '';
            if (campos.cidade) campos.cidade.value = data.localidade || '';
            if (campos.uf) campos.uf.value = data.uf || '';

            // Mostra sucesso
            alertas.success(
                `Endereço encontrado: ${data.logradouro || 'Rua não informada'}`,
                '📍 CEP Encontrado',
                3000
            );

            // Foca no número
            if (campos.numero) campos.numero.focus();

        })
        .catch(error => {
            console.error('Erro ao buscar CEP:', error);
            alertas.error(
                'Ocorreu um erro ao buscar o CEP. Verifique sua conexão com a internet.',
                '⚠️ Erro de Conexão',
                5000
            );
            
            // Limpa os campos
            if (campos.logradouro) campos.logradouro.value = '';
            if (campos.bairro) campos.bairro.value = '';
            if (campos.cidade) campos.cidade.value = '';
            if (campos.uf) campos.uf.value = '';
        });
}

// ===== VALIDAÇÃO DO FORMULÁRIO =====

function validarCliente(form) {
    const nome = form.querySelector('[name="nNome"]');
    const cpf = form.querySelector('[name="nCpfCnpj"]');
    const telefone = form.querySelector('[name="nTelefone"]');
    
    if (!nome || nome.value.trim() === '') {
        alertas.warning('O campo Nome é obrigatório.', '⚠️ Campos Obrigatórios');
        nome.focus();
        return false;
    }
    
    if (!cpf || cpf.value.trim() === '') {
        alertas.warning('O campo CPF/CNPJ é obrigatório.', '⚠️ Campos Obrigatórios');
        cpf.focus();
        return false;
    }
    
    if (!telefone || telefone.value.trim() === '') {
        alertas.warning('O campo Telefone é obrigatório.', '⚠️ Campos Obrigatórios');
        telefone.focus();
        return false;
    }
    
    return true;
}

// ===== INICIALIZAÇÃO =====
document.addEventListener('DOMContentLoaded', function() {
    // Máscaras
    const cpfInput = document.getElementById('cpfCnpj');
    const telefoneInput = document.getElementById('telefone');
    const cepInput = document.getElementById('cep');
    
    if (cpfInput) {
        cpfInput.addEventListener('input', function() {
            mascaraCpfCnpj(this);
        });
    }
    
    if (telefoneInput) {
        telefoneInput.addEventListener('input', function() {
            mascaraTelefone(this);
        });
    }
    
    if (cepInput) {
        cepInput.addEventListener('input', function() {
            mascaraCep(this);
        });
        
        cepInput.addEventListener('blur', function() {
            buscarEnderecoCliente(this);
        });
    }
    
    // Validação do formulário
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (!validarCliente(this)) {
                e.preventDefault();
            }
        });
    }
});