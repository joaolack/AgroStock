@extends('layouts.app')

@section('slot')
<div class="flex min-h-screen flex-1 flex-col overflow-hidden">
    <header class="sticky top-0 z-20 flex items-center justify-between border-b bg-white/80 px-5 py-3.5 backdrop-blur-md sm:px-6"
        style="border-color:#d4e8d6;">
        <div class="flex min-w-0 items-center gap-3">
            <x-mobile-menu-button />
            <div class="min-w-0">
                <h1 class="truncate font-display text-xl font-bold tracking-tight" style="color:#1a3d1f;">Produtos</h1>
                <p class="truncate text-[11px]" style="color:#8a9e8c;">Gerencie seu catálogo de insumos e produtos</p>
            </div>
        </div>

        <div class="flex items-center gap-2.5">
            <div class="flex h-8 w-8 cursor-pointer items-center justify-center rounded-full text-sm font-bold text-white"
                style="background:linear-gradient(135deg,#4caf50,#2d6a35);">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
        </div>
    </header>

    <div class="flex-1 space-y-5 overflow-y-auto p-4 sm:p-6">
        <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-2xl border bg-white p-5 shadow-sm" style="border-color:#d4e8d6;">
                <div class="flex items-center justify-between gap-3">
                    <p class="text-sm font-semibold" style="color:#4a5c4c;">Total de produtos</p>
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl" style="background:#eaf6e9;color:#2d6a35;">
                        <x-fas-box class="h-4 w-4"/>
                    </div>
                </div>
                <p class="mt-4 text-3xl font-bold tracking-tight" style="color:#1a3d1f;">{{ number_format($totalProducts, 0, ',', '.') }}</p>
                <p class="mt-1 text-xs" style="color:#8a9e8c;">Produtos cadastrados</p>
            </div>

            <div class="rounded-2xl border bg-white p-5 shadow-sm" style="border-color:#d4e8d6;">
                <div class="flex items-center justify-between gap-3">
                    <p class="text-sm font-semibold" style="color:#4a5c4c;">Estoque baixo</p>
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl" style="background:#fef3c7;color:#92400e;">
                        <x-fas-triangle-exclamation class="h-4 w-4" />
                    </div>
                </div>
                <p class="mt-4 text-3xl font-bold tracking-tight" style="color:#1a3d1f;">{{ number_format($lowStockProducts, 0, ',', '.') }}</p>
                <span class="mt-2 inline-flex rounded-full px-2.5 py-1 text-xs font-bold" style="background:#fffbeb;color:#92400e;">
                    Requer atenção
                </span>
            </div>

            <div class="rounded-2xl border bg-white p-5 shadow-sm" style="border-color:#d4e8d6;">
                <div class="flex items-center justify-between gap-3">
                    <p class="text-sm font-semibold" style="color:#4a5c4c;">Sem estoque</p>
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl" style="background:#fee2e2;color:#b91c1c;">
                        <x-fas-circle-xmark class="h-4 w-4" />
                    </div>
                </div>
                <p class="mt-4 text-3xl font-bold tracking-tight" style="color:#1a3d1f;">{{ number_format($outOfStockProducts, 0, ',', '.') }}</p>
                <span class="mt-2 inline-flex rounded-full px-2.5 py-1 text-xs font-bold" style="background:#fef2f2;color:#b91c1c;">
                    Crítico
                </span>
            </div>

            <div class="rounded-2xl border bg-white p-5 shadow-sm" style="border-color:#d4e8d6;">
                <div class="flex items-center justify-between gap-3">
                    <p class="text-sm font-semibold" style="color:#4a5c4c;">Valor em estoque</p>
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl" style="background:#eaf6e9;color:#2d6a35;">
                        <x-fas-dollar-sign class="h-4 w-4" />
                    </div>
                </div>
                <p class="mt-4 text-2xl font-bold tracking-tight sm:text-3xl" style="color:#1a3d1f;">R$ {{ number_format($totalStockValue, 2, ',', '.') }}</p>
                <p class="mt-1 text-xs" style="color:#8a9e8c;">Custo total armazenado</p>
            </div>
        </section>

        <section class="overflow-hidden rounded-2xl border bg-white shadow-sm"
            style="border-color:#d4e8d6;box-shadow:0 18px 45px rgba(26,61,31,0.06);">
            <div class="border-b px-5 py-5 sm:px-6"
                style="border-color:#d4e8d6;background:linear-gradient(135deg,#ffffff 0%,#f6fbf4 100%);">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                    <div class="max-w-2xl">
                        <h2 class="mt-3 font-display text-xl font-bold tracking-tight sm:text-2xl" style="color:#142f18;">
                            Lista de produtos
                        </h2>
                        <p class="mt-1 text-sm" style="color:#6e876f;">
                            {{ $products->total() }} {{ $products->total() === 1 ? 'item encontrado' : 'itens encontrados' }}
                            @if ($filters['search'] !== '')
                                para "{{ $filters['search'] }}"
                            @endif
                        </p>
                    </div>

                    <a href="{{ route('products.create') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-bold text-white shadow-sm transition-all duration-200 hover:-translate-y-px hover:shadow-lg"
                        style="background:#1a3d1f;"
                        onmouseover="this.style.background='#2d6a35'"
                        onmouseout="this.style.background='#1a3d1f'">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/>
                        </svg>
                        Adicionar produto
                    </a>
                </div>
            </div>

            <div class="px-5 py-4 sm:px-6">
                <form method="GET" action="{{ route('products.index', [], false) }}" class="space-y-4">
                    <div class="grid grid-cols-1 gap-3 lg:grid-cols-[minmax(0,1.4fr)_minmax(180px,0.7fr)_minmax(180px,0.7fr)]">
                        <div>
                            <label for="search" class="mb-1.5 block text-xs font-bold uppercase tracking-[0.14em]" style="color:#4a5c4c;">
                                Buscar produto
                            </label>
                            <div class="relative">
                                <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2" style="color:#8a9e8c;">
                                    <x-fas-magnifying-glass class="h-3.5 w-auto"/>
                                </span>
                                <input
                                    type="text"
                                    id="search"
                                    name="search"
                                    value="{{ $filters['search'] }}"
                                    placeholder="Nome, descrição, categoria ou fornecedor"
                                    class="h-11 w-full rounded-xl border py-2 pl-9 pr-3 text-sm transition-all duration-200 focus:ring-4"
                                    style="border-color:#d4e8d6;background:#f9f6f0;color:#1a3d1f;"
                                    onfocus="this.style.borderColor='#4caf50';this.style.background='#fff';this.style.boxShadow='0 0 0 4px rgba(76,175,80,0.1)'"
                                    onblur="this.style.borderColor='#d4e8d6';this.style.background='#f9f6f0';this.style.boxShadow='none'"
                                >
                            </div>
                        </div>

                        <div>
                            <label for="category_id" class="mb-1.5 block text-xs font-bold uppercase tracking-[0.14em]" style="color:#4a5c4c;">
                                Categoria
                            </label>
                            <select id="category_id" name="category_id"
                                class="h-11 w-full rounded-xl border px-3 text-sm transition-all focus:border-green-600 focus:ring-green-600"
                                style="border-color:#d4e8d6;background:#f9f6f0;color:#1a3d1f;">
                                <option value="">Todas</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" @selected((string) $filters['category_id'] === (string) $category->id)>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="stock_status" class="mb-1.5 block text-xs font-bold uppercase tracking-[0.14em]" style="color:#4a5c4c;">
                                Status
                            </label>
                            <select id="stock_status" name="stock_status"
                                class="h-11 w-full rounded-xl border px-3 text-sm transition-all focus:border-green-600 focus:ring-green-600"
                                style="border-color:#d4e8d6;background:#f9f6f0;color:#1a3d1f;">
                                <option value="">Todos</option>
                                <option value="Estoque Normal" @selected($filters['stock_status'] === 'Estoque Normal')>Estoque normal</option>
                                <option value="Crítico" @selected($filters['stock_status'] === 'Crítico')>Estoque crítico</option>
                                <option value="Estoque Baixo" @selected($filters['stock_status'] === 'Estoque Baixo')>Estoque baixo</option>
                                <option value="Em Falta" @selected($filters['stock_status'] === 'Em Falta')>Sem estoque</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                        <button type="submit"
                            class="inline-flex h-11 items-center justify-center gap-2 rounded-xl px-4 text-sm font-bold text-white transition-all duration-200 hover:-translate-y-px"
                            style="background:#2d6a35;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.3-4.3M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z"/>
                            </svg>
                            Filtrar
                        </button>
                        <a href="{{ route('products.index', [], false) }}"
                            class="inline-flex h-11 items-center justify-center gap-2 rounded-xl border px-4 text-sm font-bold transition-all duration-200 hover:bg-red-50"
                            style="border-color:#fecaca;color:#991b1b;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 6 6 18M6 6l12 12"/>
                            </svg>
                            Limpar
                        </a>
                    </div>

                    @if ($filters['search'] !== '' || $filters['category_id'] !== '' || $filters['stock_status'] !== '')
                        <div class="rounded-xl border px-3 py-2 text-xs" style="border-color:#d4e8d6;background:#f9f6f0;color:#4a5c4c;">
                            <span class="font-bold">Filtros ativos:</span>
                            @if ($filters['search'] !== '')
                                <span class="ml-1 font-semibold">busca "{{ $filters['search'] }}"</span>
                            @endif
                            @if ($filters['category_id'] !== '')
                                <span class="ml-1 font-semibold">categoria selecionada</span>
                            @endif
                            @if ($filters['stock_status'] !== '')
                                <span class="ml-1 font-semibold">status "{{ $filters['stock_status'] }}"</span>
                            @endif
                        </div>
                    @endif
                </form>
            </div>
        </section>

        @if ($products->isEmpty())
            <section class="rounded-2xl border bg-white px-5 py-12 text-center shadow-sm sm:px-6"
                style="border-color:#d4e8d6;">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl"
                    style="background:#eaf6e9;color:#2d6a35;">
                    <x-fas-box class="h-6 w-6"/>
                </div>

                <h2 class="mt-4 font-display text-xl font-bold" style="color:#1a3d1f;">
                    @if ($filters['search'] !== '' || $filters['category_id'] !== '' || $filters['stock_status'] !== '')
                        Nenhum produto encontrado
                    @else
                        Cadastre seu primeiro produto
                    @endif
                </h2>
                <p class="mx-auto mt-2 max-w-md text-sm leading-6" style="color:#6e876f;">
                    @if ($filters['search'] !== '' || $filters['category_id'] !== '' || $filters['stock_status'] !== '')
                        Ajuste os filtros ou limpe a busca para ver todos os produtos cadastrados.
                    @else
                        Produtos conectam categorias, fornecedores, lotes, estoque mínimo e relatórios do sistema.
                    @endif
                </p>

                <div class="mt-6 flex flex-col justify-center gap-2 sm:flex-row">
                    @if ($filters['search'] !== '' || $filters['category_id'] !== '' || $filters['stock_status'] !== '')
                        <a href="{{ route('products.index', [], false) }}"
                            class="inline-flex items-center justify-center gap-2 rounded-xl border px-4 py-2.5 text-sm font-bold transition-all duration-200 hover:bg-agro-pale"
                            style="border-color:#d4e8d6;color:#4a5c4c;">
                            Limpar filtros
                        </a>
                    @endif
                    <a href="{{ route('products.create') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-bold text-white transition-all duration-200 hover:-translate-y-px"
                        style="background:#1a3d1f;">
                        Cadastrar produto
                    </a>
                </div>
            </section>
        @else
            @include('products.partials.table', ['products' => $products])
        @endif
    </div>
</div>
@endsection
