@extends('layouts.app')

@section('slot')
@php
    $movementBalance30Days = $movementEntriesTotal - $movementExitsTotal;
    $movementBalancePrefix = $movementBalance30Days > 0 ? '+' : '';
    $movementBalanceStyle = match (true) {
        $movementBalance30Days > 0 => 'background:#dcfce7;color:#166534;',
        $movementBalance30Days < 0 => 'background:#fee2e2;color:#991b1b;',
        default => 'background:#fef3c7;color:#92400e;',
    };
    $movementBalanceLabel = match (true) {
        $movementBalance30Days > 0 => 'Crescimento',
        $movementBalance30Days < 0 => 'Declínio',
        default => 'Estável',
    };
@endphp

<main class="flex min-h-screen flex-1 flex-col overflow-hidden">
    <header class="sticky top-0 z-20 flex items-center justify-between border-b bg-white/80 px-5 py-3.5 backdrop-blur-md sm:px-6"
        style="border-color:#d4e8d6;">
        <div class="flex min-w-0 items-center gap-3">
            <x-mobile-menu-button />
            <div class="min-w-0">
                <h1 class="truncate font-display text-xl font-bold tracking-tight" style="color:#1a3d1f;">
                    Bom dia, {{ auth()->user()->name }}
                </h1>
                <p class="truncate text-[11px]" style="color:#8a9e8c;">
                    {{ ucfirst(today()->translatedFormat('l, d \d\e F \d\e Y')) }}
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('export.index') }}"
                class="hidden items-center gap-2 rounded-xl border px-3 py-2 text-xs font-bold transition-all hover:bg-agro-pale sm:flex"
                style="border-color:#d4e8d6;color:#4a5c4c;">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12m0 0 4-4m-4 4-4-4M4 21h16"/>
                </svg>
                Exportar relatório
            </a>
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
                <p class="mt-4 text-3xl font-bold tracking-tight" style="color:#1a3d1f;">
                    {{ number_format($totalProducts, 0, ',', '.') }}
                </p>
                <p class="mt-1 text-xs" style="color:#8a9e8c;">Produtos cadastrados</p>
            </div>

            <div class="rounded-2xl border bg-white p-5 shadow-sm" style="border-color:#d4e8d6;">
                <div class="flex items-center justify-between gap-3">
                    <p class="text-sm font-semibold" style="color:#4a5c4c;">Alertas críticos</p>
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl" style="background:#fee2e2;color:#b91c1c;">
                        <x-fas-triangle-exclamation class="h-4 w-4" />
                    </div>
                </div>
                <p class="mt-4 text-3xl font-bold tracking-tight" style="color:#1a3d1f;">{{ number_format($criticalStockCount, 0, ',', '.') }}</p>
                <span class="mt-2 inline-flex rounded-full px-2.5 py-1 text-xs font-bold" style="background:#fef2f2;color:#b91c1c;">
                    Estoque válido
                </span>
            </div>

            <div class="rounded-2xl border bg-white p-5 shadow-sm" style="border-color:#d4e8d6;">
                <div class="flex items-center justify-between gap-3">
                    <p class="text-sm font-semibold" style="color:#4a5c4c;">Movimentações hoje</p>
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl" style="background:#eaf6e9;color:#2d6a35;">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M17 3v4h-4M7 21v-4h4M18.5 9A7 7 0 0 0 6.8 5.8L5 7.5M5.5 15a7 7 0 0 0 11.7 3.2L19 16.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </div>
                <p class="mt-4 text-3xl font-bold tracking-tight" style="color:#1a3d1f;">{{ number_format($todayMovementsCount, 0, ',', '.') }}</p>
                <p class="mt-1 text-xs" style="color:#8a9e8c;">Entradas e saídas registradas</p>
            </div>

            <div class="rounded-2xl border bg-white p-5 shadow-sm" style="border-color:#d4e8d6;">
                <div class="flex items-center justify-between gap-3">
                    <p class="text-sm font-semibold" style="color:#4a5c4c;">Valor em estoque</p>
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl" style="background:#eaf6e9;color:#2d6a35;">
                        <x-fas-dollar-sign class="h-4 w-4" />
                    </div>
                </div>
                <p class="mt-4 text-2xl font-bold tracking-tight sm:text-3xl" style="color:#1a3d1f;">
                    R$ {{ number_format($totalStockValue, 2, ',', '.') }}
                </p>
                <p class="mt-1 text-xs" style="color:#8a9e8c;">Custo total armazenado</p>
            </div>
        </section>

        <section class="overflow-hidden rounded-2xl border bg-white shadow-sm"
            style="border-color:#d4e8d6;box-shadow:0 18px 45px rgba(26,61,31,0.06);">
            <div class="border-b px-5 py-5 sm:px-6"
                style="border-color:#d4e8d6;background:linear-gradient(135deg,#ffffff 0%,#f6fbf4 100%);">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                    <div>
                        <h2 class="mt-3 font-display text-xl font-bold tracking-tight sm:text-2xl" style="color:#142f18;">
                            Entradas x saídas
                        </h2>
                        <p class="mt-1 text-sm" style="color:#6e876f;">
                            Acompanhe o fluxo recente de estoque e o saldo operacional.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-3 lg:min-w-[520px]">
                        <div class="rounded-xl border px-4 py-3" style="border-color:#d4e8d6;background:#fbfdfb;">
                            <p class="text-xs font-bold uppercase tracking-[0.14em]" style="color:#8a9e8c;">Entradas</p>
                            <p class="mt-1 text-2xl font-bold" style="color:#166534;">{{ number_format($movementEntriesTotal, 0, ',', '.') }}</p>
                        </div>
                        <div class="rounded-xl border px-4 py-3" style="border-color:#d4e8d6;background:#fbfdfb;">
                            <p class="text-xs font-bold uppercase tracking-[0.14em]" style="color:#8a9e8c;">Saídas</p>
                            <p class="mt-1 text-2xl font-bold" style="color:#991b1b;">{{ number_format($movementExitsTotal, 0, ',', '.') }}</p>
                        </div>
                        <div class="rounded-xl border px-4 py-3" style="border-color:#d4e8d6;background:#fbfdfb;">
                            <p class="text-xs font-bold uppercase tracking-[0.14em]" style="color:#8a9e8c;">Saldo</p>
                            <div class="mt-2 flex items-center gap-2">
                                <span class="flex h-7 w-7 items-center justify-center rounded-full text-sm font-bold" style="{{ $movementBalanceStyle }}">
                                    {{ $stockTrend['icon'] }}
                                </span>
                                <span class="text-sm font-bold" style="color:#1a3d1f;">
                                    {{ $movementBalancePrefix }}{{ number_format($movementBalance30Days, 0, ',', '.') }} · {{ $movementBalanceLabel }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-4 py-5 sm:px-6">
                <div class="relative h-[320px]">
                    <canvas id="dashboardMovementChart" data-movement-series='@json($movementSeries)'></canvas>
                </div>
            </div>
        </section>

        <section class="grid grid-cols-1 gap-5 xl:grid-cols-2">
            <div class="overflow-hidden rounded-2xl border bg-white shadow-sm" style="border-color:#d4e8d6;">
                <div class="border-b px-5 py-4" style="border-color:#d4e8d6;">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h2 class="font-display text-lg font-bold" style="color:#1a3d1f;">Itens críticos</h2>
                            <p class="text-sm" style="color:#8a9e8c;">Produtos com estoque válido no mínimo ou abaixo.</p>
                        </div>
                        <span class="rounded-full px-3 py-1 text-xs font-bold" style="background:#fef2f2;color:#b91c1c;">
                            {{ $criticalStockCount }}
                        </span>
                    </div>
                </div>

                <div class="p-5">
                    @if ($criticalStock->isEmpty())
                        <div class="rounded-xl border border-dashed px-4 py-8 text-center" style="border-color:#d4e8d6;color:#8a9e8c;">
                            Nenhum alerta de estoque no momento.
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach ($criticalStock as $product)
                                <div class="flex items-center justify-between gap-3 rounded-xl border px-4 py-3" style="border-color:#edf4ee;background:#fbfdfb;">
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-bold" style="color:#1a3d1f;">{{ $product->name }}</p>
                                        <p class="text-xs" style="color:#8a9e8c;">{{ $product->category->name ?? 'Sem categoria' }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-red-700">{{ $product->available_stock_quantity }} / {{ $product->minimum_stock }}</p>
                                        <p class="text-[11px]" style="color:#8a9e8c;">válido / mínimo</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if ($criticalStockCount > 5)
                            <a href="{{ route('products.index', ['stock_status' => 'Crítico']) }}"
                                class="mt-4 inline-flex w-full items-center justify-center rounded-xl border px-4 py-2.5 text-sm font-bold transition hover:bg-red-50"
                                style="border-color:#fecaca;color:#991b1b;">
                                Ver todos os {{ $criticalStockCount }} alertas
                            </a>
                        @endif
                    @endif
                </div>
            </div>

            <div class="overflow-hidden rounded-2xl border bg-white shadow-sm" style="border-color:#d4e8d6;">
                <div class="border-b px-5 py-4" style="border-color:#d4e8d6;">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h2 class="font-display text-lg font-bold" style="color:#1a3d1f;">Vencimentos próximos</h2>
                            <p class="text-sm" style="color:#8a9e8c;">Lotes com validade nos próximos 60 dias.</p>
                        </div>
                        <span class="rounded-full px-3 py-1 text-xs font-bold" style="background:#fffbeb;color:#92400e;">
                            {{ $closeToExpiryCount }}
                        </span>
                    </div>
                </div>

                <div class="p-5">
                    @if ($closeToExpiry->isEmpty())
                        <div class="rounded-xl border border-dashed px-4 py-8 text-center" style="border-color:#d4e8d6;color:#8a9e8c;">
                            Nenhum produto vencendo em breve.
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach ($closeToExpiry as $batch)
                                @php
                                    $expiryDate = \Carbon\Carbon::parse($batch->expiration_date)->startOfDay();
                                    $today = \Carbon\Carbon::now()->startOfDay();
                                    $statusDays = $today->diffInDays($expiryDate, false);
                                    $absoluteDays = abs($statusDays);
                                    $expirationDate = $expiryDate->format('d/m/Y');
                                @endphp
                                <div class="flex items-center justify-between gap-3 rounded-xl border px-4 py-3" style="border-color:#edf4ee;background:#fbfdfb;">
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-bold" style="color:#1a3d1f;">{{ $batch->product->name }}</p>
                                        <p class="text-xs" style="color:#8a9e8c;">Lote {{ $batch->number }}</p>
                                    </div>
                                    <div class="text-right">
                                        @if ($statusDays === 0)
                                            <p class="text-sm font-bold text-red-700">Vence hoje</p>
                                        @else
                                            <p class="text-sm font-bold" style="color:#92400e;">{{ $absoluteDays }} dias</p>
                                        @endif
                                        <p class="text-[11px]" style="color:#8a9e8c;">{{ $expirationDate }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if ($closeToExpiryCount > 5)
                            <a href="{{ route('expiration-date.index', ['view' => 'batch', 'status' => 'Vence em breve', 'stock_only' => 1]) }}"
                                class="mt-4 inline-flex w-full items-center justify-center rounded-xl border px-4 py-2.5 text-sm font-bold transition hover:bg-amber-50"
                                style="border-color:#fde68a;color:#92400e;">
                                Ver todos os {{ $closeToExpiryCount }} vencimentos
                            </a>
                        @endif
                    @endif
                </div>
            </div>
        </section>

        <section class="overflow-hidden rounded-2xl border bg-white shadow-sm" style="border-color:#d4e8d6;">
            <div class="border-b px-5 py-4" style="border-color:#d4e8d6;">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <h2 class="font-display text-lg font-bold" style="color:#1a3d1f;">Sugestão de compra</h2>
                        <p class="text-sm" style="color:#8a9e8c;">Baseada no estoque válido, no estoque mínimo x 1,5 e nas saídas dos últimos 30 dias.</p>
                    </div>
                    <span class="w-fit rounded-full px-3 py-1 text-xs font-bold" style="background:#eef7ef;color:#2d6a35;">
                        {{ $criticalStockCount }} {{ $criticalStockCount === 1 ? 'item crítico' : 'itens críticos' }}
                    </span>
                </div>
            </div>

            <div class="p-5">
                @if ($purchaseSuggestions->isEmpty())
                    <div class="rounded-xl border border-dashed px-4 py-8 text-center" style="border-color:#d4e8d6;color:#8a9e8c;">
                        Nenhuma sugestão de compra no momento.
                    </div>
                @else
                    <div class="grid gap-3 lg:grid-cols-2">
                        @foreach ($purchaseSuggestions as $product)
                            <div class="rounded-xl border px-4 py-3" style="border-color:#edf4ee;background:#fbfdfb;">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-bold" style="color:#1a3d1f;">{{ $product->name }}</p>
                                        <p class="mt-1 text-xs" style="color:#8a9e8c;">
                                            Válido: {{ $product->available_stock_quantity }} · Mínimo: {{ $product->minimum_stock }} · Saídas 30 dias: {{ $product->monthly_exits }}
                                        </p>
                                    </div>
                                    <span class="shrink-0 rounded-full px-3 py-1 text-xs font-bold" style="background:#dcfce7;color:#166534;">
                                        Comprar {{ $product->suggested_purchase_quantity }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if ($criticalStockCount > 5)
                        <a href="{{ route('products.index', ['stock_status' => 'Crítico']) }}"
                            class="mt-4 inline-flex w-full items-center justify-center rounded-xl border px-4 py-2.5 text-sm font-bold transition hover:bg-agro-pale"
                            style="border-color:#d4e8d6;color:#4a5c4c;">
                            Ver todos os {{ $criticalStockCount }} itens críticos
                        </a>
                    @endif
                @endif
            </div>
        </section>
    </div>
</main>
@endsection
