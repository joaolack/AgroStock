{{-- Modal de Detalhes do Fornecedor --}}
<div id="supplierModal" 
     class="fixed inset-0 z-50 hidden overflow-y-auto" 
     aria-labelledby="modal-title" 
     role="dialog" 
     aria-modal="true">
    
    {{-- Backdrop com blur --}}
    <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" 
         onclick="SupplierModal.close()"></div>
    
    {{-- Modal Card --}}
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-gray-800 shadow-2xl transition-all w-full max-w-3xl"
             onclick="event.stopPropagation()">
            
            {{-- Header do Modal --}}
            <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4 flex items-center justify-between">
                <h3 class="text-xl font-semibold text-white flex items-center gap-2">
                    🏭 Detalhes do Fornecedor
                </h3>
                <button onclick="SupplierModal.close()" 
                        class="text-white/80 hover:text-white transition">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Conteúdo do Modal --}}
            <div id="modalContent" class="p-6 max-h-[70vh] overflow-y-auto">
                {{-- Carregando... --}}
                <div class="text-center py-8">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-green-600 mx-auto"></div>
                    <p class="mt-4 text-gray-600 dark:text-gray-400">Carregando...</p>
                </div>
            </div>
        </div>
    </div>
</div>