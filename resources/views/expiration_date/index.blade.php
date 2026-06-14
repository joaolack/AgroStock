@extends('layouts.app')

@section('slot')
<div class="flex min-h-screen flex-1 flex-col overflow-hidden">
    <header class="sticky top-0 z-20 flex items-center justify-between border-b bg-white/80 px-5 py-3.5 backdrop-blur-md sm:px-6"
        style="border-color:#d4e8d6;">
        <div class="flex min-w-0 items-center gap-3">
            <x-mobile-menu-button />
            <div class="min-w-0">
                <h1 class="truncate font-display text-xl font-bold tracking-tight" style="color:#1a3d1f;">Validades</h1>
                <p class="truncate text-[11px]" style="color:#8a9e8c;">Controle a validade de seus produtos</p>
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
        <section class="overflow-hidden rounded-2xl border bg-white shadow-sm"
            style="border-color:#d4e8d6;box-shadow:0 18px 45px rgba(26,61,31,0.06);">
            <div class="border-b px-5 py-5 sm:px-6"
                style="border-color:#d4e8d6;background:linear-gradient(135deg,#ffffff 0%,#f6fbf4 100%);">
                <div>
                    <h2 class="mt-3 font-display text-xl font-bold tracking-tight" style="color:#142f18;">Filtrar validades</h2>
                    <p class="mt-1 text-sm" style="color:#6e876f;">Visualize produtos e lotes por urgência, fornecedor e estoque disponível.</p>
                </div>
            </div>

            <form method="GET" action="{{ route('expiration-date.index') }}" class="px-5 py-4 sm:px-6">
                <div class="grid grid-cols-1 gap-3 lg:grid-cols-[minmax(220px,1.2fr)_minmax(150px,0.7fr)_minmax(150px,0.7fr)_minmax(180px,0.9fr)] xl:grid-cols-[minmax(260px,1.35fr)_minmax(160px,0.7fr)_minmax(160px,0.7fr)_minmax(210px,0.95fr)_auto] xl:items-end">
                    <div>
                        <label for="search" class="mb-1.5 block text-xs font-bold uppercase tracking-[0.14em]" style="color:#4a5c4c;">Busca</label>
                        <input
                            type="text"
                            id="search"
                            name="search"
                            value="{{ $filters['search'] }}"
                            placeholder="Produto ou fornecedor"
                            class="h-11 w-full rounded-xl border px-3 text-sm transition-all focus:border-green-600 focus:ring-green-600"
                            style="border-color:#d4e8d6;background:#f9f6f0;color:#1a3d1f;"
                        >
                    </div>

                    <div>
                        <label for="view" class="mb-1.5 block text-xs font-bold uppercase tracking-[0.14em]" style="color:#4a5c4c;">Visualização</label>
                        <select id="view" name="view"
                            class="h-11 w-full rounded-xl border px-3 text-sm transition-all focus:border-green-600 focus:ring-green-600"
                            style="border-color:#d4e8d6;background:#f9f6f0;color:#1a3d1f;">
                            <option value="product" @selected($viewMode === 'product')>Por produto</option>
                            <option value="batch" @selected($viewMode === 'batch')>Por lote</option>
                        </select>
                    </div>

                    <div>
                        <label for="status" class="mb-1.5 block text-xs font-bold uppercase tracking-[0.14em]" style="color:#4a5c4c;">Status</label>
                        <select id="status" name="status"
                            class="h-11 w-full rounded-xl border px-3 text-sm transition-all focus:border-green-600 focus:ring-green-600"
                            style="border-color:#d4e8d6;background:#f9f6f0;color:#1a3d1f;">
                            <option value="">Todos</option>
                            <option value="Vencido" @selected($filters['status'] === 'Vencido')>Vencido</option>
                            <option value="Vence em breve" @selected($filters['status'] === 'Vence em breve')>Vence em breve</option>
                            <option value="Seguro" @selected($filters['status'] === 'Seguro')>Seguro</option>
                        </select>
                    </div>

                    <div>
                        <label for="supplier_id" class="mb-1.5 block text-xs font-bold uppercase tracking-[0.14em]" style="color:#4a5c4c;">Fornecedor</label>
                        <select id="supplier_id" name="supplier_id"
                            class="h-11 w-full rounded-xl border px-3 text-sm transition-all focus:border-green-600 focus:ring-green-600"
                            style="border-color:#d4e8d6;background:#f9f6f0;color:#1a3d1f;">
                            <option value="">Todos</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" @selected($filters['supplier_id'] === (string) $supplier->id)>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <label class="flex h-11 items-center gap-2 rounded-xl border px-3 text-sm font-semibold xl:min-w-44"
                        style="border-color:#d4e8d6;background:#fbfdfb;color:#4a5c4c;">
                        <input type="checkbox" name="stock_only" value="1"
                            class="rounded border-gray-300 text-green-600 focus:ring-green-500"
                            @checked($filters['stock_only'])>
                        Com estoque
                    </label>
                </div>

                <div class="mt-4 flex flex-col gap-2 sm:flex-row sm:items-center">
                    <button type="submit"
                        class="inline-flex h-11 items-center justify-center gap-2 rounded-xl px-4 text-sm font-bold text-white transition-all duration-200 hover:-translate-y-px"
                        style="background:#2d6a35;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35"/>
                            <circle cx="11" cy="11" r="7"/>
                        </svg>
                        Filtrar
                    </button>
                    <a href="{{ route('expiration-date.index') }}"
                        class="inline-flex h-11 items-center justify-center rounded-xl border px-4 text-sm font-bold transition-all duration-200 hover:bg-green-50"
                        style="border-color:#d4e8d6;color:#4a5c4c;">
                        Limpar
                    </a>
                </div>
            </form>
        </section>

        @if (session('success'))
            <div class="rounded-2xl border px-4 py-3 text-sm font-semibold" style="border-color:#bbf7d0;background:#f0fdf4;color:#166534;">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-2xl border px-4 py-3 text-sm" style="border-color:#fecaca;background:#fef2f2;color:#b91c1c;">
                <ul class="list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="grid grid-cols-1 gap-3 md:grid-cols-3">
            <div class="overflow-hidden rounded-2xl border bg-white p-5 shadow-sm" style="border-color:#fecaca;">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-sm font-bold" style="color:#991b1b;">Vencidos</p>
                        <p class="mt-3 text-3xl font-bold tracking-tight" style="color:#b91c1c;">{{ $summary['expired'] }}</p>
                    </div>
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl" style="background:#fee2e2;color:#b91c1c;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 17h.01"/>
                        </svg>
                    </span>
                </div>
                <p class="mt-2 text-xs" style="color:#b91c1c;">Itens que exigem ação imediata</p>
            </div>

            <div class="overflow-hidden rounded-2xl border bg-white p-5 shadow-sm" style="border-color:#fde68a;">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-sm font-bold" style="color:#92400e;">Vence em 60 dias</p>
                        <p class="mt-3 text-3xl font-bold tracking-tight" style="color:#b45309;">{{ $summary['soon'] }}</p>
                    </div>
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl" style="background:#fef3c7;color:#b45309;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="9"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2"/>
                        </svg>
                    </span>
                </div>
                <p class="mt-2 text-xs" style="color:#92400e;">Itens próximos do vencimento</p>
            </div>

            <div class="overflow-hidden rounded-2xl border bg-white p-5 shadow-sm" style="border-color:#bbf7d0;">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-sm font-bold" style="color:#166534;">Seguros</p>
                        <p class="mt-3 text-3xl font-bold tracking-tight" style="color:#166534;">{{ $summary['safe'] }}</p>
                    </div>
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl" style="background:#dcfce7;color:#166534;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 6 9 17l-5-5"/>
                        </svg>
                    </span>
                </div>
                <p class="mt-2 text-xs" style="color:#166534;">Itens fora da janela crítica</p>
            </div>
        </section>

        <section class="hidden overflow-hidden rounded-2xl border bg-white shadow-sm xl:block" style="border-color:#d4e8d6;">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b" style="border-color:#d4e8d6;background:#f9f6f0;">
                            <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-[0.16em]" style="color:#8a9e8c;">Produto</th>
                            @if ($viewMode === 'batch')
                                <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-[0.16em]" style="color:#8a9e8c;">Lote</th>
                                <th class="px-4 py-3 text-right text-[11px] font-bold uppercase tracking-[0.16em]" style="color:#8a9e8c;">Qtd</th>
                            @endif
                            <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-[0.16em]" style="color:#8a9e8c;">Categoria</th>
                            <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-[0.16em]" style="color:#8a9e8c;">Fornecedor</th>
                            <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-[0.16em]" style="color:#8a9e8c;">Validade</th>
                            <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-[0.16em]" style="color:#8a9e8c;">Status</th>
                            <th class="px-4 py-3 text-right text-[11px] font-bold uppercase tracking-[0.16em]" style="color:#8a9e8c;">Ação</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse ($items as $item)
                            @php
                                $batch = $item['batch'] ?? null;
                                $writeOffMaxQuantity = $batch ? min((int) $batch->quantity, (int) $item['product']->stock_quantity) : 0;
                                $canWriteOff = $batch && $item['status'] === 'Vencido' && $writeOffMaxQuantity > 0;
                                $statusTone = match ($item['status']) {
                                    'Vencido' => 'border-left:4px solid #dc2626;',
                                    'Vence em breve' => 'border-left:4px solid #f59e0b;',
                                    default => 'border-left:4px solid #22c55e;',
                                };
                            @endphp
                            <tr class="transition-colors hover:bg-[#fbfdfb]" style="{{ $statusTone }}">
                                <td class="px-4 py-4">
                                    <p class="font-bold" style="color:#1a3d1f;">{{ $item['product']->name }}</p>
                                </td>
                                @if ($viewMode === 'batch')
                                    <td class="px-4 py-4" style="color:#4a5c4c;">{{ $item['batch']->number ?? 'N/A' }}</td>
                                    <td class="px-4 py-4 text-right font-semibold" style="color:#1a3d1f;">{{ $item['batch']->quantity ?? 0 }}</td>
                                @endif
                                <td class="px-4 py-4" style="color:#4a5c4c;">{{ $item['product']->category->name ?? 'N/A' }}</td>
                                <td class="px-4 py-4" style="color:#4a5c4c;">{{ $item['supplier']->name ?? $item['product']->supplier->name ?? 'N/A' }}</td>
                                <td class="px-4 py-4 font-semibold" style="color:#1a3d1f;">{{ $item['expiration_date']->format('d/m/Y') }}</td>
                                <td class="px-4 py-4">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="inline-flex items-center rounded-full border px-2.5 py-1 text-xs font-bold {{ $item['status_class'] }}">
                                            {{ $item['status'] }}
                                        </span>

                                        @if ($item['days_to_expire'] < 0)
                                            <span class="text-xs font-semibold text-red-600">Há {{ abs($item['days_to_expire']) }} dias</span>
                                        @elseif ($item['days_to_expire'] === 0)
                                            <span class="text-xs font-semibold text-red-600">Vence hoje</span>
                                        @elseif ($item['days_to_expire'] <= 60)
                                            <span class="text-xs font-semibold text-yellow-700">Em {{ $item['days_to_expire'] }} dias</span>
                                        @else
                                            <span class="text-xs font-semibold text-green-700">Em {{ $item['days_to_expire'] }} dias</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex justify-end">
                                        @if ($canWriteOff)
                                            <form
                                                method="POST"
                                                action="{{ route('expiration-date.batches.write-off', $batch) }}"
                                                class="flex items-center justify-end gap-2"
                                                onsubmit="return confirm('Confirmar baixa de produto vencido neste lote?')"
                                            >
                                                @csrf
                                                <input
                                                    type="number"
                                                    name="quantity"
                                                    min="1"
                                                    max="{{ $writeOffMaxQuantity }}"
                                                    value="{{ $writeOffMaxQuantity }}"
                                                    class="h-10 w-20 rounded-xl border-gray-300 text-sm focus:border-red-500 focus:ring-red-500"
                                                >
                                                <button type="submit"
                                                    class="inline-flex h-10 items-center justify-center rounded-xl bg-red-600 px-3 text-xs font-bold text-white transition hover:bg-red-700">
                                                    Dar baixa
                                                </button>
                                            </form>
                                        @elseif (($item['status'] ?? '') === 'Vencido')
                                            <span class="text-xs font-semibold text-gray-500">Sem estoque</span>
                                        @else
                                            <span class="text-xs text-gray-400">-</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $viewMode === 'batch' ? 8 : 6 }}" class="px-4 py-10 text-center text-sm" style="color:#8a9e8c;">
                                    Nenhum produto com validade cadastrada.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="grid gap-3 xl:hidden">
            @forelse ($items as $item)
                @php
                    $batch = $item['batch'] ?? null;
                    $writeOffMaxQuantity = $batch ? min((int) $batch->quantity, (int) $item['product']->stock_quantity) : 0;
                    $canWriteOff = $batch && $item['status'] === 'Vencido' && $writeOffMaxQuantity > 0;
                    $statusTone = match ($item['status']) {
                        'Vencido' => ['border-color:#fecaca;', 'background:#fee2e2;color:#b91c1c;', 'background:#dc2626;'],
                        'Vence em breve' => ['border-color:#fde68a;', 'background:#fef3c7;color:#92400e;', 'background:#f59e0b;'],
                        default => ['border-color:#bbf7d0;', 'background:#dcfce7;color:#166534;', 'background:#22c55e;'],
                    };
                @endphp

                <article class="rounded-2xl border bg-white p-4 shadow-sm" style="{{ $statusTone[0] }}">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <h2 class="break-words text-base font-bold" style="color:#1a3d1f;">{{ $item['product']->name }}</h2>
                            <p class="mt-1 text-xs" style="color:#8a9e8c;">
                                {{ $item['product']->category->name ?? 'N/A' }} · {{ $item['supplier']->name ?? $item['product']->supplier->name ?? 'N/A' }}
                            </p>
                        </div>
                        <span class="inline-flex shrink-0 items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold" style="{{ $statusTone[1] }}">
                            <span class="h-1.5 w-1.5 rounded-full" style="{{ $statusTone[2] }}"></span>
                            {{ $item['status'] }}
                        </span>
                    </div>

                    <div class="mt-4 grid grid-cols-2 gap-3 text-sm">
                        <div class="rounded-xl border px-3 py-2" style="border-color:#edf4ee;background:#fbfdfb;">
                            <p class="text-[10px] font-bold uppercase tracking-[0.14em]" style="color:#8a9e8c;">Validade</p>
                            <p class="mt-1 font-bold" style="color:#1a3d1f;">{{ $item['expiration_date']->format('d/m/Y') }}</p>
                        </div>

                        <div class="rounded-xl border px-3 py-2" style="border-color:#edf4ee;background:#fbfdfb;">
                            <p class="text-[10px] font-bold uppercase tracking-[0.14em]" style="color:#8a9e8c;">Prazo</p>
                            @if ($item['days_to_expire'] < 0)
                                <p class="mt-1 font-bold text-red-600">Há {{ abs($item['days_to_expire']) }} dias</p>
                            @elseif ($item['days_to_expire'] === 0)
                                <p class="mt-1 font-bold text-red-600">Hoje</p>
                            @elseif ($item['days_to_expire'] <= 60)
                                <p class="mt-1 font-bold text-yellow-700">Em {{ $item['days_to_expire'] }} dias</p>
                            @else
                                <p class="mt-1 font-bold text-green-700">Em {{ $item['days_to_expire'] }} dias</p>
                            @endif
                        </div>
                    </div>

                    @if ($viewMode === 'batch')
                        <div class="mt-3 grid grid-cols-2 gap-3 text-sm">
                            <div class="rounded-xl border px-3 py-2" style="border-color:#edf4ee;background:#fbfdfb;">
                                <p class="text-[10px] font-bold uppercase tracking-[0.14em]" style="color:#8a9e8c;">Lote</p>
                                <p class="mt-1 break-words font-bold" style="color:#1a3d1f;">{{ $item['batch']->number ?? 'N/A' }}</p>
                            </div>
                            <div class="rounded-xl border px-3 py-2" style="border-color:#edf4ee;background:#fbfdfb;">
                                <p class="text-[10px] font-bold uppercase tracking-[0.14em]" style="color:#8a9e8c;">Quantidade</p>
                                <p class="mt-1 font-bold" style="color:#1a3d1f;">{{ $item['batch']->quantity ?? 0 }}</p>
                            </div>
                        </div>
                    @endif

                    <div class="mt-4 border-t pt-3" style="border-color:#edf4ee;">
                        @if ($canWriteOff)
                            <form
                                method="POST"
                                action="{{ route('expiration-date.batches.write-off', $batch) }}"
                                class="grid grid-cols-[minmax(76px,0.45fr)_minmax(120px,1fr)] gap-2"
                                onsubmit="return confirm('Confirmar baixa de produto vencido neste lote?')"
                            >
                                @csrf
                                <input
                                    type="number"
                                    name="quantity"
                                    min="1"
                                    max="{{ $writeOffMaxQuantity }}"
                                    value="{{ $writeOffMaxQuantity }}"
                                    class="h-11 w-full rounded-xl border-gray-300 text-sm focus:border-red-500 focus:ring-red-500"
                                    aria-label="Quantidade para baixa"
                                >
                                <button type="submit"
                                    class="inline-flex h-11 items-center justify-center rounded-xl bg-red-600 px-3 text-sm font-bold text-white transition hover:bg-red-700">
                                    Dar baixa
                                </button>
                            </form>
                        @elseif (($item['status'] ?? '') === 'Vencido')
                            <p class="text-sm font-semibold text-gray-500">Sem estoque para baixa</p>
                        @else
                            <p class="text-sm" style="color:#8a9e8c;">Sem ação necessária</p>
                        @endif
                    </div>
                </article>
            @empty
                <div class="rounded-2xl border border-dashed bg-white px-4 py-10 text-center text-sm" style="border-color:#d4e8d6;color:#8a9e8c;">
                    Nenhum produto com validade cadastrada.
                </div>
            @endforelse
        </section>

        <div>
            {{ $items->links('vendor.pagination.agro') }}
        </div>
    </div>
</div>
@endsection
