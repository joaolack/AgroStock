@extends('layouts.app')

@section('slot')
@php
    $periodLabels = [
        'today' => 'Hoje',
        '7d' => 'Últimos 7 dias',
        '30d' => 'Últimos 30 dias',
        'month' => 'Mês atual',
        'custom' => 'Personalizado',
    ];

    $movementBalance = $entries - $exits;
    $movementBalancePrefix = $movementBalance > 0 ? '+' : '';
    $movementBalanceStyle = match (true) {
        $movementBalance > 0 => 'background:#dcfce7;color:#166534;',
        $movementBalance < 0 => 'background:#fee2e2;color:#991b1b;',
        default => 'background:#fef3c7;color:#92400e;',
    };
@endphp

<div class="flex min-h-screen flex-1 flex-col overflow-hidden">
    <header class="sticky top-0 z-20 flex items-center justify-between border-b bg-white/80 px-5 py-3.5 backdrop-blur-md sm:px-6"
        style="border-color:#d4e8d6;">
        <div class="flex min-w-0 items-center gap-3">
            <x-mobile-menu-button />
            <div class="min-w-0">
                <h1 class="truncate font-display text-xl font-bold tracking-tight" style="color:#1a3d1f;">Análises</h1>
                <p class="truncate text-[11px]" style="color:#8a9e8c;">Métricas e indicadores do seu negócio</p>
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
                <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <h2 class="mt-3 font-display text-xl font-bold tracking-tight sm:text-2xl" style="color:#142f18;">
                            Painel analítico
                        </h2>
                        <p class="mt-1 text-sm" style="color:#6e876f;">
                            Período atual: {{ $periodLabels[$period] ?? $period }} · {{ $startDate->format('d/m/Y') }} até {{ $endDate->format('d/m/Y') }}
                        </p>
                    </div>
                </div>
            </div>

            <form method="GET" action="{{ route('analytics.index') }}" class="px-5 py-4 sm:px-6">
                <div class="grid grid-cols-1 gap-3 md:grid-cols-[minmax(180px,0.7fr)_minmax(160px,0.55fr)_minmax(160px,0.55fr)_auto] md:items-end">
                    <div>
                        <label for="period" class="mb-1.5 block text-xs font-bold uppercase tracking-[0.14em]" style="color:#4a5c4c;">Período</label>
                        <select id="period" name="period"
                            class="h-11 w-full rounded-xl border px-3 text-sm transition-all focus:border-green-600 focus:ring-green-600"
                            style="border-color:#d4e8d6;background:#f9f6f0;color:#1a3d1f;"
                            onchange="this.form.submit()">
                            <option value="today" @selected($period === 'today')>Hoje</option>
                            <option value="7d" @selected($period === '7d')>Últimos 7 dias</option>
                            <option value="30d" @selected($period === '30d')>Últimos 30 dias</option>
                            <option value="month" @selected($period === 'month')>Mês atual</option>
                            <option value="custom" @selected($period === 'custom')>Personalizado</option>
                        </select>
                    </div>

                    <div id="startDateWrap" @if($period !== 'custom') style="display:none;" @endif>
                        <label for="start_date" class="mb-1.5 block text-xs font-bold uppercase tracking-[0.14em]" style="color:#4a5c4c;">Data inicial</label>
                        <input id="start_date" type="date" name="start_date" value="{{ $startDate->toDateString() }}"
                            class="h-11 w-full rounded-xl border px-3 text-sm transition-all focus:border-green-600 focus:ring-green-600"
                            style="border-color:#d4e8d6;background:#f9f6f0;color:#1a3d1f;">
                    </div>

                    <div id="endDateWrap" @if($period !== 'custom') style="display:none;" @endif>
                        <label for="end_date" class="mb-1.5 block text-xs font-bold uppercase tracking-[0.14em]" style="color:#4a5c4c;">Data final</label>
                        <input id="end_date" type="date" name="end_date" value="{{ $endDate->toDateString() }}"
                            class="h-11 w-full rounded-xl border px-3 text-sm transition-all focus:border-green-600 focus:ring-green-600"
                            style="border-color:#d4e8d6;background:#f9f6f0;color:#1a3d1f;">
                    </div>

                    @if($period === 'custom')
                        <button type="submit"
                            class="inline-flex h-11 items-center justify-center gap-2 rounded-xl px-4 text-sm font-bold text-white transition-all duration-200 hover:-translate-y-px"
                            style="background:#2d6a35;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 6 9 17l-5-5"/>
                            </svg>
                            Aplicar
                        </button>
                    @endif
                </div>
            </form>
        </section>

        <section class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <div class="rounded-2xl border bg-white p-5 shadow-sm" style="border-color:#d4e8d6;">
                <p class="text-sm font-semibold" style="color:#4a5c4c;">Entradas no período</p>
                <p class="mt-4 text-3xl font-bold tracking-tight" style="color:#166534;">{{ number_format($entries, 0, ',', '.') }}</p>
                <p class="mt-1 text-xs" style="color:#8a9e8c;">Quantidade movimentada para entrada</p>
            </div>
            <div class="rounded-2xl border bg-white p-5 shadow-sm" style="border-color:#d4e8d6;">
                <p class="text-sm font-semibold" style="color:#4a5c4c;">Saídas no período</p>
                <p class="mt-4 text-3xl font-bold tracking-tight" style="color:#991b1b;">{{ number_format($exits, 0, ',', '.') }}</p>
                <p class="mt-1 text-xs" style="color:#8a9e8c;">Quantidade movimentada para saída</p>
            </div>
            <div class="rounded-2xl border bg-white p-5 shadow-sm" style="border-color:#d4e8d6;">
                <p class="text-sm font-semibold" style="color:#4a5c4c;">Saldo do período</p>
                <div class="mt-4 flex items-center gap-2">
                    <span class="flex h-9 w-9 items-center justify-center rounded-full text-sm font-bold" style="{{ $movementBalanceStyle }}">
                        {{ $movementBalancePrefix }}{{ $movementBalance }}
                    </span>
                    <p class="text-2xl font-bold tracking-tight" style="color:#1a3d1f;">{{ number_format($movementBalance, 0, ',', '.') }}</p>
                </div>
                <p class="mt-1 text-xs" style="color:#8a9e8c;">Entradas menos saídas</p>
            </div>
        </section>

        <section class="overflow-hidden rounded-2xl border bg-white shadow-sm" style="border-color:#d4e8d6;">
            <div class="border-b px-5 py-5 sm:px-6" style="border-color:#d4e8d6;">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <h2 class="font-display text-lg font-bold" style="color:#1a3d1f;">Curva ABC</h2>
                        <p class="text-sm" style="color:#8a9e8c;">Classificação dos produtos pelo valor válido em estoque</p>
                    </div>
                    <div class="rounded-xl border px-4 py-3 text-left sm:text-right" style="border-color:#d4e8d6;background:#fbfdfb;">
                        <p class="text-xs font-bold uppercase tracking-[0.14em]" style="color:#8a9e8c;">Valor válido em estoque</p>
                        <p class="mt-1 text-xl font-bold" style="color:#1a3d1f;">R$ {{ number_format($abcCurve['total_stock_value'], 2, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <div class="p-5 sm:p-6">
                @if ($abcCurve['products']->isEmpty())
                    <div class="rounded-xl border border-dashed px-4 py-12 text-center" style="border-color:#d4e8d6;color:#8a9e8c;">
                        Não há produtos com estoque válido positivo para gerar a Curva ABC.
                    </div>
                @else
                    <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-3">
                        @foreach ($abcCurve['summary'] as $classSummary)
                            @php
                                $classStyles = [
                                    'A' => ['bg' => '#ecfdf3', 'border' => '#86efac', 'text' => '#166534'],
                                    'B' => ['bg' => '#fffbeb', 'border' => '#fcd34d', 'text' => '#92400e'],
                                    'C' => ['bg' => '#eff6ff', 'border' => '#93c5fd', 'text' => '#1d4ed8'],
                                ][$classSummary['class']];
                            @endphp
                            <div class="rounded-2xl border p-4" style="background:{{ $classStyles['bg'] }};border-color:{{ $classStyles['border'] }};">
                                <div class="flex items-center justify-between gap-3">
                                    <p class="text-sm font-bold" style="color:{{ $classStyles['text'] }};">Classe {{ $classSummary['class'] }}</p>
                                    <span class="rounded-full bg-white px-2 py-1 text-xs font-bold" style="color:{{ $classStyles['text'] }};">
                                        {{ number_format($classSummary['stock_percentage'], 1, ',', '.') }}%
                                    </span>
                                </div>
                                <p class="mt-3 text-2xl font-bold" style="color:#1a3d1f;">R$ {{ number_format($classSummary['stock_value'], 2, ',', '.') }}</p>
                                <p class="mt-1 text-sm" style="color:#4a5c4c;">{{ $classSummary['products_count'] }} produto{{ $classSummary['products_count'] === 1 ? '' : 's' }}</p>
                            </div>
                        @endforeach
                    </div>

                    <div class="hidden overflow-x-auto rounded-2xl border md:block" style="border-color:#d4e8d6;">
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead style="background:#f6fbf6;">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-[0.14em]" style="color:#4a5c4c;">Produto</th>
                                    <th class="px-4 py-3 text-right text-xs font-bold uppercase tracking-[0.14em]" style="color:#4a5c4c;">Qtd. válida</th>
                                    <th class="px-4 py-3 text-right text-xs font-bold uppercase tracking-[0.14em]" style="color:#4a5c4c;">Custo unit.</th>
                                    <th class="px-4 py-3 text-right text-xs font-bold uppercase tracking-[0.14em]" style="color:#4a5c4c;">Valor válido</th>
                                    <th class="px-4 py-3 text-right text-xs font-bold uppercase tracking-[0.14em]" style="color:#4a5c4c;">% acumulado</th>
                                    <th class="px-4 py-3 text-center text-xs font-bold uppercase tracking-[0.14em]" style="color:#4a5c4c;">Classe</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @foreach ($abcCurve['products'] as $abcProduct)
                                    @php
                                        $badgeStyles = [
                                            'A' => ['bg' => '#dcfce7', 'text' => '#166534'],
                                            'B' => ['bg' => '#fef3c7', 'text' => '#92400e'],
                                            'C' => ['bg' => '#dbeafe', 'text' => '#1d4ed8'],
                                        ][$abcProduct['class']];
                                    @endphp
                                    <tr class="transition-colors hover:bg-[#fbfdfb]">
                                        <td class="whitespace-nowrap px-4 py-3 text-sm font-bold" style="color:#1a3d1f;">{{ $abcProduct['name'] }}</td>
                                        <td class="whitespace-nowrap px-4 py-3 text-right text-sm" style="color:#4a5c4c;">{{ number_format($abcProduct['stock_quantity'], 0, ',', '.') }}</td>
                                        <td class="whitespace-nowrap px-4 py-3 text-right text-sm" style="color:#4a5c4c;">R$ {{ number_format($abcProduct['cost_price'], 2, ',', '.') }}</td>
                                        <td class="whitespace-nowrap px-4 py-3 text-right text-sm font-bold" style="color:#1a3d1f;">R$ {{ number_format($abcProduct['stock_value'], 2, ',', '.') }}</td>
                                        <td class="whitespace-nowrap px-4 py-3 text-right text-sm" style="color:#4a5c4c;">{{ number_format($abcProduct['cumulative_percentage'], 1, ',', '.') }}%</td>
                                        <td class="whitespace-nowrap px-4 py-3 text-center">
                                            <span class="inline-flex min-w-8 items-center justify-center rounded-full px-2.5 py-1 text-xs font-bold" style="background:{{ $badgeStyles['bg'] }};color:{{ $badgeStyles['text'] }};">
                                                {{ $abcProduct['class'] }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="grid gap-3 md:hidden">
                        @foreach ($abcCurve['products'] as $abcProduct)
                            @php
                                $badgeStyles = [
                                    'A' => ['bg' => '#dcfce7', 'text' => '#166534'],
                                    'B' => ['bg' => '#fef3c7', 'text' => '#92400e'],
                                    'C' => ['bg' => '#dbeafe', 'text' => '#1d4ed8'],
                                ][$abcProduct['class']];
                            @endphp
                            <article class="rounded-2xl border p-4" style="border-color:#d4e8d6;background:#fbfdfb;">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <h3 class="break-words text-sm font-bold" style="color:#1a3d1f;">{{ $abcProduct['name'] }}</h3>
                                        <p class="mt-1 text-xs" style="color:#8a9e8c;">Qtd. válida {{ number_format($abcProduct['stock_quantity'], 0, ',', '.') }} · {{ number_format($abcProduct['cumulative_percentage'], 1, ',', '.') }}% acumulado</p>
                                    </div>
                                    <span class="inline-flex min-w-8 items-center justify-center rounded-full px-2.5 py-1 text-xs font-bold" style="background:{{ $badgeStyles['bg'] }};color:{{ $badgeStyles['text'] }};">
                                        {{ $abcProduct['class'] }}
                                    </span>
                                </div>
                                <p class="mt-3 text-lg font-bold" style="color:#1a3d1f;">R$ {{ number_format($abcProduct['stock_value'], 2, ',', '.') }}</p>
                            </article>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>

        <section class="overflow-hidden rounded-2xl border bg-white shadow-sm" style="border-color:#d4e8d6;">
            <div class="border-b px-5 py-4" style="border-color:#d4e8d6;">
                <h2 class="font-display text-lg font-bold" style="color:#1a3d1f;">Movimentação de estoque</h2>
                <p class="text-sm" style="color:#8a9e8c;">Entradas e saídas no período selecionado</p>
            </div>
            <div class="p-5 sm:p-6">
                <div class="relative h-[320px]">
                    <canvas id="movementChart"></canvas>
                </div>
            </div>
        </section>

        <section class="overflow-hidden rounded-2xl border bg-white shadow-sm" style="border-color:#d4e8d6;">
            <div class="border-b px-5 py-4" style="border-color:#d4e8d6;">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h2 class="font-display text-lg font-bold" style="color:#1a3d1f;">Produtos parados</h2>
                        <p class="text-sm" style="color:#8a9e8c;">Produtos com estoque v&aacute;lido sem movimenta&ccedil;&atilde;o h&aacute; pelo menos {{ $staleDays }} dias</p>
                    </div>
                    <form method="GET" action="{{ route('analytics.index') }}" class="flex flex-col gap-2 sm:flex-row sm:items-end">
                        <input type="hidden" name="period" value="{{ $period }}">
                        <input type="hidden" name="start_date" value="{{ $startDate->toDateString() }}">
                        <input type="hidden" name="end_date" value="{{ $endDate->toDateString() }}">
                        <div>
                            <label for="stale_days" class="mb-1.5 block text-xs font-bold uppercase tracking-[0.14em]" style="color:#4a5c4c;">Dias sem movimentação</label>
                            <input id="stale_days" type="number" min="1" name="stale_days" value="{{ $staleDays }}"
                                class="h-11 w-full rounded-xl border px-3 text-sm sm:w-32"
                                style="border-color:#d4e8d6;background:#f9f6f0;color:#1a3d1f;">
                        </div>
                        <button type="submit"
                            class="inline-flex h-11 items-center justify-center gap-2 rounded-xl px-4 text-sm font-bold text-white transition-all duration-200 hover:-translate-y-px"
                            style="background:#2d6a35;">
                            Aplicar
                        </button>
                    </form>
                </div>
            </div>

            <div class="p-5 sm:p-6">
                @if ($staleProducts->isEmpty())
                    <div class="rounded-xl border border-dashed px-4 py-10 text-center" style="border-color:#d4e8d6;color:#8a9e8c;">
                        Nenhum produto com estoque válido sem movimentações há {{ $staleDays }} dias ou mais.
                    </div>
                @else
                    <div class="grid gap-3 lg:grid-cols-2">
                        @foreach ($staleProducts as $staleProduct)
                            <div class="rounded-xl border px-4 py-3" style="border-color:#edf4ee;background:#fbfdfb;">
                                <p class="text-sm" style="color:#1a3d1f;">
                                    <span class="font-bold">{{ $staleProduct['name'] }}</span>
                                    está há <span class="font-bold">{{ $staleProduct['days_without_movement'] }} dias</span> sem movimentação.
                                </p>
                                <p class="mt-1 text-xs" style="color:#8a9e8c;">
                                    Estoque válido: {{ number_format($staleProduct['valid_stock_quantity'], 0, ',', '.') }}.
                                    @if ($staleProduct['has_movements'])
                                        Última movimentação em {{ $staleProduct['last_movement_at']->format('d/m/Y H:i') }}.
                                    @else
                                        Sem movimentação registrada desde o cadastro em {{ $staleProduct['last_activity_at']->format('d/m/Y H:i') }}.
                                    @endif
                                </p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>

        <section class="grid grid-cols-1 gap-5 xl:grid-cols-2">
            <div class="overflow-hidden rounded-2xl border bg-white shadow-sm" style="border-color:#d4e8d6;">
                <div class="border-b px-5 py-4" style="border-color:#d4e8d6;">
                    <h2 class="font-display text-lg font-bold" style="color:#1a3d1f;">Distribuição de dependência</h2>
                    <p class="text-sm" style="color:#8a9e8c;">Percentual do valor em estoque por fornecedor</p>
                </div>

                <div class="p-5 sm:p-6">
                    @if ($supplierDependencyDistribution->isEmpty())
                        <div class="rounded-xl border border-dashed px-4 py-12 text-center" style="border-color:#d4e8d6;color:#8a9e8c;">
                            Não há estoque com fornecedor para gerar a distribuição.
                        </div>
                    @else
                        <div class="relative h-[260px]">
                            <canvas id="supplierDependencyChart"></canvas>
                        </div>
                    @endif
                </div>
            </div>

            <div class="overflow-hidden rounded-2xl border bg-white shadow-sm" style="border-color:#d4e8d6;">
                <div class="border-b px-5 py-4" style="border-color:#d4e8d6;">
                    <h2 class="font-display text-lg font-bold" style="color:#1a3d1f;">Análise de categorias</h2>
                    <p class="text-sm" style="color:#8a9e8c;">Top 10 categorias por valor em estoque</p>
                </div>

                <div class="p-5 sm:p-6">
                    @if ($categoryAnalysis->isEmpty())
                        <div class="rounded-xl border border-dashed px-4 py-12 text-center" style="border-color:#d4e8d6;color:#8a9e8c;">
                            Nenhuma categoria encontrada para gerar a análise.
                        </div>
                    @else
                        <div class="relative h-[420px]">
                            <canvas id="categoryChart"></canvas>
                        </div>
                    @endif
                </div>
            </div>
        </section>

        <section class="overflow-hidden rounded-2xl border bg-white shadow-sm" style="border-color:#d4e8d6;">
            <div class="border-b px-5 py-4" style="border-color:#d4e8d6;">
                <h2 class="font-display text-lg font-bold" style="color:#1a3d1f;">Ranking de fornecedores</h2>
                <p class="text-sm" style="color:#8a9e8c;">Top 10 por quantidade de produtos e valor total em estoque</p>
            </div>

            <div class="p-5 sm:p-6">
                @if ($supplierRanking->isEmpty())
                    <div class="rounded-xl border border-dashed px-4 py-12 text-center" style="border-color:#d4e8d6;color:#8a9e8c;">
                        Nenhum fornecedor encontrado para gerar o ranking.
                    </div>
                @else
                    <div class="relative h-[420px]">
                        <canvas id="supplierChart"></canvas>
                    </div>
                @endif
            </div>
        </section>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            const periodEl = document.getElementById('period');
            const startDateWrap = document.getElementById('startDateWrap');
            const endDateWrap = document.getElementById('endDateWrap');

            if (periodEl && startDateWrap && endDateWrap) {
                periodEl.addEventListener('change', () => {
                    const isCustom = periodEl.value === 'custom';
                    startDateWrap.style.display = isCustom ? 'block' : 'none';
                    endDateWrap.style.display = isCustom ? 'block' : 'none';
                });
            }
        </script>

        <script>
            const movementCtx = document.getElementById('movementChart');
            const movementSeries = @json($movementSeries);

            if (movementCtx) {
                new Chart(movementCtx, {
                    type: 'bar',
                    data: {
                        labels: movementSeries.map((row) => row.day),
                        datasets: [
                            {
                                label: 'Entradas',
                                data: movementSeries.map((row) => row.entries),
                                backgroundColor: 'rgba(45,106,53,0.82)',
                                borderColor: '#2d6a35',
                                borderWidth: 1,
                                borderRadius: 6,
                                borderSkipped: false,
                                barPercentage: 0.72,
                                categoryPercentage: 0.78
                            },
                            {
                                label: 'Saídas',
                                data: movementSeries.map((row) => row.exits),
                                backgroundColor: 'rgba(180,83,9,0.78)',
                                borderColor: '#b45309',
                                borderWidth: 1,
                                borderRadius: 6,
                                borderSkipped: false,
                                barPercentage: 0.72,
                                categoryPercentage: 0.78
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            mode: 'index',
                            intersect: false
                        },
                        plugins: {
                            legend: {
                                labels: {
                                    color: '#1a3d1f',
                                    font: {
                                        weight: '600'
                                    }
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label(context) {
                                        return `${context.dataset.label}: ${context.parsed.y || 0}`;
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                ticks: {
                                    color: '#4a5c4c'
                                },
                                grid: {
                                    display: false
                                }
                            },
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    color: '#4a5c4c',
                                    precision: 0
                                },
                                grid: {
                                    color: 'rgba(138,158,140,0.18)',
                                    lineWidth: 1
                                }
                            }
                        }
                    }
                });
            }
        </script>

        @if ($supplierDependencyDistribution->isNotEmpty())
        <script>
            const supplierDependencyCtx = document.getElementById('supplierDependencyChart');
            const supplierDependencyDistribution = @json($supplierDependencyDistribution);
            const supplierDependencyPalette = [
                '#2d6a35', '#4caf50', '#84cc16', '#f59e0b', '#d97706',
                '#0ea5a4', '#14b8a6', '#22c55e', '#65a30d', '#15803d'
            ];
            const totalDependencyStock = supplierDependencyDistribution.reduce((total, item) => total + item.stock_value, 0);
            const dependencyValueFormatter = new Intl.NumberFormat('pt-BR', {
                style: 'currency',
                currency: 'BRL'
            });

            if (supplierDependencyCtx) {
                new Chart(supplierDependencyCtx, {
                    type: 'bar',
                    data: {
                        labels: ['Dependência'],
                        datasets: supplierDependencyDistribution.map((supplier, index) => {
                            const percent = totalDependencyStock > 0 ? (supplier.stock_value / totalDependencyStock) * 100 : 0;

                            return {
                                label: supplier.name,
                                data: [percent],
                                stockValue: supplier.stock_value,
                                backgroundColor: supplierDependencyPalette[index % supplierDependencyPalette.length],
                                borderColor: '#ffffff',
                                borderWidth: 1,
                                borderRadius: 6,
                                borderSkipped: false,
                                barPercentage: 0.55,
                                categoryPercentage: 0.8
                            };
                        })
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label(context) {
                                        const percent = context.parsed.x || 0;
                                        const formattedValue = dependencyValueFormatter.format(context.dataset.stockValue || 0);

                                        return `${context.dataset.label}: ${percent.toFixed(1)}% (${formattedValue})`;
                                    }
                                }
                            },
                            legend: {
                                position: 'bottom',
                                labels: {
                                    color: '#1a3d1f',
                                    font: {
                                        weight: '600'
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                stacked: true,
                                min: 0,
                                max: 100,
                                ticks: {
                                    color: '#4a5c4c',
                                    callback(value) {
                                        return `${value}%`;
                                    }
                                },
                                grid: {
                                    color: 'rgba(138,158,140,0.16)'
                                }
                            },
                            y: {
                                stacked: true,
                                ticks: {
                                    color: '#1a3d1f',
                                    font: {
                                        weight: '600'
                                    }
                                },
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }
        </script>
        @endif

        @if ($supplierRanking->isNotEmpty())
        <script>
            const supplierCtx = document.getElementById('supplierChart');
            const supplierRanking = @json($supplierRanking);
            const supplierValueFormatter = new Intl.NumberFormat('pt-BR', {
                style: 'currency',
                currency: 'BRL',
                maximumFractionDigits: 0
            });
            const supplierPalette = ['#2d6a35', '#4caf50', '#0ea5a4', '#84cc16', '#f59e0b', '#15803d'];

            new Chart(supplierCtx, {
                type: 'bar',
                data: {
                    labels: supplierRanking.map((supplier) => supplier.name),
                    datasets: [
                        {
                            label: 'Valor em estoque',
                            data: supplierRanking.map((supplier) => supplier.stock_value),
                            backgroundColor: supplierRanking.map((_, index) => supplierPalette[index % supplierPalette.length]),
                            borderColor: 'rgba(45,106,53,0.22)',
                            borderWidth: 1,
                            borderRadius: 8,
                            borderSkipped: false,
                            barPercentage: 0.68,
                            categoryPercentage: 0.78
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'nearest',
                        intersect: false
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label(context) {
                                    return `${context.dataset.label}: ${supplierValueFormatter.format(context.parsed.y || 0)}`;
                                },
                                afterLabel(context) {
                                    const supplier = supplierRanking[context.dataIndex];

                                    return `Produtos vinculados: ${supplier.products_count}`;
                                }
                            }
                        },
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                autoSkip: false,
                                color: '#1a3d1f',
                                maxRotation: 0,
                                minRotation: 0,
                                font: {
                                    weight: '600'
                                },
                                callback(value) {
                                    const label = this.getLabelForValue(value);

                                    return label.length > 14 ? `${label.slice(0, 14)}...` : label;
                                }
                            },
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: '#4a5c4c',
                                callback(value) {
                                    return supplierValueFormatter.format(value);
                                }
                            },
                            grid: {
                                color: 'rgba(138,158,140,0.16)'
                            }
                        }
                    }
                }
            });
        </script>
        @endif

        @if ($categoryAnalysis->isNotEmpty())
        <script>
            const categoryCtx = document.getElementById('categoryChart');
            const categoryAnalysis = @json($categoryAnalysis);
            const categoryValueFormatter = new Intl.NumberFormat('pt-BR', {
                style: 'currency',
                currency: 'BRL',
                maximumFractionDigits: 0
            });
            const categoryPalette = ['#0f766e', '#2d6a35', '#4caf50', '#84cc16', '#f59e0b', '#0ea5a4'];

            new Chart(categoryCtx, {
                type: 'bar',
                data: {
                    labels: categoryAnalysis.map((category) => category.name),
                    datasets: [
                        {
                            label: 'Valor em estoque',
                            data: categoryAnalysis.map((category) => category.stock_value),
                            backgroundColor: categoryAnalysis.map((_, index) => categoryPalette[index % categoryPalette.length]),
                            borderColor: 'rgba(15,118,110,0.22)',
                            borderWidth: 1,
                            borderRadius: 8,
                            borderSkipped: false,
                            barPercentage: 0.68,
                            categoryPercentage: 0.78
                        }
                    ]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'nearest',
                        intersect: false
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label(context) {
                                    return `${context.dataset.label}: ${categoryValueFormatter.format(context.parsed.x || 0)}`;
                                },
                                afterLabel(context) {
                                    const category = categoryAnalysis[context.dataIndex];

                                    return [
                                        `Produtos: ${category.products_count}`,
                                        `Quantidade em estoque: ${category.stock_quantity}`
                                    ];
                                }
                            }
                        },
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: {
                                color: '#4a5c4c',
                                callback(value) {
                                    return categoryValueFormatter.format(value);
                                }
                            },
                            grid: {
                                color: 'rgba(138,158,140,0.16)'
                            }
                        },
                        y: {
                            ticks: {
                                autoSkip: false,
                                color: '#1a3d1f',
                                font: {
                                    weight: '600'
                                }
                            },
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        </script>
        @endif
    </div>
</div>
@endsection
