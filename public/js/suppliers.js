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

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.modal && !this.modal.classList.contains('hidden')) {
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

        this.modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        this.content.innerHTML = this.renderLoading();
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
                    'Accept': 'application/json',
                },
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

    escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    },

    formatMoney(value) {
        const amount = Number.parseFloat(value);

        if (Number.isNaN(amount)) {
            return '';
        }

        return amount.toLocaleString('pt-BR', {
            style: 'currency',
            currency: 'BRL',
        });
    },

    renderField(label, value) {
        if (!value) {
            return '';
        }

        return `
            <div class="rounded-xl border bg-white px-4 py-3" style="border-color:#d4e8d6;">
                <p class="text-[11px] font-bold uppercase tracking-[0.16em]" style="color:#8a9e8c;">${this.escapeHtml(label)}</p>
                <p class="mt-1 text-sm font-semibold text-slate-900">${this.escapeHtml(value)}</p>
            </div>
        `;
    },

    /**
     * Renderiza o estado de carregamento
     */
    renderLoading() {
        return `
            <div class="flex flex-col items-center justify-center py-12 text-center">
                <div class="h-12 w-12 animate-spin rounded-full border-4 border-[#d4e8d6] border-t-[#2d6a35]"></div>
                <p class="mt-4 text-sm font-semibold" style="color:#1a3d1f;">Carregando fornecedor</p>
                <p class="mt-1 text-xs" style="color:#8a9e8c;">Buscando detalhes e produtos vinculados.</p>
            </div>
        `;
    },

    /**
     * Renderiza mensagem de erro
     */
    renderError() {
        return `
            <div class="rounded-2xl border bg-red-50 px-5 py-8 text-center" style="border-color:#fecaca;">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-white text-red-700">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M12 9v4m0 4h.01M10.3 4.3 2.8 17a2 2 0 0 0 1.7 3h15a2 2 0 0 0 1.7-3L13.7 4.3a2 2 0 0 0-3.4 0Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <p class="mt-4 text-sm font-bold text-red-800">Erro ao carregar detalhes.</p>
                <p class="mt-1 text-xs text-red-700">Tente abrir o fornecedor novamente.</p>
                <button onclick="SupplierModal.close()"
                        class="mt-5 rounded-lg border bg-white px-5 py-2 text-sm font-semibold transition hover:bg-red-100"
                        style="border-color:#fecaca;color:#991b1b;">
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
        const products = data.products || [];

        return `
            ${this.renderSupplierHeader(supplier, products.length)}
            ${this.renderBasicInfo(supplier)}
            ${supplier.notes ? this.renderNotes(supplier.notes) : ''}
            ${this.renderProducts(products)}
            ${this.renderActions(supplier.id)}
        `;
    },

    renderSupplierHeader(supplier, productCount) {
        const statusClass = supplier.active
            ? 'bg-emerald-50 text-emerald-700'
            : 'bg-slate-100 text-slate-600';
        const statusLabel = supplier.active ? 'Ativo' : 'Inativo';

        return `
            <div class="mb-6 rounded-2xl border p-5" style="border-color:#d4e8d6;background:#f9f6f0;">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-[0.18em]" style="color:#8a9e8c;">Fornecedor</p>
                        <h4 class="mt-1 text-2xl font-bold tracking-tight" style="color:#1a3d1f;">${this.escapeHtml(supplier.name)}</h4>
                        <p class="mt-2 text-sm" style="color:#6f7f71;">${productCount} ${productCount === 1 ? 'produto vinculado' : 'produtos vinculados'}</p>
                    </div>
                    <span class="inline-flex w-fit items-center rounded-full px-3 py-1 text-xs font-bold ${statusClass}">
                        ${statusLabel}
                    </span>
                </div>
            </div>
        `;
    },

    /**
     * Renderiza informações básicas e endereço
     */
    renderBasicInfo(supplier) {
        const location = [supplier.city, supplier.state].filter(Boolean).join('/');
        const hasAddress = supplier.address || location || supplier.zip_code;

        return `
            <div class="mb-6 grid grid-cols-1 gap-5 md:grid-cols-2">
                <section class="rounded-2xl border bg-white p-5" style="border-color:#d4e8d6;">
                    <div class="mb-4 flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M20 21a8 8 0 0 0-16 0m12-13a4 4 0 1 1-8 0 4 4 0 0 1 8 0Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div>
                            <h5 class="text-sm font-bold" style="color:#1a3d1f;">Contato</h5>
                            <p class="text-xs" style="color:#8a9e8c;">Dados comerciais</p>
                        </div>
                    </div>
                    <div class="grid gap-3">
                        ${this.renderField('Responsável', supplier.contact_name)}
                        ${this.renderField('Telefone', supplier.phone)}
                        ${this.renderField('E-mail', supplier.email)}
                    </div>
                </section>

                <section class="rounded-2xl border bg-white p-5" style="border-color:#d4e8d6;">
                    <div class="mb-4 flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M12 21s7-5.3 7-11a7 7 0 1 0-14 0c0 5.7 7 11 7 11Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M12 10.5h.01" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                            </svg>
                        </div>
                        <div>
                            <h5 class="text-sm font-bold" style="color:#1a3d1f;">Endereço</h5>
                            <p class="text-xs" style="color:#8a9e8c;">Localização cadastrada</p>
                        </div>
                    </div>
                    ${hasAddress ? `
                        <div class="grid gap-3">
                            ${this.renderField('Endereço', supplier.address)}
                            ${this.renderField('Cidade/UF', location)}
                            ${this.renderField('CEP', supplier.zip_code)}
                        </div>
                    ` : `
                        <div class="rounded-xl border border-dashed px-4 py-6 text-center text-sm" style="border-color:#d4e8d6;color:#8a9e8c;">
                            Endereço não cadastrado
                        </div>
                    `}
                </section>
            </div>
        `;
    },

    /**
     * Renderiza observações
     */
    renderNotes(notes) {
        return `
            <section class="mb-6 rounded-2xl border bg-[#fffaf0] p-5" style="border-color:#fde68a;">
                <div class="mb-3 flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-amber-700">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M4 5h16M4 12h16M4 19h10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <h5 class="text-sm font-bold" style="color:#1a3d1f;">Observações</h5>
                </div>
                <p class="whitespace-pre-line text-sm leading-6 text-slate-700">${this.escapeHtml(notes)}</p>
            </section>
        `;
    },

    /**
     * Renderiza lista de produtos
     */
    renderProducts(products) {
        return `
            <section class="rounded-2xl border bg-white p-5" style="border-color:#d4e8d6;">
                <div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h5 class="text-sm font-bold" style="color:#1a3d1f;">Produtos vinculados</h5>
                        <p class="text-xs" style="color:#8a9e8c;">Itens associados a este fornecedor</p>
                    </div>
                    <span class="inline-flex w-fit rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">${products.length}</span>
                </div>

                ${products.length > 0 ? `
                    <div class="grid gap-3">
                        ${products.map((product) => `
                            <div class="flex flex-col gap-3 rounded-xl border bg-[#fbfdfb] px-4 py-3 transition hover:bg-[#eef7ef] sm:flex-row sm:items-center sm:justify-between" style="border-color:#d4e8d6;">
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-bold text-slate-900">${this.escapeHtml(product.name)}</p>
                                    ${product.description ? `
                                        <p class="mt-1 text-xs text-slate-500">${this.escapeHtml(product.description)}</p>
                                    ` : ''}
                                </div>
                                <div class="shrink-0 text-left sm:text-right">
                                    <p class="text-sm font-bold" style="color:#1a3d1f;">Estoque: ${this.escapeHtml(product.stock_quantity)}</p>
                                    ${product.selling_price ? `
                                        <p class="mt-1 text-xs text-slate-500">${this.formatMoney(product.selling_price)}</p>
                                    ` : ''}
                                </div>
                            </div>
                        `).join('')}
                    </div>
                ` : `
                    <div class="rounded-xl border border-dashed px-4 py-8 text-center text-sm" style="border-color:#d4e8d6;color:#8a9e8c;">
                        Nenhum produto vinculado a este fornecedor.
                    </div>
                `}
            </section>
        `;
    },

    /**
     * Renderiza botões de ação
     */
    renderActions(supplierId) {
        return `
            <div class="mt-6 flex flex-col-reverse gap-3 border-t pt-5 sm:flex-row sm:items-center sm:justify-end" style="border-color:#d4e8d6;">
                <button onclick="SupplierModal.close()"
                        class="rounded-lg border px-5 py-2.5 text-sm font-semibold transition hover:bg-[#f9f6f0]"
                        style="border-color:#d4e8d6;color:#4a5c4c;">
                    Fechar
                </button>
                <a href="/suppliers/${this.escapeHtml(supplierId)}/edit"
                   class="rounded-lg px-5 py-2.5 text-center text-sm font-bold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-[#2d6a35]"
                   style="background:#1a3d1f;">
                    Editar fornecedor
                </a>
            </div>
        `;
    },
};

// Inicializar quando o DOM carregar
document.addEventListener('DOMContentLoaded', () => {
    SupplierModal.init();
});

window.SupplierModal = SupplierModal;
