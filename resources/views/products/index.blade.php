@extends('layouts.app')

@section('slot')
<div class="flex-1 flex flex-col min-h-screen overflow-hidden">

    <header class="sticky top-0 z-20 flex items-center justify-between px-6 py-3.5 border-b bg-white/80 backdrop-blur-md"
            style="border-color:#d4e8d6;">
        <div class="flex items-center gap-3">
            <button class="lg:hidden p-2 rounded-lg hover:bg-agro-pale transition colors" style="color:#4a5c4c;">
                Menu
            </button>
            <div>
                <h1 class="font-display text-xl font-bold tracking-tight" style="color:#1a3d1f;">Produtos</h1>
                <p class="text-[11px]" style="color:#8a9e8c;">Gerencie seu catalogo de insumos e produtos</p>
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

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">

            <div class="rounded-2xl p-5 border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-slate-500">Total de produtos</p>
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700">
                        <x-fas-box class="h-4 w-4"/>
                    </div>
                </div>

                <p class="mt-4 text-3xl font-bold tracking-tight text-slate-900">
                    {{ number_format($totalProducts, 0, ',', '.') }}
                </p>

                <p class="mt-1 text-xs text-slate-400">
                    Produtos cadastrados
                </p>
            </div>

            <div class="rounded-2xl p-5 border border-slate-200 bg-white shadow-sm">
                <div class="flex items-start justify-between">
                    <p class="text-sm font-medium text-slate-500">Estoque baixo</p>
                    
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-700">
                        <x-fas-triangle-exclamation class="h-4 w-4" />
                    </div>
                </div>
                <p class="mt-4 text-3xl font-bold tracking-tight text-slate-900">{{ $lowStockProducts }}</p>
                <div class="mt-2 inline-flex rounded-full bg-amber-50 px-2 py-0.5 text-xs font-medium text-amber-700">
                    Requer atenção
                </div>
            </div>

            <div class="rounded-2xl p-5 border border-slate-200 bg-white shadow-sm">
                <div class="flex items-start justify-between">
                    <p class="text-sm font-medium text-slate-500">Sem estoque</p>

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-red-50 text-red-700">
                        <x-fas-circle-xmark class="h-4 w-4" />
                    </div>
                </div>
                <p class="mt-4 text-3xl font-bold tracking-tight text-slate-900">{{ $outOfStockProducts }}</p>
                <div class="mt-2 inline-flex rounded-full bg-amber-50 px-2 py-0.5 text-xs font-medium text-red-700">
                    Crítico
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between">
                    <p class="text-sm font-medium text-slate-500">Valor em estoque</p>

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700">
                        <x-fas-dollar-sign class="h-4 w-4" />
                    </div>
                </div>
                <p class="mt-4 text-3xl font-bold tracking-tight text-slate-900">R$ {{ number_format($totalStockValue, 2, ',', '.') }}</p>
                <p class="mt-1 text-xs text-slate-400">Custo total armazenado</p>
            </div>
        </div>

        <form method="GET" action="{{ route('products.index', [], false) }}" class="bg-white rounded-2xl border p-4" style="border-color:#d4e8d6;">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <div class="md:col-span-2">
                    <label for="search" class="block text-xs font-semibold uppercase tracking-wide mb-1" style="color:#6e876f;">Busca</label>
                    <input
                        type="text"
                        id="search"
                        name="search"
                        value="{{ $filters['search'] }}"
                        placeholder="Buscar produto"
                        class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 text-sm"
                    >
                </div>

                <div>
                    <label for="category_id" class="block text-xs font-semibold uppercase tracking-wide mb-1" style="color:#6e876f;">Categoria</label>
                    <select id="category_id" name="category_id" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 text-sm">
                        <option value="">Todas</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected((string) $filters['category_id'] === (string) $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="stock_status" class="block text-xs font-semibold uppercase tracking-wide mb-1" style="color:#6e876f;">Status</label>
                    <select id="stock_status" name="stock_status" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 text-sm">
                        <option value="">Todos</option>
                        <option value="Estoque Normal" @selected($filters['stock_status'] === 'Estoque Normal')>Estoque normal</option>
                        <option value="Crítico" @selected($filters['stock_status'] === 'Crítico')>Estoque crítico</option>
                        <option value="Estoque Baixo" @selected($filters['stock_status'] === 'Estoque Baixo')>Estoque baixo</option>
                        <option value="Em Falta" @selected($filters['stock_status'] === 'Em Falta')>Sem estoque</option>
                    </select>
                </div>
            </div>

            <div class="mt-3 flex items-center gap-2">
                <button type="submit" class="px-4 py-2 rounded-lg text-sm font-semibold text-white" style="background:#2d6a35;">
                    Filtrar
                </button>
                <a href="{{ route('products.index', [], false) }}" class="px-4 py-2 rounded-lg text-sm font-semibold border" style="border-color:#d4e8d6;color:#4a5c4c;">
                    Limpar
                </a>
            </div>

            @if ($filters['search'] !== '' || $filters['category_id'] !== '' || $filters['stock_status'] !== '')
                <div class="mt-3 rounded-lg border px-3 py-2 text-xs" style="border-color:#d4e8d6;background:#f9f6f0;color:#4a5c4c;">
                    Filtros ativos:
                    @if ($filters['search'] !== '')
                        <span class="font-semibold">busca "{{ $filters['search'] }}"</span>
                    @endif
                    @if ($filters['category_id'] !== '')
                        <span class="font-semibold">categoria selecionada</span>
                    @endif
                    @if ($filters['stock_status'] !== '')
                        <span class="font-semibold">status "{{ $filters['stock_status'] }}"</span>
                    @endif
                </div>
            @endif
        </form>

        <div class="bg-white rounded-2xl border overflow-hidden animate-fadeIn" style="border-color:#d4e8d6;animation-delay:0.22s;">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-5 py-4 border-b" style="border-color:#d4e8d6;">
                <div>
                    <h2 class="font-display font-bold text-base" style="color:#1a3d1f;">Lista de Produtos</h2>
                    <p class="text-xs" style="color:#8a9e8c;">
                        {{ $products->total() }} {{ $products->total() === 1 ? 'item encontrado' : 'itens encontrados' }}
                        @if ($filters['search'] !== '')
                            para "{{ $filters['search'] }}"
                        @endif
                    </p>
                </div>

                <a href="{{ route('products.create') }}" class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-semibold text-white transition-all duration-200 hover:-translate-y-px hover:shadow-lg"
                   style="background:#1a3d1f;"
                   onmouseover="this.style.background='#2d6a35'"
                   onmouseout="this.style.background='#1a3d1f'">
                    <span class="text-base leading-none">+</span>
                    Adicionar Produto
                </a>
            </div>

            @include('products.partials.table', ['products' => $products])
        </div>
    </div>
</div>
@endsection
