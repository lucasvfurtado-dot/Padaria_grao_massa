// ==========================================
// SISTEMA DE ALERTAS/NOTIFICAÇÕES GLOBAL
// ==========================================

class AlertSystem {
    constructor() {
        this.container = null;
        this.init();
    }

    init() {
        // Cria o container se não existir
        if (!document.getElementById('alert-container')) {
            this.container = document.createElement('div');
            this.container.id = 'alert-container';
            this.container.className = 'alert-container';
            document.body.appendChild(this.container);
        } else {
            this.container = document.getElementById('alert-container');
        }
    }

    /**
     * Mostra um alerta
     * @param {string} message - Mensagem principal
     * @param {string} type - 'success', 'error', 'warning', 'info'
     * @param {string} title - Título do alerta (opcional)
     * @param {number} duration - Tempo em ms antes de fechar (padrão: 5000)
     */
    show(message, type = 'info', title = '', duration = 5000) {
        // Remove alertas duplicados com a mesma mensagem
        const existing = this.container.querySelectorAll('.alert-item');
        for (const item of existing) {
            const msgEl = item.querySelector('.alert-message');
            if (msgEl && msgEl.textContent === message) {
                return; // Não mostra duplicado
            }
        }

        // Define título padrão baseado no tipo
        const titles = {
            success: '✅ Sucesso!',
            error: '❌ Erro!',
            warning: '⚠️ Atenção!',
            info: 'ℹ️ Informação'
        };
        const finalTitle = title || titles[type] || 'ℹ️ Informação';

        // Ícones SVG
        const icons = {
            success: `<svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>`,
            error: `<svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>`,
            warning: `<svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 9v4"/><path d="M12 17h.01"/><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/></svg>`,
            info: `<svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>`
        };

        // Cria o elemento do alerta
        const alert = document.createElement('div');
        alert.className = `alert-item alert-${type}`;
        alert.innerHTML = `
            <div class="alert-icon">${icons[type] || icons.info}</div>
            <div class="alert-content">
                <div class="alert-title">${finalTitle}</div>
                <div class="alert-message">${message}</div>
            </div>
            <button class="alert-close" onclick="this.closest('.alert-item').remove()">
                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        `;

        // Adiciona ao container
        this.container.appendChild(alert);

        // Auto-fechar após a duração
        if (duration > 0) {
            setTimeout(() => {
                this.close(alert);
            }, duration);
        }

        // Fechar ao clicar fora (opcional)
        return alert;
    }

    /**
     * Fecha um alerta com animação
     */
    close(alertElement) {
        if (!alertElement || alertElement.classList.contains('hiding')) return;
        
        alertElement.classList.add('hiding');
        setTimeout(() => {
            if (alertElement.parentNode) {
                alertElement.remove();
            }
        }, 300);
    }

    /**
     * Limpa todos os alertas
     */
    clearAll() {
        const alerts = this.container.querySelectorAll('.alert-item');
        alerts.forEach(alert => this.close(alert));
    }

    // ===== MÉTODOS DE ATALHO =====

    success(message, title = '✅ Sucesso!', duration = 4000) {
        return this.show(message, 'success', title, duration);
    }

    error(message, title = '❌ Erro!', duration = 5000) {
        return this.show(message, 'error', title, duration);
    }

    warning(message, title = '⚠️ Atenção!', duration = 4500) {
        return this.show(message, 'warning', title, duration);
    }

    info(message, title = 'ℹ️ Informação', duration = 4000) {
        return this.show(message, 'info', title, duration);
    }
}

// ==========================================
// INSTÂNCIA GLOBAL
// ==========================================

// Cria a instância global
const alertas = new AlertSystem();

// ==========================================
// FUNÇÃO DE BUSCA CEP MELHORADA
// ==========================================

/**
 * Busca endereço pelo CEP usando ViaCEP
 * @param {string} cep - CEP com ou sem máscara
 * @param {Object} campos - Objeto com os campos do formulário
 * @param {Function} callback - Função opcional após preencher
 */
function buscarEnderecoPorCEP(cep, campos, callback = null) {
    const cepLimpo = cep.replace(/\D/g, '');
    
    if (cepLimpo.length !== 8) {
        alertas.warning('Digite um CEP válido com 8 dígitos.', 'CEP Inválido');
        return false;
    }

    // Mostra loading nos campos
    if (campos.logradouro) campos.logradouro.value = 'Buscando...';
    if (campos.bairro) campos.bairro.value = 'Buscando...';
    if (campos.cidade) campos.cidade.value = 'Buscando...';
    if (campos.uf) campos.uf.value = '...';

    fetch(`https://viacep.com.br/ws/${cepLimpo}/json/`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Erro na comunicação com o serviço de CEP');
            }
            return response.json();
        })
        .then(data => {
            if (data.erro) {
                // CEP não encontrado - ALERTA BONITO!
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
                `Endereço encontrado: ${data.logradouro || 'Rua não informada'}, ${data.bairro || 'Bairro não informado'}`,
                '📍 CEP Encontrado',
                3000
            );

            // Foca no número
            if (campos.numero) campos.numero.focus();

            // Callback opcional
            if (callback) callback(data);

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

    return true;
}