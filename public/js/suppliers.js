/**
 * Gerenciador de Modal de Fornecedores
 */
const SupplierModal = {
    modal: null,
    content: null,

    /**
     * Inicializa o modal
     */
    init() {
        this.modal = document.getElementById('supplierModal');
        this.content = document.getElementById('modalContent');
        
        // Listener para fechar com ESC
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !this.modal.classList.contains('hidden')) {
                this.close();
            }
        });
    },

    /**
     * Abre o modal e carrega os dados do fornecedor
     */
    show(supplierId) {
        if (!this.modal || !this.content) {
            console.error('Modal não inicializado');
            return;
        }

        // Mostrar modal
        this.modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Resetar conteúdo para loading
        this.content.innerHTML = this.renderLoading();
        
        // Carregar dados via AJAX
        this.loadSupplierData(supplierId);
    },

    /**
     * Fecha o modal
     */
    close() {
        if (this.modal) {
            this.modal.classList.add('hidden');
            document.body.style.overflow = '';
        }
    },

    /**
     * Carrega os dados do fornecedor via AJAX
     */
    async loadSupplierData(supplierId) {
        try {
            const response = await fetch(`/suppliers/${supplierId}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error('Erro ao carregar dados');
            }

            const data = await response.json();
            this.content.innerHTML = this.renderSupplierDetails(data);
            
        } catch (error) {
            console.error('Erro ao carregar fornecedor:', error);
            this.content.innerHTML = this.renderError();
        }
    },

    /**
     * Renderiza o estado de carregamento
     */
    renderLoading() {
        return `
            <div class="text-center py-8">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-green-600 mx-auto"></div>
                <p class="mt-4 text-gray-600 dark:text-gray-400">Carregando...</p>
            </div>
        `;
    },

    /**
     * Renderiza mensagem de erro
     */
    renderError() {
        return `
            <div class="text-center py-8">
                <p class="text-red-600 dark:text-red-400">❌ Erro ao carregar detalhes.</p>
                <button onclick="SupplierModal.close()" 
                        class="mt-4 px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                    Fechar
                </button>
            </div>
        `;
    },

    /**
     * Renderiza os detalhes do fornecedor
     */
    renderSupplierDetails(data) {
        const supplier = data.supplier;
        const products = data.products;
        
        return `
            ${this.renderBasicInfo(supplier)}
            ${supplier.notes ? this.renderNotes(supplier.notes) : ''}
            ${this.renderProducts(products)}
            ${this.renderActions(supplier.id)}
        `;
    },

    /**
     * Renderiza informações básicas e endereço
     */
    renderBasicInfo(supplier) {
        return `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Informações Básicas -->
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                    <h4 class="text-sm font-semibold text-gray-600 dark:text-gray-400 mb-3">📋 Informações Básicas</h4>
                    <div class="space-y-2">
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Nome:</span>
                            <p class="font-semibold text-gray-900 dark:text-gray-100">${supplier.name}</p>
                        </div>
                        ${supplier.contact_name ? `
                            <div>
                                <span class="text-xs text-gray-500 dark:text-gray-400">Contato:</span>
                                <p class="text-gray-800 dark:text-gray-200">${supplier.contact_name}</p>
                            </div>
                        ` : ''}
                        ${supplier.phone ? `
                            <div>
                                <span class="text-xs text-gray-500 dark:text-gray-400">Telefone:</span>
                                <p class="text-gray-800 dark:text-gray-200">${supplier.phone}</p>
                            </div>
                        ` : ''}
                        ${supplier.email ? `
                            <div>
                                <span class="text-xs text-gray-500 dark:text-gray-400">Email:</span>
                                <p class="text-gray-800 dark:text-gray-200">${supplier.email}</p>
                            </div>
                        ` : ''}
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Status:</span>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${supplier.active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}">
                                ${supplier.active ? '✓ Ativo' : '✗ Inativo'}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Endereço -->
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                    <h4 class="text-sm font-semibold text-gray-600 dark:text-gray-400 mb-3">📍 Endereço</h4>
                    <div class="space-y-2">
                        ${supplier.address ? `
                            <div>
                                <span class="text-xs text-gray-500 dark:text-gray-400">Endereço:</span>
                                <p class="text-gray-800 dark:text-gray-200">${supplier.address}</p>
                            </div>
                        ` : ''}
                        ${supplier.city || supplier.state ? `
                            <div>
                                <span class="text-xs text-gray-500 dark:text-gray-400">Cidade/UF:</span>
                                <p class="text-gray-800 dark:text-gray-200">${supplier.city || ''}${supplier.city && supplier.state ? '/' : ''}${supplier.state || ''}</p>
                            </div>
                        ` : ''}
                        ${supplier.zip_code ? `
                            <div>
                                <span class="text-xs text-gray-500 dark:text-gray-400">CEP:</span>
                                <p class="text-gray-800 dark:text-gray-200">${supplier.zip_code}</p>
                            </div>
                        ` : ''}
                        ${!supplier.address && !supplier.city && !supplier.state && !supplier.zip_code ? `
                            <p class="text-sm text-gray-500 dark:text-gray-400">Endereço não cadastrado</p>
                        ` : ''}
                    </div>
                </div>
            </div>
        `;
    },

    /**
     * Renderiza observações
     */
    renderNotes(notes) {
        return `
            <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-4 mb-6">
                <h4 class="text-sm font-semibold text-gray-600 dark:text-gray-400 mb-2">📝 Observações</h4>
                <p class="text-gray-800 dark:text-gray-200 whitespace-pre-line">${notes}</p>
            </div>
        `;
    },

    /**
     * Renderiza lista de produtos
     */
    renderProducts(products) {
        return `
            <div class="border-t dark:border-gray-700 pt-6">
                <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center gap-2">
                    📦 Produtos Vinculados
                    <span class="text-sm bg-blue-100 text-blue-800 px-2 py-1 rounded-full">${products.length}</span>
                </h4>
                
                ${products.length > 0 ? `
                    <div class="space-y-2">
                        ${products.map(product => `
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900 dark:text-gray-100">${product.name}</p>
                                    ${product.description ? `
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">${product.description}</p>
                                    ` : ''}
                                </div>
                                <div class="text-right ml-4">
                                    <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                        Estoque: ${product.stock_quantity}
                                    </p>
                                    ${product.selling_price ? `
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            R$ ${parseFloat(product.selling_price).toFixed(2).replace('.', ',')}
                                        </p>
                                    ` : ''}
                                </div>
                            </div>
                        `).join('')}
                    </div>
                ` : `
                    <p class="text-center text-gray-500 dark:text-gray-400 py-8 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        Nenhum produto vinculado a este fornecedor.
                    </p>
                `}
            </div>
        `;
    },

    /**
     * Renderiza botões de ação
     */
    renderActions(supplierId) {
        return `
            <div class="flex gap-3 mt-6 pt-6 border-t dark:border-gray-700">
                <a href="/suppliers/${supplierId}/edit" 
                   class="flex-1 px-4 py-2 bg-indigo-600 text-white text-center rounded-lg hover:bg-indigo-700 transition font-medium">
                    ✏️ Editar Fornecedor
                </a>
                <button onclick="SupplierModal.close()" 
                        class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition font-medium">
                    Fechar
                </button>
            </div>
        `;
    }
};

// Inicializar quando o DOM carregar
document.addEventListener('DOMContentLoaded', () => {
    SupplierModal.init();
});