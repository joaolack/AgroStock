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
                <h1 class="font-display text-xl font-bold tracking-tight" style="color:#1a3d1f;">Validades</h1>
                <p class="text-[11px]" style="color:#8a9e8c;">Controle a validade de seus produtos</p>
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
        <form method="GET" action="{{ route('expiration-date.index') }}" class="bg-white rounded-2xl border p-4" style="border-color:#d4e8d6;">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
                <div class="md:col-span-2">
                    <label for="search" class="block text-xs font-semibold uppercase tracking-wide mb-1" style="color:#6e876f;">Busca</label>
                    <input
                        type="text"
                        id="search"
                        name="search"
                        value="{{ $filters['search'] }}"
                        placeholder="Produto ou fornecedor"
                        class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 text-sm"
                    >
                </div>

                <div>
                    <label for="view" class="block text-xs font-semibold uppercase tracking-wide mb-1" style="color:#6e876f;">Visualizacao</label>
                    <select id="view" name="view" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 text-sm">
                        <option value="product" @selected($viewMode === 'product')>Por produto</option>
                        <option value="batch" @selected($viewMode === 'batch')>Por lote</option>
                    </select>
                </div>

                <div>
                    <label for="status" class="block text-xs font-semibold uppercase tracking-wide mb-1" style="color:#6e876f;">Status</label>
                    <select id="status" name="status" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 text-sm">
                        <option value="">Todos</option>
                        <option value="Vencido" @selected($filters['status'] === 'Vencido')>Vencido</option>
                        <option value="Vence em breve" @selected($filters['status'] === 'Vence em breve')>Vence em breve</option>
                        <option value="Seguro" @selected($filters['status'] === 'Seguro')>Seguro</option>
                    </select>
                </div>

                <div>
                    <label for="supplier_id" class="block text-xs font-semibold uppercase tracking-wide mb-1" style="color:#6e876f;">Fornecedor</label>
                    <select id="supplier_id" name="supplier_id" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 text-sm">
                        <option value="">Todos</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" @selected($filters['supplier_id'] === (string) $supplier->id)>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end">
                    <label class="inline-flex items-center gap-2 text-sm">
                        <input type="checkbox" name="stock_only" value="1" class="rounded border-gray-300 text-green-600 focus:ring-green-500" @checked($filters['stock_only'])>
                        <span style="color:#4a5c4c;">Com estoque apenas</span>
                    </label>
                </div>
            </div>

            <div class="mt-3 flex items-center gap-2">
                <button type="submit" class="px-4 py-2 rounded-lg text-sm font-semibold text-white" style="background:#2d6a35;">
                    Filtrar
                </button>
                <a href="{{ route('expiration-date.index') }}" class="px-4 py-2 rounded-lg text-sm font-semibold border" style="border-color:#d4e8d6;color:#4a5c4c;">
                    Limpar
                </a>
            </div>
        </form>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <div class="rounded-xl border px-4 py-3 bg-white" style="border-color:#f5b7b1;">
                <p class="text-xs font-semibold uppercase tracking-wide text-red-700">Vencidos</p>
                <p class="text-2xl font-bold text-red-700">{{ $summary['expired'] }}</p>
            </div>
            <div class="rounded-xl border px-4 py-3 bg-white" style="border-color:#f8e7a3;">
                <p class="text-xs font-semibold uppercase tracking-wide text-yellow-700">Vence em 30 dias</p>
                <p class="text-2xl font-bold text-yellow-700">{{ $summary['soon'] }}</p>
            </div>
            <div class="rounded-xl border px-4 py-3 bg-white" style="border-color:#b8dfbd;">
                <p class="text-xs font-semibold uppercase tracking-wide text-green-700">Seguros</p>
                <p class="text-2xl font-bold text-green-700">{{ $summary['safe'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl border overflow-hidden" style="border-color:#d4e8d6;">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b" style="border-color:#d4e8d6;background:#f9f6f0;">
                            <th class="text-left px-4 py-3 text-[11px] font-semibold uppercase tracking-wider" style="color:#8a9e8c;">Produto</th>
                            @if ($viewMode === 'batch')
                                <th class="text-left px-4 py-3 text-[11px] font-semibold uppercase tracking-wider" style="color:#8a9e8c;">Lote</th>
                                <th class="text-left px-4 py-3 text-[11px] font-semibold uppercase tracking-wider" style="color:#8a9e8c;">Qtd</th>
                            @endif
                            <th class="text-left px-4 py-3 text-[11px] font-semibold uppercase tracking-wider" style="color:#8a9e8c;">Categoria</th>
                            <th class="text-left px-4 py-3 text-[11px] font-semibold uppercase tracking-wider" style="color:#8a9e8c;">Fornecedor</th>
                            <th class="text-left px-4 py-3 text-[11px] font-semibold uppercase tracking-wider" style="color:#8a9e8c;">Validade</th>
                            <th class="text-left px-4 py-3 text-[11px] font-semibold uppercase tracking-wider" style="color:#8a9e8c;">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y" style="divide-color:#eef7ef;">
                        @forelse ($items as $item)
                            <tr>
                                <td class="px-4 py-3.5">
                                    <p class="font-semibold text-gray-900">{{ $item['product']->name }}</p>
                                </td>
                                @if ($viewMode === 'batch')
                                    <td class="px-4 py-3.5 text-gray-700">{{ $item['batch']->number ?? 'N/A' }}</td>
                                    <td class="px-4 py-3.5 text-gray-700">{{ $item['batch']->quantity ?? 0 }}</td>
                                @endif
                                <td class="px-4 py-3.5 text-gray-700">{{ $item['product']->category->name ?? 'N/A' }}</td>
                                <td class="px-4 py-3.5 text-gray-700">{{ $item['supplier']->name ?? $item['product']->supplier->name ?? 'N/A' }}</td>
                                <td class="px-4 py-3.5 text-gray-700">
                                    {{ $item['expiration_date']->format('d/m/Y') }}
                                </td>
                                <td class="px-4 py-3.5">
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full border text-xs font-semibold {{ $item['status_class'] }}">
                                            {{ $item['status'] }}
                                        </span>

                                        @if ($item['days_to_expire'] < 0)
                                            <span class="text-xs text-red-600">Há {{ abs($item['days_to_expire']) }} dias</span>
                                        @elseif ($item['days_to_expire'] === 0)
                                            <span class="text-xs text-red-600">Vence hoje</span>
                                        @elseif ($item['days_to_expire'] <= 30)
                                            <span class="text-xs text-yellow-700">Em {{ $item['days_to_expire'] }} dias</span>
                                        @else
                                            <span class="text-xs text-green-700">Em {{ $item['days_to_expire'] }} dias</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $viewMode === 'batch' ? 7 : 5 }}" class="text-center px-4 py-8 text-gray-500">
                                    Nenhum produto com validade cadastrada.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div>
            {{ $items->links('vendor.pagination.agro') }}
        </div>
    </div>
</div>
@endsection
