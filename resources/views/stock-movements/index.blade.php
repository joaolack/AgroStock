@extends('layouts.app')

@section('slot')
@php
    $showEntryErrors = old('type') === 'entry';
    $showExitErrors = old('type') === 'exit';
    $displayTimezone = config('app.display_timezone');
@endphp

<div class="flex-1 flex flex-col min-h-screen overflow-hidden">

    <header class="sticky top-0 z-20 flex items-center justify-between px-6 py-3.5 mb-6 border-b bg-white/80 backdrop-blur-md"
            style="border-color:#d4e8d6;">
        <div class="flex items-center gap-3">
            <x-mobile-menu-button />
            <div>
                <h1 class="font-display text-xl font-bold tracking-tight" style="color:#1a3d1f;">Movimentações do Estoque</h1>
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

    <div class="bg-white shadow-sm sm:rounded-lg mb-6">

        <div class="p-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">
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
                <div class="relative overflow-hidden rounded-lg border bg-white p-6 shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md"
                     style="border-color:#d4e8d6;">
                    <div class="absolute inset-x-0 top-0 h-1" style="background:#2d6a35;"></div>
                    <div class="mb-5 flex items-center justify-between gap-4">
                        <h3 class="flex items-center gap-3 text-lg font-semibold text-gray-900">
                            <span class="flex h-10 w-10 items-center justify-center rounded-lg border bg-white shadow-sm" style="border-color:#d4e8d6;color:#2d6a35;">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                   <path d="M12 19V5m0 0 6 6m-6-6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                            Entrada de Estoque
                        </h3>
                        <span class="text-xs font-semibold uppercase tracking-wider" style="color:#8a9e8c;">Recebimento</span>
                    </div>

                    <form action="{{ route('stock-movements.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="entry">

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Produto *
                                </label>
                                <select name="product_id" required
                                        class="w-full rounded-lg border-gray-300 focus:border-[#2d6a35] focus:ring-[#2d6a35] {{ $showEntryErrors && $errors->has('product_id') ? 'border-red-500' : '' }}">
                                    <option value="">Selecione um produto</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" @selected($showEntryErrors && old('product_id') == $product->id)>
                                            {{ $product->name }} (Estoque: {{ $product->stock_quantity }})
                                        </option>
                                    @endforeach
                                </select>
                                @if($showEntryErrors)
                                    @error('product_id') <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                                @endif
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Quantidade *
                                </label>
                                <input type="number" name="quantity" min="1" required
                                    value="{{ $showEntryErrors ? old('quantity') : '' }}"
                                    class="w-full rounded-lg border-gray-300 focus:border-[#2d6a35] focus:ring-[#2d6a35] {{ $showEntryErrors && $errors->has('quantity') ? 'border-red-500' : '' }}"
                                    placeholder="Ex: 50">
                                @if($showEntryErrors)
                                    @error('quantity') <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                                @endif
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Numero do Lote *
                                </label>
                                <input type="text" name="batch_number" required
                                    value="{{ $showEntryErrors ? old('batch_number') : '' }}"
                                    class="w-full rounded-lg border-gray-300 focus:border-[#2d6a35] focus:ring-[#2d6a35] {{ $showEntryErrors && $errors->has('batch_number') ? 'border-red-500' : '' }}"
                                    placeholder="Ex: LOTE-001">
                                @if($showEntryErrors)
                                    @error('batch_number') <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                                @endif
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Fornecedor *
                                </label>
                                <select name="supplier_id" required
                                        class="w-full rounded-lg border-gray-300 focus:border-[#2d6a35] focus:ring-[#2d6a35] {{ $showEntryErrors && $errors->has('supplier_id') ? 'border-red-500' : '' }}">
                                    <option value="">Selecione um fornecedor</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" @selected($showEntryErrors && old('supplier_id') == $supplier->id)>{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                                @if($showEntryErrors)
                                    @error('supplier_id') <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                                @endif
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Validade (opcional)
                                </label>
                                <input type="date" name="expiration_date"
                                    value="{{ $showEntryErrors ? old('expiration_date') : '' }}"
                                    class="w-full rounded-lg border-gray-300 focus:border-[#2d6a35] focus:ring-[#2d6a35] {{ $showEntryErrors && $errors->has('expiration_date') ? 'border-red-500' : '' }}">
                                @if($showEntryErrors)
                                    @error('expiration_date') <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                                @endif
                            </div>
                            <button type="submit" 
                                    class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-[#1a3d1f] px-4 py-3 font-semibold text-white shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:bg-[#2d6a35] focus:outline-none focus:ring-2 focus:ring-[#2d6a35] focus:ring-offset-2">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M12 19V5m0 0 6 6m-6-6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                Registrar Entrada
                            </button>
                        </div>
                    </form>
                </div>

                {{-- FORMULÁRIO 2: Saída --}}
                <div class="relative overflow-hidden rounded-lg border bg-white p-6 shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md"
                     style="border-color:#d4e8d6;">
                    <div class="absolute inset-x-0 top-0 h-1" style="background:#8a9e8c;"></div>
                    <div class="mb-5 flex items-center justify-between gap-4">
                        <h3 class="flex items-center gap-3 text-lg font-semibold text-gray-900">
                            <span class="flex h-10 w-10 items-center justify-center rounded-lg border bg-white shadow-sm" style="border-color:#d4e8d6;color:#6f7f71;">
                                 <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M12 5v14m0 0 6-6m-6 6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                            Saída de Estoque
                        </h3>
                        <span class="text-xs font-semibold uppercase tracking-wider" style="color:#8a9e8c;">Retirada</span>
                    </div>

                    <form action="{{ route('stock-movements.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="exit">

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Produto *
                                </label>
                                <select name="product_id" required
                                        class="w-full rounded-lg border-gray-300 focus:border-[#2d6a35] focus:ring-[#2d6a35] {{ $showExitErrors && $errors->has('product_id') ? 'border-red-500' : '' }}">
                                    <option value="">Selecione um produto</option>
                                    @foreach($products as $product)
                                        @php
                                            $availableForExit = $product->availableBatches->sum('quantity');
                                        @endphp
                                        <option value="{{ $product->id }}" @selected($showExitErrors && old('product_id') == $product->id)>
                                            {{ $product->name }} (Disponivel para saida: {{ $availableForExit }})
                                        </option>
                                    @endforeach
                                </select>
                                @if($showExitErrors)
                                    @error('product_id') <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                                @endif
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Quantidade *
                                </label>
                                <input type="number" name="quantity" min="1" required
                                    value="{{ $showExitErrors ? old('quantity') : '' }}"
                                    class="w-full rounded-lg border-gray-300 focus:border-[#2d6a35] focus:ring-[#2d6a35] {{ $showExitErrors && $errors->has('quantity') ? 'border-red-500' : '' }}"
                                    placeholder="Ex: 20">
                                @if($showExitErrors)
                                    @error('quantity') <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                                @endif
                            </div>
                            <button type="submit" 
                                    class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-[#1a3d1f] px-4 py-3 font-semibold text-white shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:bg-[#2d6a35] focus:outline-none focus:ring-2 focus:ring-[#2d6a35] focus:ring-offset-2">
                                 <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M12 5v14m0 0 6-6m-6 6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                Registrar Saída
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

        {{-- Histórico de Movimentações --}}
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white">

                {{-- Filtros --}}
                <section class="mb-6 overflow-hidden rounded-2xl border bg-white shadow-sm"
                    style="border-color:#d4e8d6;box-shadow:0 18px 45px rgba(26,61,31,0.06);">
                    <div class="border-b px-5 py-5 sm:px-6"
                        style="border-color:#d4e8d6;background:linear-gradient(135deg,#ffffff 0%,#f6fbf4 100%);">
                        <div>
                            <h2 class="mt-3 font-display text-xl font-bold tracking-tight" style="color:#142f18;">
                                Filtrar movimentações
                            </h2>
                            <p class="mt-1 text-sm" style="color:#6e876f;">
                                Refine o histórico por produto, tipo de operação e período.
                            </p>
                        </div>
                    </div>

                    <form method="GET" action="{{ route('stock-movements.index') }}" class="px-5 py-4 sm:px-6">
                        <div class="grid grid-cols-1 gap-3 lg:grid-cols-[minmax(0,1.2fr)_minmax(170px,0.6fr)_minmax(160px,0.6fr)_minmax(160px,0.6fr)]">
                            <div>
                                <label for="product_id" class="mb-1.5 block text-xs font-bold uppercase tracking-[0.14em]" style="color:#4a5c4c;">
                                    Produto
                                </label>
                                <select id="product_id" name="product_id"
                                    class="h-11 w-full rounded-xl border px-3 text-sm transition-all focus:border-green-600 focus:ring-green-600"
                                    style="border-color:#d4e8d6;background:#f9f6f0;color:#1a3d1f;">
                                    <option value="">Todos os produtos</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="type" class="mb-1.5 block text-xs font-bold uppercase tracking-[0.14em]" style="color:#4a5c4c;">
                                    Tipo
                                </label>
                                <select id="type" name="type"
                                    class="h-11 w-full rounded-xl border px-3 text-sm transition-all focus:border-green-600 focus:ring-green-600"
                                    style="border-color:#d4e8d6;background:#f9f6f0;color:#1a3d1f;">
                                    <option value="">Todos</option>
                                    <option value="entry" {{ request('type') == 'entry' ? 'selected' : '' }}>Entrada</option>
                                    <option value="exit" {{ request('type') == 'exit' ? 'selected' : '' }}>Saída</option>
                                </select>
                            </div>

                            <div>
                                <label for="date_from" class="mb-1.5 block text-xs font-bold uppercase tracking-[0.14em]" style="color:#4a5c4c;">
                                    Data inicial
                                </label>
                                <input id="date_from" type="date" name="date_from" value="{{ request('date_from') }}"
                                    class="h-11 w-full rounded-xl border px-3 text-sm transition-all focus:border-green-600 focus:ring-green-600"
                                    style="border-color:#d4e8d6;background:#f9f6f0;color:#1a3d1f;">
                            </div>

                            <div>
                                <label for="date_to" class="mb-1.5 block text-xs font-bold uppercase tracking-[0.14em]" style="color:#4a5c4c;">
                                    Data final
                                </label>
                                <input id="date_to" type="date" name="date_to" value="{{ request('date_to') }}"
                                    class="h-11 w-full rounded-xl border px-3 text-sm transition-all focus:border-green-600 focus:ring-green-600"
                                    style="border-color:#d4e8d6;background:#f9f6f0;color:#1a3d1f;">
                            </div>
                        </div>

                        <div class="mt-4 flex flex-col gap-2 sm:flex-row sm:items-center">
                            <button type="submit"
                                class="inline-flex h-11 items-center justify-center gap-2 rounded-xl px-4 text-sm font-bold text-white transition-all duration-200 hover:-translate-y-px"
                                style="background:#2d6a35;">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.3-4.3M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z"/>
                                </svg>
                                Filtrar
                            </button>
                            <a href="{{ route('stock-movements.index') }}"
                                class="inline-flex h-11 items-center justify-center gap-2 rounded-xl border px-4 text-sm font-bold transition-all duration-200 hover:bg-red-50"
                                style="border-color:#fecaca;color:#991b1b;">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 6 6 18M6 6l12 12"/>
                                </svg>
                                Limpar
                            </a>
                        </div>

                        @if (request('product_id') || request('type') || request('date_from') || request('date_to'))
                            <div class="mt-4 rounded-xl border px-3 py-2 text-xs" style="border-color:#d4e8d6;background:#f9f6f0;color:#4a5c4c;">
                                <span class="font-bold">Filtros ativos:</span>
                                @if (request('product_id'))
                                    <span class="ml-1 font-semibold">produto selecionado</span>
                                @endif
                                @if (request('type'))
                                    <span class="ml-1 font-semibold">tipo "{{ request('type') === 'entry' ? 'Entrada' : 'Saída' }}"</span>
                                @endif
                                @if (request('date_from'))
                                    <span class="ml-1 font-semibold">início {{ \Carbon\Carbon::parse(request('date_from'))->format('d/m/Y') }}</span>
                                @endif
                                @if (request('date_to'))
                                    <span class="ml-1 font-semibold">fim {{ \Carbon\Carbon::parse(request('date_to'))->format('d/m/Y') }}</span>
                                @endif
                            </div>
                        @endif
                    </form>
                </section>

                {{-- Tabela / Cards --}}
                <div class="hidden bg-white rounded-xl border overflow-hidden xl:block" style="border-color:#d4e8d6;">
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
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($movements as $movement)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $movement->created_at->timezone($displayTimezone)->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $movement->product->name }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        @if($movement->productBatch)
                                            <div class="font-semibold">{{ $movement->productBatch->number }}</div>
                                            <div class="text-xs text-gray-500">{{ $movement->productBatch->supplier->name ?? 'Fornecedor N/A' }}</div>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($movement->type === 'entry')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Entrada
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Saída
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
                                    <td class="px-6 py-4 text-center text-sm text-gray-600">
                                        {{ $movement->previous_quantity }} → {{ $movement->new_quantity }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $movement->user->name }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                        Nenhuma movimentação encontrada.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="grid gap-3 xl:hidden">
                    @forelse($movements as $movement)
                        @php
                            $typeTone = $movement->type === 'entry'
                                ? ['background:#dcfce7;color:#166534;', 'background:#22c55e;', '+', 'Entrada']
                                : ['background:#fef2f2;color:#b91c1c;', 'background:#ef4444;', '-', 'Saída'];
                            $reasonTone = ($movement->reason ?? 'manual') === 'expired'
                                ? ['background:#fff7ed;color:#c2410c;', 'Vencimento']
                                : ['background:#f8fafc;color:#475569;', 'Manual'];
                        @endphp

                        <article class="rounded-2xl border bg-white p-4 shadow-sm" style="border-color:#d4e8d6;">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <h2 class="break-words text-base font-bold" style="color:#1a3d1f;">{{ $movement->product->name }}</h2>
                                    <p class="mt-1 text-xs" style="color:#8a9e8c;">
                                        {{ $movement->created_at->timezone($displayTimezone)->format('d/m/Y H:i') }} · {{ $movement->user->name }}
                                    </p>
                                </div>
                                <span class="inline-flex shrink-0 items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold" style="{{ $typeTone[0] }}">
                                    <span class="h-1.5 w-1.5 rounded-full" style="{{ $typeTone[1] }}"></span>
                                    {{ $typeTone[3] }}
                                </span>
                            </div>

                            <div class="mt-4 grid grid-cols-2 gap-3 text-sm">
                                <div class="rounded-xl border px-3 py-2" style="border-color:#edf4ee;background:#fbfdfb;">
                                    <p class="text-[10px] font-bold uppercase tracking-[0.14em]" style="color:#8a9e8c;">Quantidade</p>
                                    <p class="mt-1 text-lg font-bold {{ $movement->type === 'entry' ? 'text-green-700' : 'text-red-600' }}">
                                        {{ $typeTone[2] }}{{ $movement->quantity }}
                                    </p>
                                </div>

                                <div class="rounded-xl border px-3 py-2" style="border-color:#edf4ee;background:#fbfdfb;">
                                    <p class="text-[10px] font-bold uppercase tracking-[0.14em]" style="color:#8a9e8c;">Estoque</p>
                                    <p class="mt-1 font-bold" style="color:#1a3d1f;">{{ $movement->previous_quantity }} -> {{ $movement->new_quantity }}</p>
                                </div>
                            </div>

                            <div class="mt-3 flex flex-wrap gap-2">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold" style="{{ $reasonTone[0] }}">
                                    {{ $reasonTone[1] }}
                                </span>
                                @if($movement->productBatch)
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold" style="background:#eef7ef;color:#2d6a35;">
                                        Lote {{ $movement->productBatch->number }}
                                    </span>
                                @else
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold" style="background:#f8fafc;color:#64748b;">
                                        Sem lote
                                    </span>
                                @endif
                            </div>

                            @if($movement->productBatch)
                                <div class="mt-4 border-t pt-3 text-sm" style="border-color:#edf4ee;color:#4a5c4c;">
                                    <span class="font-bold">Fornecedor:</span>
                                    {{ $movement->productBatch->supplier->name ?? 'Fornecedor N/A' }}
                                </div>
                            @endif
                        </article>
                    @empty
                        <div class="rounded-2xl border border-dashed bg-white px-4 py-10 text-center text-sm" style="border-color:#d4e8d6;color:#8a9e8c;">
                            Nenhuma movimentação encontrada.
                        </div>
                    @endforelse
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
