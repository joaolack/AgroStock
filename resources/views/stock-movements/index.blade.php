@extends('layouts.app')

@section('slot')
<div class="flex-1 flex flex-col min-h-screen overflow-hidden">

    <header class="sticky top-0 z-20 flex items-center justify-between px-6 py-3.5 border-b bg-white/80 backdrop-blur-md"
            style="border-color:#d4e8d6;">
        <div class="flex items-center gap-3">
            <div>
                <h1 class="font-display text-xl font-bold tracking-tight" style="color:#1a3df;">Movimentações do Estoque</h1>
                <p class="text-[11px]" style="color:#8a9e8c;"></p>
            </div>
        </div>

        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold text-white cursor-pointer"
                 style="background:linear-gradient(135deg,#4caf50,#2d6a35);">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
        </div>
    </header>

    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg mb-6">

        <div class="p-6">
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-4">
                Registrar Movimentação
            </h2>

            {{-- Mensagens de Erro --}}
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- FORMULÁRIO 1: Entrada --}}
                <div class="bg-green-50 dark:bg-green-900/20 p-6 rounded-lg border-2 border-green-200 dark:border-green-800">
                    <h3 class="text-lg font-semibold text-green-800 dark:text-green-200 mb-4 flex items-center gap-2">
                        ⬆️ Entrada de Estoque
                    </h3>

                    <form action="{{ route('stock-movements.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="entry">

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Produto *
                                </label>
                                <select name="product_id" required
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
                                    <option value="">Selecione um produto</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">
                                            {{ $product->name }} (Estoque: {{ $product->stock_quantity }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Quantidade *
                                </label>
                                <input type="number" name="quantity" min="1" required
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300"
                                    placeholder="Ex: 50">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Numero do Lote *
                                </label>
                                <input type="text" name="batch_number" required
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300"
                                    placeholder="Ex: LOTE-001">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Fornecedor *
                                </label>
                                <select name="supplier_id" required
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
                                    <option value="">Selecione um fornecedor</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Validade (opcional)
                                </label>
                                <input type="date" name="expiration_date"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
                            </div>
                            <button type="submit" 
                                    class="w-full px-4 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-all duration-200">
                                ✅ Registrar Entrada
                            </button>
                        </div>
                    </form>
                </div>

                {{-- FORMULÁRIO 2: Saída --}}
                <div class="bg-red-50 dark:bg-red-900/20 p-6 rounded-lg border-2 border-red-200 dark:border-red-800">
                    <h3 class="text-lg font-semibold text-red-800 dark:text-red-200 mb-4 flex items-center gap-2">
                        ⬇️ Saída de Estoque
                    </h3>

                    <form action="{{ route('stock-movements.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="exit">

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Produto *
                                </label>
                                <select name="product_id" required
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
                                    <option value="">Selecione um produto</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">
                                            {{ $product->name }} (Estoque: {{ $product->stock_quantity }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Quantidade *
                                </label>
                                <input type="number" name="quantity" min="1" required
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300"
                                    placeholder="Ex: 20">
                            </div>
                            <button type="submit" 
                                    class="w-full px-4 py-3 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition-all duration-200">
                                ✅ Registrar Saída
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

        {{-- Histórico de Movimentações --}}
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white dark:bg-gray-800">

                {{-- Filtros --}}
                <form method="GET" action="{{ route('stock-movements.index') }}" class="mb-6 p-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        {{-- Filtro por Produto --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Produto
                            </label>
                            <select name="product_id" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
                                <option value="">Todos os produtos</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Filtro por Tipo --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tipo
                            </label>
                            <select name="type" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
                                <option value="">Todos</option>
                                <option value="entry" {{ request('type') == 'entry' ? 'selected' : '' }}>Entrada</option>
                                <option value="exit" {{ request('type') == 'exit' ? 'selected' : '' }}>Saída</option>
                            </select>
                        </div>

                        {{-- Filtro por Data Inicial --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Data Inicial
                            </label>
                            <input type="date" name="date_from" value="{{ request('date_from') }}" 
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
                        </div>

                        {{-- Filtro por Data Final --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Data Final
                            </label>
                            <input type="date" name="date_to" value="{{ request('date_to') }}" 
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
                        </div>
                    </div>

                    <div class="flex gap-3 mt-4">
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                            🔍 Filtrar
                        </button>
                        <a href="{{ route('stock-movements.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                            Limpar
                        </a>
                    </div>
                </form>

                {{-- Tabela --}}
                <div class="bg-white rounded-xl border overflow-hidden" style="border-color:#d4e8d6;">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b" style="border-color:#d4e8d6;background:#f9f6f0;">
                                <th class="text-left px-6 py-3 text-[11px] font-semibold uppercase tracking-wider"
                                    style="color:#8a9e8c;">
                                    Data/Hora
                                </th>
                                <th class="text-left px-6 py-3 text-[11px] font-semibold uppercase tracking-wider"
                                    style="color:#8a9e8c;">
                                    Produto
                                </th>
                                <th class="text-left px-6 py-3 text-[11px] font-semibold uppercase tracking-wider"
                                    style="color:#8a9e8c;">
                                    Lote
                                </th>
                                <th class="text-left px-6 py-3 text-[11px] font-semibold uppercase tracking-wider"
                                    style="color:#8a9e8c;">
                                    Tipo
                                </th>
                                <th class="text-left px-6 py-3 text-[11px] font-semibold uppercase tracking-wider"
                                    style="color:#8a9e8c;">
                                    Motivo
                                </th>
                                <th class="text-left px-6 py-3 text-[11px] font-semibold uppercase tracking-wider"
                                    style="color:#8a9e8c;">
                                    Quantidade
                                </th>
                                <th class="text-left px-6 py-3 text-[11px] font-semibold uppercase tracking-wider"
                                    style="color:#8a9e8c;">
                                    Estoque
                                </th>
                                <th class="text-left px-6 py-3 text-[11px] font-semibold uppercase tracking-wider"
                                    style="color:#8a9e8c;">
                                    Usuário
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($movements as $movement)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                        {{ $movement->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $movement->product->name }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        @if($movement->productBatch)
                                            <div class="font-semibold">{{ $movement->productBatch->number }}</div>
                                            <div class="text-xs text-gray-500">{{ $movement->productBatch->supplier->name ?? 'Fornecedor N/A' }}</div>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($movement->type === 'entry')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                ⬆️ Entrada
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                ⬇️ Saída
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if(($movement->reason ?? 'manual') === 'expired')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                Vencimento
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                                Manual
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="text-lg font-semibold {{ $movement->type === 'entry' ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $movement->type === 'entry' ? '+' : '-' }}{{ $movement->quantity }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm text-gray-600 dark:text-gray-400">
                                        {{ $movement->previous_quantity }} → {{ $movement->new_quantity }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $movement->user->name }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                        Nenhuma movimentação encontrada.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Paginação --}}
                @if($movements->hasPages())
                    <div class="mt-6">
                        {{ $movements->appends(request()->query())->links() }}
                    </div>
                @endif

            </div>
    </div>
 @endsection   
