@extends('layouts.app')

@section('slot')
@php
    $reportLabels = [
        'general_stock' => 'Relatório de estoque geral',
        'critical_stock' => 'Relatório de estoque crítico',
        'financial' => 'Relatório financeiro (custo x venda)',
        'by_supplier' => 'Relatório por fornecedor',
        'most_profitable' => 'Relatório de produtos mais lucrativos',
    ];

    $selectedReportType = old('report_type', $filters['report_type'] ?? 'general_stock');
    $displayTimezone = config('app.display_timezone');
@endphp

<div class="flex min-h-screen flex-1 flex-col overflow-hidden">
    <header class="sticky top-0 z-20 flex items-center justify-between border-b bg-white/80 px-5 py-3.5 backdrop-blur-md sm:px-6"
        style="border-color:#d4e8d6;">
        <div class="flex min-w-0 items-center gap-3">
            <x-mobile-menu-button />
            <div class="min-w-0">
                <h1 class="truncate font-display text-xl font-bold tracking-tight" style="color:#1a3d1f;">Exportar</h1>
                <p class="truncate text-[11px]" style="color:#8a9e8c;">Gere relatórios filtrados em PDF ou Excel</p>
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
        <section class="grid grid-cols-1 gap-3 md:grid-cols-3">
            <div class="overflow-hidden rounded-2xl border bg-white p-5 shadow-sm" style="border-color:#bbf7d0;">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-sm font-bold" style="color:#166534;">Venda potencial</p>
                        <p class="mt-3 break-words font-display text-2xl font-bold tracking-tight sm:text-3xl" style="color:#1a3d1f;">
                            R$ {{ number_format($insights['potential_sale_value'], 2, ',', '.') }}
                        </p>
                    </div>
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl" style="background:#dcfce7;color:#166534;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h18v18H3z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h8M8 14h8M8 18h5"/>
                        </svg>
                    </span>
                </div>
                <p class="mt-2 text-xs" style="color:#166534;">Receita bruta se o estoque válido for vendido</p>
            </div>

            <div class="overflow-hidden rounded-2xl border bg-white p-5 shadow-sm" style="border-color:#d4e8d6;">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-sm font-bold" style="color:#2d6a35;">Lucro estimado</p>
                        <p class="mt-3 break-words font-display text-2xl font-bold tracking-tight sm:text-3xl" style="color:#1a3d1f;">
                            R$ {{ number_format($insights['estimated_profit'], 2, ',', '.') }}
                        </p>
                    </div>
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl" style="background:#eaf6e9;color:#2d6a35;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 20V4"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="m6 10 6-6 6 6"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 20h14"/>
                        </svg>
                    </span>
                </div>
                <p class="mt-2 text-xs" style="color:#6e876f;">Margem potencial sobre itens válidos em estoque</p>
            </div>

            <div class="overflow-hidden rounded-2xl border bg-white p-5 shadow-sm" style="border-color:#fde68a;">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-sm font-bold" style="color:#92400e;">Estoque baixo</p>
                        <p class="mt-3 font-display text-3xl font-bold tracking-tight" style="color:#b45309;">
                            {{ number_format($insights['low_stock_items'], 0, ',', '.') }}
                        </p>
                    </div>
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl" style="background:#fef3c7;color:#b45309;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4M12 17h.01"/>
                        </svg>
                    </span>
                </div>
                <p class="mt-2 text-xs" style="color:#92400e;">Itens válidos acima de zero e no limite mínimo</p>
            </div>
        </section>

        @if ($errors->any())
            <div class="rounded-2xl border px-4 py-3 text-sm" style="border-color:#fecaca;background:#fef2f2;color:#b91c1c;">
                <ul class="list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="overflow-hidden rounded-2xl border bg-white shadow-sm"
            style="border-color:#d4e8d6;box-shadow:0 18px 45px rgba(26,61,31,0.06);">
            <div class="border-b px-5 py-5 sm:px-6"
                style="border-color:#d4e8d6;background:linear-gradient(135deg,#ffffff 0%,#f6fbf4 100%);">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <h2 class="mt-3 font-display text-xl font-bold tracking-tight sm:text-2xl" style="color:#142f18;">
                            Configurar exportação
                        </h2>
                        <p class="mt-1 text-sm" style="color:#6e876f;">
                            Escolha o modelo, refine os filtros e gere o arquivo no formato necessário.
                        </p>
                    </div>
                </div>
            </div>

            <form method="GET" action="{{ route('export.reports.pdf') }}" class="px-5 py-4 sm:px-6">
                <div class="grid grid-cols-1 gap-3 lg:grid-cols-[minmax(260px,1.35fr)_minmax(180px,0.75fr)_minmax(180px,0.75fr)]">
                    <div>
                        <label for="report_type" class="mb-1.5 block text-xs font-bold uppercase tracking-[0.14em]" style="color:#4a5c4c;">
                            Tipo de relatório
                        </label>
                        <select id="report_type" name="report_type"
                            class="h-11 w-full rounded-xl border px-3 text-sm transition-all focus:border-green-600 focus:ring-green-600"
                            style="border-color:#d4e8d6;background:#f9f6f0;color:#1a3d1f;">
                            @foreach ($reportLabels as $value => $label)
                                <option value="{{ $value }}" @selected($selectedReportType === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="category_id" class="mb-1.5 block text-xs font-bold uppercase tracking-[0.14em]" style="color:#4a5c4c;">
                            Categoria
                        </label>
                        <select id="category_id" name="category_id"
                            class="h-11 w-full rounded-xl border px-3 text-sm transition-all focus:border-green-600 focus:ring-green-600"
                            style="border-color:#d4e8d6;background:#f9f6f0;color:#1a3d1f;">
                            <option value="">Todas</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected((string) old('category_id', $filters['category_id'] ?? '') === (string) $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="supplier_id" class="mb-1.5 block text-xs font-bold uppercase tracking-[0.14em]" style="color:#4a5c4c;">
                            Fornecedor
                        </label>
                        <select id="supplier_id" name="supplier_id"
                            class="h-11 w-full rounded-xl border px-3 text-sm transition-all focus:border-green-600 focus:ring-green-600"
                            style="border-color:#d4e8d6;background:#f9f6f0;color:#1a3d1f;">
                            <option value="">Todos</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" @selected((string) old('supplier_id', $filters['supplier_id'] ?? '') === (string) $supplier->id)>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-3 grid grid-cols-1 gap-3 lg:grid-cols-[minmax(180px,0.75fr)_minmax(180px,0.75fr)_minmax(180px,0.75fr)]">
                    <div>
                        <label for="stock_status" class="mb-1.5 block text-xs font-bold uppercase tracking-[0.14em]" style="color:#4a5c4c;">
                            Status de estoque
                        </label>
                        <select id="stock_status" name="stock_status"
                            class="h-11 w-full rounded-xl border px-3 text-sm transition-all focus:border-green-600 focus:ring-green-600"
                            style="border-color:#d4e8d6;background:#f9f6f0;color:#1a3d1f;">
                            <option value="all" @selected(old('stock_status', $filters['stock_status'] ?? 'all') === 'all')>Todos</option>
                            <option value="in_stock" @selected(old('stock_status', $filters['stock_status'] ?? '') === 'in_stock')>Estoque normal</option>
                            <option value="low_stock" @selected(old('stock_status', $filters['stock_status'] ?? '') === 'low_stock')>Estoque baixo</option>
                            <option value="out_of_stock" @selected(old('stock_status', $filters['stock_status'] ?? '') === 'out_of_stock')>Sem estoque</option>
                        </select>
                    </div>

                    <div>
                        <label for="price_min" class="mb-1.5 block text-xs font-bold uppercase tracking-[0.14em]" style="color:#4a5c4c;">
                            Preço mínimo (R$)
                        </label>
                        <input id="price_min" name="price_min" type="number" step="0.01" min="0"
                            value="{{ old('price_min', $filters['price_min'] ?? '') }}"
                            class="h-11 w-full rounded-xl border px-3 text-sm transition-all focus:border-green-600 focus:ring-green-600"
                            style="border-color:#d4e8d6;background:#f9f6f0;color:#1a3d1f;">
                    </div>

                    <div>
                        <label for="price_max" class="mb-1.5 block text-xs font-bold uppercase tracking-[0.14em]" style="color:#4a5c4c;">
                            Preço máximo (R$)
                        </label>
                        <input id="price_max" name="price_max" type="number" step="0.01" min="0"
                            value="{{ old('price_max', $filters['price_max'] ?? '') }}"
                            class="h-11 w-full rounded-xl border px-3 text-sm transition-all focus:border-green-600 focus:ring-green-600"
                            style="border-color:#d4e8d6;background:#f9f6f0;color:#1a3d1f;">
                    </div>
                </div>

                <div class="mt-4 flex flex-col gap-2 sm:flex-row sm:flex-wrap sm:items-center">
                    <button type="submit"
                        class="inline-flex h-11 items-center justify-center gap-2 rounded-xl px-4 text-sm font-bold text-white transition-all duration-200 hover:-translate-y-px"
                        style="background:#2d6a35;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="m7 10 5 5 5-5"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 21h14"/>
                        </svg>
                        Baixar PDF
                    </button>

                    <button type="submit" formaction="{{ route('export.reports.excel') }}"
                        class="inline-flex h-11 items-center justify-center gap-2 rounded-xl border px-4 text-sm font-bold transition-all duration-200 hover:-translate-y-px hover:bg-green-50"
                        style="border-color:#2d6a35;color:#2d6a35;background:#eef7ef;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 2v6h6"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8 13 2.5 3L14 11"/>
                        </svg>
                        Baixar Excel
                    </button>

                    <button type="submit" formaction="{{ route('export.reports.preview') }}" formtarget="_blank"
                        class="inline-flex h-11 items-center justify-center gap-2 rounded-xl border px-4 text-sm font-bold transition-all duration-200 hover:bg-green-50"
                        style="border-color:#d4e8d6;color:#4a5c4c;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                        Pré-visualizar
                    </button>

                    <a href="{{ route('export.index') }}"
                        class="inline-flex h-11 items-center justify-center gap-2 rounded-xl border px-4 text-sm font-bold transition-all duration-200 hover:bg-red-50"
                        style="border-color:#fecaca;color:#991b1b;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 6 6 18M6 6l12 12"/>
                        </svg>
                        Limpar
                    </a>
                </div>
            </form>
        </section>

        <section class="overflow-hidden rounded-2xl border bg-white shadow-sm" style="border-color:#d4e8d6;">
            <div class="border-b px-5 py-5 sm:px-6"
                style="border-color:#d4e8d6;background:linear-gradient(135deg,#ffffff 0%,#f6fbf4 100%);">
                <div>
                    <div class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-bold uppercase tracking-[0.18em]"
                        style="background:#eaf6e9;color:#2d6a35;">
                        <span class="h-1.5 w-1.5 rounded-full" style="background:#4caf50;"></span>
                        Histórico
                    </div>
                    <h2 class="mt-3 font-display text-xl font-bold tracking-tight" style="color:#142f18;">Exportações recentes</h2>
                    <p class="mt-1 text-sm" style="color:#6e876f;">Últimos relatórios gerados no sistema.</p>
                </div>
            </div>

            <div class="hidden overflow-x-auto xl:block">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b" style="border-color:#d4e8d6;background:#f9f6f0;">
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-[0.16em]" style="color:#8a9e8c;">Data</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-[0.16em]" style="color:#8a9e8c;">Tipo de relatório</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-[0.16em]" style="color:#8a9e8c;">Quem gerou</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse ($histories as $history)
                            <tr class="transition-colors hover:bg-[#fbfdfb]">
                                <td class="whitespace-nowrap px-5 py-4 font-semibold" style="color:#1a3d1f;">
                                    {{ $history->created_at->timezone($displayTimezone)->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-5 py-4" style="color:#4a5c4c;">
                                    {{ $reportLabels[$history->report_type] ?? $history->report_type }}
                                </td>
                                <td class="px-5 py-4" style="color:#4a5c4c;">
                                    {{ $history->user?->name ?? 'Usuário removido' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-5 py-10 text-center text-sm" style="color:#8a9e8c;">
                                    Nenhuma exportação registrada até o momento.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="grid gap-3 p-4 xl:hidden">
                @forelse ($histories as $history)
                    <article class="rounded-2xl border bg-white p-4 shadow-sm" style="border-color:#d4e8d6;">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <h3 class="break-words text-sm font-bold" style="color:#1a3d1f;">
                                    {{ $reportLabels[$history->report_type] ?? $history->report_type }}
                                </h3>
                                <p class="mt-1 text-xs" style="color:#8a9e8c;">
                                    {{ $history->created_at->timezone($displayTimezone)->format('d/m/Y H:i') }}
                                </p>
                            </div>
                            <span class="inline-flex shrink-0 items-center rounded-full px-3 py-1 text-xs font-bold" style="background:#eaf6e9;color:#2d6a35;">
                                PDF/Excel
                            </span>
                        </div>
                        <p class="mt-3 text-sm" style="color:#4a5c4c;">
                            Gerado por <span class="font-bold">{{ $history->user?->name ?? 'Usuário removido' }}</span>
                        </p>
                    </article>
                @empty
                    <div class="rounded-2xl border border-dashed bg-white px-4 py-10 text-center text-sm" style="border-color:#d4e8d6;color:#8a9e8c;">
                        Nenhuma exportação registrada até o momento.
                    </div>
                @endforelse
            </div>

            <div class="border-t px-5 py-4" style="border-color:#eef5ef;">
                {{ $histories->links('vendor.pagination.agro') }}
            </div>
        </section>
    </div>
</div>
@endsection
