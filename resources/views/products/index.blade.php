@extends('layouts.app')
    
@section('slot')
<div class="flex-1 flex flex-col min-h-screen overflow-hidden">

    <header class="sticky top-0 z-20 flex items-center justify-between px-6 py-3.5 border-b bg-white/80 backdrop-blur-md"
            style="border-color:#d4e8d6;">
        <div class="flex items-center gap-3">
            <button class="lg:hidden p-2 rounded-lg hover:bg-agro-pale transition colors" style="color:#4a5c4c;">
                ☰
            </button>
            <div>
                <h1 class="font-display text-xl font-bold tracking-tight" style="color:#1a3d1f;">Produtos</h1>
                <p class="text-[11px]" style="color:#8a9e8c;">Gerencie seu catálogo de insumos e produtos</p>
            </div>
        </div>

        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold text-white cursor-pointer"
                 style="background:linear-gradient(135deg,#4caf50,#2d6a35);">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
        </div>
    </header>

    <div class="flex-1 p-6 overflow-y-auto space-y-5">
        
        <!--Cards-->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">

            <div class="bg-white rounded-2xl p-4 border animate-fadeIn" style="border-color:#d4e8d6;animation-delay:0s;">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg" style="background:#eef7ef;">📦</div>
                </div>
                <p class="font-display text-2xl font-bold" style="color:#1a3d1f;">{{ number_format($totalProducts, 0, ',', '.')}}</p>
                <p class="text-xs mt-0.5" style="color:#8a9e8c;">Total de produtos</p>
            </div>

            <div class="bg-white rounded-2xl p-4 border animate-fadeIn" style="border-color:#d4e8d6;animation-delay:0.06s;">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg" style="background:#eef7ef;">⚠️</div>
                    <span class="text-[10px] font-semibold uppercase tracking-widest px-2 py-1 rounded-full" style="background:#fef9c3;color:#854d0e;">Atenção</span>
                </div>
                <p class="font-display text-2xl font-bold" style="color:#1a3d1f;">{{ $criticalStock->count() }}</p>
                <p class="text-xs mt-0.5" style="color:#8a9e8c;">Estoque baixo</p>
            </div>

            <div class="bg-white rounded-2xl p-4 border animate-fadeIn" style="border-color:#d4e8d6;animation-delay:0.12s;">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg" style="background:#fee2e2;">❌</div>
                    <span class="text-[10px] font-semibold uppercase tracking-widest px-2 py-1 rounded-full" style="background:#fee2e2;color:#b91c1c;">Crítico</span>
                </div>
                <p class="font-display text-2xl font-bold" style="color:#1a3d1f;">{{ $outOfStockProducts }}</p>
                <p class="text-xs mt-0.5" style="color:#8a9e8c;">Sem estoque</p>
            </div>   
            
            <div class="bg-white rounded-2xl p-4 border animate-fadeIn" style="border-color:#d4e8d6;animation-delay:0.18s;">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg" style="background:#eef7ef;">💰</div>
                </div>
                <p class="font-display text-2xl font-bold" style="color:#1a3d1f;">R$ {{ number_format($totalStockValue, 2, ',', '.')}}</p>
                <p class="text-xs mt-0.5" style="color:#8a9e8c;">Valor em estoque</p>
            </div>
        </div>

        <!--Table-->
        <div class="bg-white rounded-2xl border overflow-hidden animate-fadeIn" style="border-color:#d4e8d6;animation-delay:0.22s;">
            <!--Table header-->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-5 py-4 border-b" style="border-color:#d4e8d6;animation-delay:0.22s;">
                <div>
                    <h2 class="font-display font-bold text-base" style="color:#1a3d1f;">Lista de Produtos</h2>
                    <p class="text-xs" style="color:#8a9e8c;">{{ $totalProducts }} itens cadastrados</p>
                </div>

                <div class="flex items-center gap-3 flex-wrap justify-between">

                    <!--Search-->
                    <form action="{{ route('products.index') }}" method="GET" id="filter-form" class="flex flex-wrap items-center justify-end gap-3">
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm pointer-events-none" style="color:#8a9e8c;">🔍</span>
                            <input type="text" placeholder="Buscar produto..." id="search" name="search" value="{{ request('search') }}" onkeyup="fetchProducts()"
                                class="pl-8 pr-3 py-2 rounded-xl text-sm border transition-all duration-200 focus:ring-4"
                                style="border-color:#d4e8d6;background:#f9f6f0;color:#1a3d1f;"
                                onfocus="this.style.borderColor='#4caf50';this.style.background='#fff';this.style.boxShadow='0 0 0 4px rgba(76,175,80,0.1)'"
                                onblur="this.style.borderColor='#d4e8d6';this.style.background='#f9f6f0';this.style.boxShadow='none'"/>           
                        </div>

                        <div class="flex gap-2 flex-wrap">
                            <select onchange="fetchProducts()" name="category_id" class="pl-3 pr-8 py-2 rounded-xl text-sm border transition-all duration-200 appearance-none cursor-pointer"
                                    style="border-color:#d4e8d6;background:#f9f6f0;color:#4a5c4c;" >
                                <option value="">Todas as categorias</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id}}" {{ request('category_id') == $category->id ? 'selected' : ''}}>
                                        {{ $category->name}}
                                    </option>
                                @endforeach
                            </select>


                            <select onchange="fetchProducts()" name="stock_status" class="pl-3 pr-8 py-2 rounded-xl text-sm border transition-all duration-200 appearance-none cursor-pointer"
                                    style="border-color:#d4e8d6;background:#f9f6f0;color:#4a5c4c;">
                                <option value="">Todos os status</option>
                                <option value="Estoque Normal" {{ request('stock_status') == 'Estoque Normal' ? 'selected' : '' }}>Estoque Normal</option>
                                <option value="Estoque Baixo" {{ request('stock_status') == 'Estoque Baixo' ? 'selected' : '' }}>Estoque Baixo</option>
                                <option value="Em Falta" {{ request('stock_status') == 'Em Falta' ? 'selected' : '' }}>Em Falta</option>
                            </select>

                        
                            <a href="{{ route('products.create') }}" class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-semibold text-white transition-all duration-200 hover:-translate-y-px hover:shadow-lg"
                            style="background:#1a3d1f;"
                            onmouseover="this.style.background='#2d6a35'"
                            onmouseout="this.style.background='#1a3d1f'">
                                <span class="text-base leading-none">+</span>
                                Adicionar Produto
                            </a>
                        </div>        
                    </form>    
                </div>    
            </div>
            <div id="products-table">
                @include('products.partials.table', ['products' => $products])
            </div>
            </div> 
        </div>
    </div>
</div>

@push('scripts')
<script>

let timeout;
let controller;

function fetchProducts(url = null) {
    if (controller) controller.abort();
    controller = new AbortController();

    clearTimeout(timeout);

    timeout = setTimeout(() => {
        const form = document.getElementById('filter-form');
        const params = new URLSearchParams(new FormData(form));

        const finalUrl = url || `{{ route('products.index') }}?${params.toString()}`;

        fetch(finalUrl, {
            signal: controller.signal,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.text())
        .then(html => {
            document.getElementById('products-table').innerHTML = html;

            // Atualiza URL corretamente
            history.pushState({}, '', finalUrl);
        })
        .catch(err => {
            if (err.name !== 'AbortError') console.error(err);
        });

    }, 400);
}

// Busca
    document.getElementById('search').addEventListener('keyup', () => fetchProducts());

//  Selects
    document.querySelectorAll('#filter-form select').forEach(select => {
        select.addEventListener('change', () => fetchProducts());
    });

// Paginação AJAX
    document.addEventListener('click', function(e) {
        const link = e.target.closest('.pagination a');

        if (link) {
            e.preventDefault();
            fetchProducts(link.href);
        }
    });
</script>
@endpush

@endsection

