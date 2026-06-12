{{-- Modal de Detalhes do Fornecedor --}}
<div id="supplierModal" 
     class="fixed inset-0 z-50 hidden overflow-y-auto" 
     aria-labelledby="modal-title" 
     role="dialog" 
     aria-modal="true">
    
    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" 
         onclick="SupplierModal.close()"></div>
    
    {{-- Modal Card --}}
    <div class="flex min-h-full items-center justify-center p-4 sm:p-6">
        <div class="relative w-full max-w-4xl transform overflow-hidden rounded-2xl border bg-white shadow-2xl shadow-[#1a3d1f]/25 transition-all"
             style="border-color:#d4e8d6;"
             onclick="event.stopPropagation()">
            
            {{-- Header do Modal --}}
            <div class="flex items-start justify-between gap-4 border-b bg-[#f9f6f0] px-6 py-5"
                 style="border-color:#d4e8d6;">
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl border bg-white shadow-sm"
                         style="border-color:#d4e8d6;color:#2d6a35;">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M16 11a4 4 0 1 0-8 0v1H6a2 2 0 0 0-2 2v5h16v-5a2 2 0 0 0-2-2h-2v-1Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M9 7a4 4 0 0 1 6 0" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-[0.2em]" style="color:#8a9e8c;">
                            Cadastro de fornecedor
                        </p>
                        <h3 id="modal-title" class="mt-1 text-xl font-bold tracking-tight" style="color:#1a3d1f;">
                            Detalhes do fornecedor
                        </h3>
                    </div>
                </div>
                <button onclick="SupplierModal.close()" 
                        class="flex h-10 w-10 items-center justify-center rounded-xl border bg-white text-slate-500 shadow-sm transition hover:bg-[#eef7ef] hover:text-[#1a3d1f] focus:outline-none focus:ring-2 focus:ring-[#2d6a35] focus:ring-offset-2"
                        style="border-color:#d4e8d6;"
                        aria-label="Fechar modal">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Conteúdo do Modal --}}
            <div id="modalContent" class="max-h-[72vh] overflow-y-auto bg-white p-6">
                {{-- Carregando... --}}
                <div class="flex flex-col items-center justify-center py-12 text-center">
                    <div class="h-12 w-12 animate-spin rounded-full border-4 border-[#d4e8d6] border-t-[#2d6a35]"></div>
                    <p class="mt-4 text-sm font-semibold" style="color:#1a3d1f;">Carregando fornecedor</p>
                    <p class="mt-1 text-xs" style="color:#8a9e8c;">Buscando detalhes e produtos vinculados.</p>
                </div>
            </div>
        </div>
    </div>
</div>
