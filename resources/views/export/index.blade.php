@extends('layouts.app')

@section('slot')
<div class="flex flex-1 flex-col min-h-screen overflow-hidden">
    <header class="sticky top-0 z-20 flex items-center justify-between px-6 py-3.5 border-b bg-white/80 backdrop-blur-md"
            style="border-color:#d4e8d6;">
        <div class="flex items-center gap-3">
            <button class="lg:hidden p-2 rounded-lg hover:bg-agro-pale transition colors" style="color:#4a5c4c;">
                ☰
            </button>
            <div>
                <h1 class="font-display text-xl font-bold tracking-tight" style="color:#1a3d1f;">Exportar</h1>
                <p class="text-[11px]" style="color:#8a9e8c;">Exporte seus relátorios</p>
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
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div class="relative overflow-hidden bg-white rounded-2xl border p-5 shadow-sm" style="border-color:#d4e8d6;">
                <div class="absolute -right-8 -top-8 h-24 w-24 rounded-full" style="background:rgba(76,175,80,0.12);"></div>
                <div class="relative">
                    <div class="flex items-center justify-between gap-3">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em]" style="color:#4a5c4c;">Venda potencial</p>
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl" style="background:#eef7ef;color:#2d6a35;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 3h18v18H3z"/>
                                <path d="M8 14h8"/>
                                <path d="M8 10h8"/>
                                <path d="M8 18h5"/>
                            </svg>
                        </span>
                    </div>
                    <p class="mt-4 font-display text-2xl font-bold tracking-tight" style="color:#1a3d1f;">
                        R$ {{ number_format($insights['potential_sale_value'], 2, ',', '.') }}
                    </p>
                    <p class="mt-1 text-xs" style="color:#8a9e8c;">Receita bruta se o estoque atual for vendido.</p>
                </div>
            </div>

            <div class="relative overflow-hidden bg-white rounded-2xl border p-5 shadow-sm" style="border-color:#d4e8d6;">
                <div class="absolute -right-8 -top-8 h-24 w-24 rounded-full" style="background:rgba(45,106,53,0.12);"></div>
                <div class="relative">
                    <div class="flex items-center justify-between gap-3">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em]" style="color:#4a5c4c;">Lucro estimado</p>
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl" style="background:#e8f4ea;color:#1a3d1f;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 20V4"/>
                                <path d="m6 10 6-6 6 6"/>
                                <path d="M5 20h14"/>
                            </svg>
                        </span>
                    </div>
                    <p class="mt-4 font-display text-2xl font-bold tracking-tight" style="color:#1a3d1f;">
                        R$ {{ number_format($insights['estimated_profit'], 2, ',', '.') }}
                    </p>
                    <p class="mt-1 text-xs" style="color:#8a9e8c;">Margem potencial sobre os itens em estoque.</p>
                </div>
            </div>

            <div class="relative overflow-hidden bg-white rounded-2xl border p-5 shadow-sm" style="border-color:#d4e8d6;">
                <div class="absolute -right-8 -top-8 h-24 w-24 rounded-full" style="background:rgba(234,179,8,0.16);"></div>
                <div class="relative">
                    <div class="flex items-center justify-between gap-3">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em]" style="color:#4a5c4c;">Estoque baixo</p>
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl" style="background:#fef9c3;color:#854d0e;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                                <path d="M12 9v4"/>
                                <path d="M12 17h.01"/>
                            </svg>
                        </span>
                    </div>
                    <p class="mt-4 font-display text-2xl font-bold tracking-tight" style="color:#1a3d1f;">
                        {{ number_format($insights['low_stock_items'], 0, ',', '.') }}
                    </p>
                    <p class="mt-1 text-xs" style="color:#8a9e8c;">Itens com estoque acima de zero e no limite minimo.</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border p-6" style="border-color:#d4e8d6;">
            <div class="mb-5">
                <div>
                    <h2 class="text-lg font-bold" style="color:#1a3d1f;">Relatorios</h2>
                    <p class="text-sm" style="color:#8a9e8c;">
                        Escolha o tipo de relatorio e aplique filtros antes de exportar.
                    </p>
                </div>
            </div>

            <form method="GET" action="{{ route('export.reports.pdf') }}" class="space-y-4">
                <div>
                    <label for="report_type" class="block text-xs font-semibold mb-1" style="color:#4a5c4c;">
                        Tipo de relatorio
                    </label>
                    <select id="report_type" name="report_type"
                            class="w-full rounded-lg border px-3 py-2 text-sm focus:outline-none"
                            style="border-color:#d4e8d6;">
                        <option value="general_stock" @selected(old('report_type', $filters['report_type'] ?? 'general_stock') === 'general_stock')>Relatorio de estoque geral</option>
                        <option value="critical_stock" @selected(old('report_type', $filters['report_type'] ?? '') === 'critical_stock')>Relatorio de estoque critico</option>
                        <option value="financial" @selected(old('report_type', $filters['report_type'] ?? '') === 'financial')>Relatorio financeiro (custo x venda)</option>
                        <option value="by_supplier" @selected(old('report_type', $filters['report_type'] ?? '') === 'by_supplier')>Relatorio por fornecedor</option>
                        <option value="most_profitable" @selected(old('report_type', $filters['report_type'] ?? '') === 'most_profitable')>Relatorio de produtos mais lucrativos</option>
                    </select>
                </div>

                <details class="border rounded-lg p-4" style="border-color:#d4e8d6;" open>
                    <summary class="cursor-pointer text-sm font-semibold" style="color:#1a3d1f;">
                        Filtros avancados
                    </summary>

                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="category_id" class="block text-xs font-semibold mb-1" style="color:#4a5c4c;">
                                Categoria
                            </label>
                            <select id="category_id" name="category_id"
                                    class="w-full rounded-lg border px-3 py-2 text-sm focus:outline-none"
                                    style="border-color:#d4e8d6;">
                                <option value="">Todas</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        @selected((string) old('category_id', $filters['category_id'] ?? '') === (string) $category->id)>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="supplier_id" class="block text-xs font-semibold mb-1" style="color:#4a5c4c;">
                                Fornecedor
                            </label>
                            <select id="supplier_id" name="supplier_id"
                                    class="w-full rounded-lg border px-3 py-2 text-sm focus:outline-none"
                                    style="border-color:#d4e8d6;">
                                <option value="">Todos</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}"
                                        @selected((string) old('supplier_id', $filters['supplier_id'] ?? '') === (string) $supplier->id)>
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="stock_status" class="block text-xs font-semibold mb-1" style="color:#4a5c4c;">
                                Status de estoque
                            </label>
                            <select id="stock_status" name="stock_status"
                                    class="w-full rounded-lg border px-3 py-2 text-sm focus:outline-none"
                                    style="border-color:#d4e8d6;">
                                <option value="all" @selected(old('stock_status', $filters['stock_status'] ?? 'all') === 'all')>Todos</option>
                                <option value="in_stock" @selected(old('stock_status', $filters['stock_status'] ?? '') === 'in_stock')>Estoque normal</option>
                                <option value="low_stock" @selected(old('stock_status', $filters['stock_status'] ?? '') === 'low_stock')>Estoque baixo</option>
                                <option value="out_of_stock" @selected(old('stock_status', $filters['stock_status'] ?? '') === 'out_of_stock')>Sem estoque</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label for="price_min" class="block text-xs font-semibold mb-1" style="color:#4a5c4c;">
                                    Preco minimo (R$)
                                </label>
                                <input id="price_min" name="price_min" type="number" step="0.01" min="0"
                                       value="{{ old('price_min', $filters['price_min'] ?? '') }}"
                                       class="w-full rounded-lg border px-3 py-2 text-sm focus:outline-none"
                                       style="border-color:#d4e8d6;">
                            </div>
                            <div>
                                <label for="price_max" class="block text-xs font-semibold mb-1" style="color:#4a5c4c;">
                                    Preco maximo (R$)
                                </label>
                                <input id="price_max" name="price_max" type="number" step="0.01" min="0"
                                       value="{{ old('price_max', $filters['price_max'] ?? '') }}"
                                       class="w-full rounded-lg border px-3 py-2 text-sm focus:outline-none"
                                       style="border-color:#d4e8d6;">
                            </div>
                        </div>
                    </div>
                </details>

                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('export.index') }}"
                       class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-semibold border"
                       style="border-color:#d4e8d6;color:#1a3d1f;">
                        Limpar filtros
                    </a>
                    <button type="submit"
                            formaction="{{ route('export.reports.preview') }}"
                            formtarget="_blank"
                            class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-semibold border"
                            style="border-color:#2d6a35;color:#2d6a35;">
                        Pre-visualizar PDF
                    </button>
                    <button type="submit"
                            class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-semibold text-white"
                            style="background:#2d6a35;">
                        Baixar PDF
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm border p-6" style="border-color:#d4e8d6;">
            <div class="mb-4">
                <h2 class="text-lg font-bold" style="color:#1a3d1f;">Historico de exportacoes</h2>
                <p class="text-sm" style="color:#8a9e8c;">
                    Ultimas exportacoes realizadas no sistema.
                </p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="border-b" style="border-color:#d4e8d6;">
                            <th class="text-left py-2 pr-4" style="color:#4a5c4c;">Data</th>
                            <th class="text-left py-2 pr-4" style="color:#4a5c4c;">Tipo de relatorio</th>
                            <th class="text-left py-2" style="color:#4a5c4c;">Quem gerou</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($histories as $history)
                            <tr class="border-b" style="border-color:#eef5ef;">
                                <td class="py-2 pr-4">{{ $history->created_at->timezone('America/Sao_Paulo')->format('d/m/Y H:i') }}</td>
                                <td class="py-2 pr-4">
                                    @switch($history->report_type)
                                        @case('general_stock') Relatorio de estoque geral @break
                                        @case('critical_stock') Relatorio de estoque critico @break
                                        @case('financial') Relatorio financeiro (custo x venda) @break
                                        @case('by_supplier') Relatorio por fornecedor @break
                                        @case('most_profitable') Relatorio de produtos mais lucrativos @break
                                        @default {{ $history->report_type }}
                                    @endswitch
                                </td>
                                <td class="py-2">{{ $history->user?->name ?? 'Usuario removido' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-3" style="color:#8a9e8c;">
                                    Nenhuma exportacao registrada ate o momento.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
