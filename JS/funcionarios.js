// Aguarda o documento carregar completamente para rodar o JS
document.addEventListener("DOMContentLoaded", function() {

    // 1. MÁSCARA PARA TELEFONE
    const inputTelefone = document.getElementById('telefone');
    if (inputTelefone) {
        inputTelefone.addEventListener('input', function (e) {
            let x = e.target.value.replace(/\D/g, '').match(/(\d{0,2})(\d{0,5})(\d{0,4})/);
            e.target.value = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
        });
    }

    // 2. MÁSCARA APENAS PARA CPF (Ajustado para Funcionários)
    const inputCpf = document.getElementById('cpf');
    if (inputCpf) {
        inputCpf.addEventListener('input', function (e) {
            let v = e.target.value.replace(/\D/g, ''); 
            
            // Aplica a formatação do CPF: 000.000.000-00
            v = v.replace(/(\d{3})(\d)/, '$1.$2');
            v = v.replace(/(\d{3})(\d)/, '$1.$2');
            v = v.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            
            // Limita a 14 caracteres para garantir que não passe do tamanho do CPF
            e.target.value = v.substring(0, 14); 
        });
    }

    // 3. MÁSCARA PARA CEP
    const inputCep = document.getElementById('cep');
    if (inputCep) {
        inputCep.addEventListener('input', function (e) {
            let v = e.target.value.replace(/\D/g, '');
            v = v.replace(/^(\d{5})(\d)/, '$1-$2');
            e.target.value = v.substring(0, 9);
        });

        // 4. A MÁGICA DO CEP: Busca na API quando tira o foco do campo ('blur')
        inputCep.addEventListener('blur', function() {
            let cepValue = this.value.replace(/\D/g, ''); // Pega só os números do CEP
            
            // Verifica se tem 8 dígitos
            if (cepValue.length === 8) {
                // Coloca "Buscando..." enquanto a internet processa
                document.getElementById('logradouro').value = 'Buscando...';
                document.getElementById('bairro').value = 'Buscando...';
                document.getElementById('cidade').value = 'Buscando...';
                document.getElementById('uf').value = '...';
                
                // Consome a API do ViaCEP
                fetch(`https://viacep.com.br/ws/${cepValue}/json/`)
                    .then(response => response.json())
                    .then(data => {
                        if (!data.erro) {
                            // Preenche os campos com os dados que voltaram da API
                            document.getElementById('logradouro').value = data.logradouro;
                            document.getElementById('bairro').value = data.bairro;
                            document.getElementById('cidade').value = data.localidade;
                            document.getElementById('uf').value = data.uf;
                            
                            // Joga o cursor do mouse direto pro campo de número
                            document.getElementById('numero').focus();
                        } else {
                            alert('CEP não encontrado!');
                            limparCamposEndereco();
                        }
                    })
                    .catch(error => {
                        console.error('Erro ao buscar o CEP:', error);
                        alert('Erro ao buscar o CEP. Verifique sua conexão.');
                        limparCamposEndereco();
                    });
            }
        });
    }

    // Função de apoio para limpar os campos se o CEP der errado
    function limparCamposEndereco() {
        document.getElementById('logradouro').value = '';
        document.getElementById('bairro').value = '';
        document.getElementById('cidade').value = '';
        document.getElementById('uf').value = '';
    }

});