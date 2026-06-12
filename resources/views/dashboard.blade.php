@extends('layouts.app')

@section('slot')
<main class="flex-1 flex flex-col min-h-screen overflow-hidden">

    <header class="sticky top-0 z-20 flex items-center justify-between px-6 py-3.5 border-b bg-white/80 backdrop-blur-md"
            style="border-color:#d4e8d6;">
        <div class="flex items-center gap-3">
            <button class="lg:hidden p-2 rounded-lg hover:bg-agro-pale transition colors" style="color:#4a5c4c;">
                ☰
            </button>
            <div>
                <h1 class="font-display text-xl font-bold tracking-tight" style="color:#1a3d1f;">Bom dia, {{ auth()->user()->name }}</h1>
                <p class="text-[11px]" style="color:#8a9e8c;">{{ ucfirst(today()->translatedFormat('l, d \d\e F \d\e Y')) }}</p>
            </div>
        </div>
        
        <div class="flex items-center gap-2.5">
            <a href="{{ route('export.index') }}"
                    class="hidden sm:flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-semibold border transition-all hover:bg-agro-pale"
                    style="border-color:#d4e8d6;color:#4a5c4c;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
</svg>
 Exportar relatório
            </a>
            <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold text-white cursor-pointer"
                 style="background:linear-gradient(135deg,#4caf50,#2d6a35);">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            <div>
        </div>
    </header>

    <div class="flex-1 p-6 overflow-y-auto space-y-5">
        <!--Cards-->    
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">

            <div class="rounded-2xl p-5 border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-md font-medium text-slate-500">Total de produtos</p>
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700">
                        <x-fas-box class="h-4 w-4"/>
                    </div>
                </div>

                <p class="mt-4 text-3xl font-bold tracking-tight text-slate-900">
                    {{ number_format($totalProducts, 0, ',', '.') }}
                </p>

                <p class="mt-1 text-sm text-slate-400">
                    Produtos cadastrados
                </p>
            </div>

            <div class="rounded-2xl p-5 border border-slate-200 bg-white shadow-sm">
                <div class="flex items-start justify-between">
                    <p class="text-sm font-medium text-slate-500">Alertas críticos</p>
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-red-50 text-red-700">
                        <x-fas-triangle-exclamation class="h-4 w-4" />
                    </div>
                </div>

                <p class="mt-4 text-3xl font-bold tracking-tight text-slate-900">{{ $criticalStockCount }}</p>

                <div class="mt-2 inline-flex rounded-full bg-red-50 px-2 py-0.5 text-xs font-medium text-red-700">
                    Crítico
                </div>
            </div>

            <div class="rounded-2xl p-5 border border-slate-200 bg-white shadow-sm">
                <div class="flex items-start justify-between">
                    <p class="text-md font-medium text-slate-500">Movimentações</p>
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M17 3v4h-4M7 21v-4h4M18.5 9A7 7 0 0 0 6.8 5.8L5 7.5M5.5 15a7 7 0 0 0 11.7 3.2L19 16.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </div>

                <p class="mt-4 text-3xl font-bold tracking-tight text-slate-900">{{ number_format($todayMovementsCount, 0, ',', '.') }}</p>

                <p class="mt-1 text-sm text-slate-400">
                    Movimentações hoje
                </p>
            </div>

            @php
                $movementBalance30Days = $movementEntriesTotal - $movementExitsTotal;
                $movementBalancePrefix = $movementBalance30Days > 0 ? '+' : '';
                $movementBalanceTone = match (true) {
                    $movementBalance30Days > 0 => [
                        'icon' => 'bg-emerald-50 text-emerald-700',
                        'badge' => 'bg-emerald-50 text-emerald-700',
                        'label' => 'Crescimento',
                    ],
                    $movementBalance30Days < 0 => [
                        'icon' => 'bg-red-50 text-red-700',
                        'badge' => 'bg-red-50 text-red-700',
                        'label' => 'Declínio',
                    ],
                    default => [
                        'icon' => 'bg-amber-50 text-amber-700',
                        'badge' => 'bg-amber-50 text-amber-700',
                        'label' => 'Estável',
                    ],
                };
            @endphp
            <div class="rounded-2xl p-5 border border-slate-200 bg-white shadow-sm">
                <div class="flex items-start justify-between">
                    <p class="text-md font-medium text-slate-500">Saldo de movimentações</p>
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl {{ $movementBalanceTone['icon'] }}">
                        <span class="text-base font-bold">{{ $stockTrend['icon'] }}</span>
                    </div>
                </div>

                <p class="mt-4 text-3xl font-bold tracking-tight text-slate-900">{{ $movementBalancePrefix }}{{ number_format($movementBalance30Days, 0, ',', '.') }}</p>

                <p class="mt-1 text-sm text-slate-400">
                    Entradas - saídas em 30 dias
                </p>
            </div>

        </div>

        <div class="bg-white rounded-xl shadow-sm border p-6" style="border-color:#d4e8d6;">
            <div class="flex flex-col gap-4 mb-5 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <h2 class="text-lg font-bold" style="color:#1a3d1f;">Entradas x Saídas</h2>
                    <p class="text-sm" style="color:#8a9e8c;">Movimentações dos últimos 30 dias</p>
                </div>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-3 lg:min-w-[520px]">
                    <div class="rounded-lg border px-4 py-3" style="border-color:#d4e8d6;">
                        <p class="text-xs uppercase font-semibold" style="color:#8a9e8c;">Entradas</p>
                        <p class="text-2xl font-bold mt-1" style="color:#166534;">{{ number_format($movementEntriesTotal, 0, ',', '.') }}</p>
                    </div>
                    <div class="rounded-lg border px-4 py-3" style="border-color:#d4e8d6;">
                        <p class="text-xs uppercase font-semibold" style="color:#8a9e8c;">Saídas</p>
                        <p class="text-2xl font-bold mt-1" style="color:#991b1b;">{{ number_format($movementExitsTotal, 0, ',', '.') }}</p>
                    </div>
                    <div class="rounded-lg border px-4 py-3" style="border-color:#d4e8d6;">
                        <p class="text-xs uppercase font-semibold" style="color:#8a9e8c;">Tendência</p>
                        <div class="mt-2 flex items-center gap-2">
                            <span class="w-7 h-7 rounded-full flex items-center justify-center text-sm font-bold" style="{{ $stockTrend['badge_style'] }}">
                                {{ $stockTrend['icon'] }}
                            </span>
                            <span class="text-sm font-bold {{ $stockTrend['class'] }}">{{ $stockTrend['label'] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative h-[320px]">
                <canvas id="dashboardMovementChart" data-movement-series='@json($movementSeries)'></canvas>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold mb-4 text-red-700 border-b pb-2">Itens no Estoque Mínimo ou Abaixo</h3>
                @if ($criticalStock->isEmpty())
                    <p class="text-gray-500">Nenhum alerta de estoque. Tudo sob controle!</p>
                @else
                    <ul class="divide-y divide-gray-100">
                        @foreach ($criticalStock as $product)
                            <li class="py-3 flex justify-between itens-center text-sm">
                                <span class="font-medium text-gray-900">{{ $product->name }}</span>
                                <span class="text-red-600 font-bold">
                                    {{ $product->stock_quantity }} / Min: {{ $product->minimum_stock}}
                                </span>
                            </li>
                        @endforeach
                        @if ($criticalStockCount > 5)
                        <li class="pt-3 text-center text-sm">
                            <a href="{{ route('products.index', ['stock_status' => 'Crítico']) }}" class="text-indigo-600 hover:underline"> Ver todos os {{ $criticalStockCount }} alertas</a>
                        </li>
                        @endif    
                    </ul>
                @endif        
            </div>
        
            <div class="bg-white p-6 rounded-lg shado-md">
                <h3 class="text-xl font-semibold mb-4 text-yellow-700 border-b pb-2">Produtos com Vencimento próximo</h3>
                @if ($closeToExpiry->isEmpty())
                    <p class="text-gray-500">Nenhum produto vencendo em breve.</p>
                @else
                    <ul class="divide-y divide-gray-100">
                        @foreach ($closeToExpiry as $batch)
                            <li class="py-3 flex justify-between itens-center text-sm">
                                <span class="font-medium text-gray-900">
                                    {{ $batch->product->name }} - Lote {{ $batch->number }}
                                </span>
                                @php
                                    $expiryDate = \Carbon\Carbon::parse($batch->expiration_date)->startOfDay();
                                    $today = \Carbon\Carbon::now()->startOfDay();
                                    $statusDays = $today->diffInDays($expiryDate, false); 
                                    $absoluteDays = abs($statusDays); 
                                    $expirationDate = $expiryDate->format('d/m/Y');
                                @endphp
                                @if ($statusDays < 0) 
                                    <span class="text-red-600 font-bold">EXPIRADO HÁ {{ $absoluteDays }} DIAS ({{ $expirationDate}})</span>
                                @elseif ($statusDays === 0)
                                    <span class="text-red-600 font-bold">VENCE HOJE ({{ $expirationDate}})</span>
                                @else
                                    <span class="text-yellow-600">Vence em {{ $absoluteDays}} dias ({{ $expirationDate}})</span>
                                @endif
                            </li>
                        @endforeach
                        @if ($closeToExpiryCount > 5)
                            <li class="pt-3 text-center text-sm">
                                <a href="{{ route('expiration-date.index', ['view' => 'batch', 'status' => 'Vence em breve', 'stock_only' => 1]) }}" class="text-indigo-600 hover:underline">Ver todos os {{ $closeToExpiryCount }} vencimentos</a>
                            </li>
                        @endif
                    </ul>
                @endif 
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex flex-col gap-1 border-b pb-3 mb-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-xl font-semibold text-green-800">Sugestão de Compra</h3>
                    <p class="text-sm text-gray-500">Baseada na margem de segurança: (estoque mínimo x 1,5) - 5.</p>
                </div>
                <span class="text-xs font-semibold px-3 py-1 rounded-full self-start" style="background:#eef7ef;color:#2d6a35;">
                    {{ $criticalStockCount }} {{ $criticalStockCount === 1 ? 'item crítico' : 'itens críticos' }}
                </span>
            </div>

            @if ($purchaseSuggestions->isEmpty())
                <p class="text-gray-500">Nenhuma sugestão de compra no momento.</p>
            @else
                <ul class="divide-y divide-gray-100">
                    @foreach ($purchaseSuggestions as $product)
                        <li class="py-3 flex flex-col gap-2 text-sm sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <span class="font-medium text-gray-900">{{ $product->name }}</span>
                                <span class="block text-xs text-gray-500">
                                    Estoque atual: {{ $product->stock_quantity }} | Mínimo: {{ $product->minimum_stock }}
                                </span>
                            </div>
                            <span class="font-bold text-green-700">
                                Comprar {{ $product->suggested_purchase_quantity }} {{ $product->suggested_purchase_quantity === 1 ? 'unidade' : 'unidades' }}
                            </span>
                        </li>
                    @endforeach
                    @if ($criticalStockCount > 5)
                        <li class="pt-3 text-center text-sm">
                            <a href="{{ route('products.index') }}" class="text-indigo-600 hover:underline">Ver todos os {{ $criticalStockCount }} itens críticos</a>
                        </li>
                    @endif
                </ul>
            @endif
        </div>

    </div>
    @endsection
</main>
