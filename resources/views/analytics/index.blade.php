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
                <h1 class="font-display text-xl font-bold tracking-tight" style="color:#1a3d1f;">Análises</h1>
                <p class="text-[11px]" style="color:#8a9e8c;">Análise métricas do seu négocio</p>
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
        <div class="bg-white rounded-xl shadow-sm border p-4" style="border-color:#d4e8d6;">
            <form method="GET" action="{{ route('analytics.index') }}" class="flex flex-col md:flex-row md:items-end gap-3">
                <div>
                    <label for="period" class="block text-xs mb-1" style="color:#4a5c4c;">Periodo</label>
                    <select id="period" name="period" class="rounded-lg border text-sm px-3 py-2" style="border-color:#d4e8d6;" onchange="this.form.submit()">
                        <option value="today" @selected($period === 'today')>Hoje</option>
                        <option value="7d" @selected($period === '7d')>Ultimos 7 dias</option>
                        <option value="30d" @selected($period === '30d')>Ultimos 30 dias</option>
                        <option value="month" @selected($period === 'month')>Mes atual</option>
                        <option value="custom" @selected($period === 'custom')>Personalizado</option>
                    </select>
                </div>

                <div id="startDateWrap" @if($period !== 'custom') style="display:none;" @endif>
                    <label for="start_date" class="block text-xs mb-1" style="color:#4a5c4c;">Inicio</label>
                    <input id="start_date" type="date" name="start_date" value="{{ $startDate->toDateString() }}" class="rounded-lg border text-sm px-3 py-2" style="border-color:#d4e8d6;">
                </div>

                <div id="endDateWrap" @if($period !== 'custom') style="display:none;" @endif>
                    <label for="end_date" class="block text-xs mb-1" style="color:#4a5c4c;">Fim</label>
                    <input id="end_date" type="date" name="end_date" value="{{ $endDate->toDateString() }}" class="rounded-lg border text-sm px-3 py-2" style="border-color:#d4e8d6;">
                </div>

                @if($period === 'custom')
                    <button type="submit" class="px-4 py-2 rounded-lg text-sm font-semibold text-white" style="background:#2d6a35;">Aplicar</button>
                @endif
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white rounded-xl shadow-sm border p-5" style="border-color:#d4e8d6;">
                <p class="text-xs uppercase tracking-wide" style="color:#8a9e8c;">Entradas no periodo</p>
                <p class="text-2xl font-bold mt-2" style="color:#1a3d1f;">{{ number_format($entries, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border p-5" style="border-color:#d4e8d6;">
                <p class="text-xs uppercase tracking-wide" style="color:#8a9e8c;">Saidas no periodo</p>
                <p class="text-2xl font-bold mt-2" style="color:#1a3d1f;">{{ number_format($exits, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border p-6" style="border-color:#d4e8d6;">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-5">
                <div>
                    <h2 class="text-lg font-bold" style="color:#1a3d1f;">Movimentação de estoque</h2>
                    <p class="text-sm" style="color:#8a9e8c;">Entradas e saídas no periodo selecionado</p>
                </div>
            </div>
            <div class="relative h-[320px]">
                <canvas id="movementChart"></canvas>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border p-6" style="border-color:#d4e8d6;">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 mb-5">
                <div>
                    <h2 class="text-lg font-bold" style="color:#1a3d1f;">Produtos parados</h2>
                    <p class="text-sm" style="color:#8a9e8c;">Produtos em estoque sem movimentação há pelo menos X dias</p>
                </div>
                <form method="GET" action="{{ route('analytics.index') }}" class="flex items-end gap-2">
                    <input type="hidden" name="period" value="{{ $period }}">
                    <input type="hidden" name="start_date" value="{{ $startDate->toDateString() }}">
                    <input type="hidden" name="end_date" value="{{ $endDate->toDateString() }}">
                    <div>
                        <label for="stale_days" class="block text-xs mb-1" style="color:#4a5c4c;">Dias sem movimentação</label>
                        <input
                            id="stale_days"
                            type="number"
                            min="1"
                            name="stale_days"
                            value="{{ $staleDays }}"
                            class="w-28 rounded-lg border text-sm px-3 py-2"
                            style="border-color:#d4e8d6;"
                        >
                    </div>
                    <button type="submit" class="px-4 py-2 rounded-lg text-sm font-semibold text-white" style="background:#2d6a35;">Aplicar</button>
                </form>
            </div>

            @if ($staleProducts->isEmpty())
                <div class="py-10 text-center rounded-lg border border-dashed" style="border-color:#d4e8d6;color:#8a9e8c;">
                    Nenhum produto em estoque sem movimentação há {{ $staleDays }} dias ou mais.
                </div>
            @else
                <div class="space-y-3">
                    @foreach ($staleProducts as $staleProduct)
                        <div class="rounded-lg border px-4 py-3" style="border-color:#d4e8d6;">
                            <p class="text-sm" style="color:#1a3d1f;">
                                <span class="font-semibold">{{ $staleProduct['name'] }}</span>
                                esta há
                                <span class="font-semibold">{{ $staleProduct['days_without_movement'] }} dias</span>
                                sem movimentação.
                            </p>
                            <p class="text-xs mt-1" style="color:#8a9e8c;">
                                Última movimentação em {{ $staleProduct['last_movement_at']->format('d/m/Y H:i') }}.
                            </p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        <div class="bg-white rounded-xl shadow-sm border p-6" style="border-color:#d4e8d6;">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-5">
                <div>
                    <h2 class="text-lg font-bold" style="color:#1a3d1f;">Distribuicao de dependencia</h2>
                    <p class="text-sm" style="color:#8a9e8c;">Percentual do valor em estoque por fornecedor</p>
                </div>
            </div>

            @if ($supplierDependencyDistribution->isEmpty())
                <div class="py-12 text-center rounded-lg border border-dashed" style="border-color:#d4e8d6;color:#8a9e8c;">
                    Nao ha estoque com fornecedor para gerar a distribuicao.
                </div>
            @else
                <div class="relative h-[360px]">
                    <canvas id="supplierDependencyChart"></canvas>
                </div>
            @endif
        </div>

        <div class="bg-white rounded-xl shadow-sm border p-6" style="border-color:#d4e8d6;">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-5">
                <div>
                    <h2 class="text-lg font-bold" style="color:#1a3d1f;">Analise de categorias</h2>
                    <p class="text-sm" style="color:#8a9e8c;">Top 10 categorias por valor em estoque</p>
                </div>
            </div>

            @if ($categoryAnalysis->isEmpty())
                <div class="py-12 text-center rounded-lg border border-dashed" style="border-color:#d4e8d6;color:#8a9e8c;">
                    Nenhuma categoria encontrada para gerar a analise.
                </div>
            @else
                <div class="relative h-[420px]">
                    <canvas id="categoryChart"></canvas>
                </div>
            @endif
        </div>

        <div class="bg-white rounded-xl shadow-sm border p-6" style="border-color:#d4e8d6;">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-5">
                <div>
                    <h2 class="text-lg font-bold" style="color:#1a3d1f;">Ranking de fornecedores</h2>
                    <p class="text-sm" style="color:#8a9e8c;">Top 10 por quantidade de produtos e valor total em estoque</p>
                </div>
            </div>

            @if ($supplierRanking->isEmpty())
                <div class="py-12 text-center rounded-lg border border-dashed" style="border-color:#d4e8d6;color:#8a9e8c;">
                    Nenhum fornecedor encontrado para gerar o ranking.
                </div>
            @else
                <div class="relative h-[420px]">
                    <canvas id="supplierChart"></canvas>
                </div>
            @endif
        </div>

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
                    type: 'line',
                    data: {
                        labels: movementSeries.map((row) => row.day),
                        datasets: [
                            {
                                label: 'Entradas',
                                data: movementSeries.map((row) => row.entries),
                                borderColor: '#2d6a35',
                                backgroundColor: 'rgba(45,106,53,0.12)',
                                tension: 0.3,
                                fill: true
                            },
                            {
                                label: 'Saidas',
                                data: movementSeries.map((row) => row.exits),
                                borderColor: '#b45309',
                                backgroundColor: 'rgba(180,83,9,0.10)',
                                tension: 0.3,
                                fill: true
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

            if (supplierDependencyCtx) {
                new Chart(supplierDependencyCtx, {
                    type: 'pie',
                    data: {
                        labels: supplierDependencyDistribution.map((supplier) => supplier.name),
                        datasets: [{
                            data: supplierDependencyDistribution.map((supplier) => supplier.stock_value),
                            backgroundColor: supplierDependencyDistribution.map((_, index) => supplierDependencyPalette[index % supplierDependencyPalette.length]),
                            borderColor: '#ffffff',
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label(context) {
                                        const value = context.parsed || 0;
                                        const percent = totalDependencyStock > 0 ? (value / totalDependencyStock) * 100 : 0;
                                        const formattedValue = new Intl.NumberFormat('pt-BR', {
                                            style: 'currency',
                                            currency: 'BRL'
                                        }).format(value);

                                        return `${context.label}: ${percent.toFixed(1)}% (${formattedValue})`;
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
                        }
                    }
                });
            }
        </script>
        @endif

        @if ($supplierRanking->isNotEmpty())
        <script>
            const ctx = document.getElementById('supplierChart');
            const supplierRanking = @json($supplierRanking);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: supplierRanking.map((supplier) => supplier.name),
                    datasets: [
                        {
                            label: 'Produtos',
                            data: supplierRanking.map((supplier) => supplier.products_count),
                            backgroundColor: '#4caf50',
                            borderColor: '#2d6a35',
                            borderWidth: 1,
                            borderRadius: 6,
                            yAxisID: 'products'
                        },
                        {
                            label: 'Valor em estoque',
                            data: supplierRanking.map((supplier) => supplier.stock_value),
                            backgroundColor: '#f59e0b',
                            borderColor: '#b45309',
                            borderWidth: 1,
                            borderRadius: 6,
                            yAxisID: 'stockValue'
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
                        tooltip: {
                            callbacks: {
                                label(context) {
                                    if (context.dataset.yAxisID === 'stockValue') {
                                        return `${context.dataset.label}: ${new Intl.NumberFormat('pt-BR', {
                                            style: 'currency',
                                            currency: 'BRL'
                                        }).format(context.parsed.y)}`;
                                    }

                                    return `${context.dataset.label}: ${context.parsed.y}`;
                                }
                            }
                        },
                        legend: {
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
                            ticks: {
                                color: '#4a5c4c'
                            },
                            grid: {
                                display: false
                            }
                        },
                        products: {
                            type: 'linear',
                            position: 'left',
                            beginAtZero: true,
                            ticks: {
                                color: '#2d6a35',
                                precision: 0
                            },
                            title: {
                                display: true,
                                text: 'Produtos',
                                color: '#2d6a35'
                            }
                        },
                        stockValue: {
                            type: 'linear',
                            position: 'right',
                            beginAtZero: true,
                            grid: {
                                drawOnChartArea: false
                            },
                            ticks: {
                                color: '#b45309',
                                callback(value) {
                                    return new Intl.NumberFormat('pt-BR', {
                                        style: 'currency',
                                        currency: 'BRL',
                                        maximumFractionDigits: 0
                                    }).format(value);
                                }
                            },
                            title: {
                                display: true,
                                text: 'Valor em estoque',
                                color: '#b45309'
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

            new Chart(categoryCtx, {
                type: 'bar',
                data: {
                    labels: categoryAnalysis.map((category) => category.name),
                    datasets: [
                        {
                            label: 'Produtos',
                            data: categoryAnalysis.map((category) => category.products_count),
                            backgroundColor: '#84cc16',
                            borderColor: '#4d7c0f',
                            borderWidth: 1,
                            borderRadius: 6,
                            yAxisID: 'products'
                        },
                        {
                            label: 'Valor em estoque',
                            data: categoryAnalysis.map((category) => category.stock_value),
                            backgroundColor: '#0ea5a4',
                            borderColor: '#0f766e',
                            borderWidth: 1,
                            borderRadius: 6,
                            yAxisID: 'stockValue'
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
                        tooltip: {
                            callbacks: {
                                label(context) {
                                    if (context.dataset.yAxisID === 'stockValue') {
                                        return `${context.dataset.label}: ${new Intl.NumberFormat('pt-BR', {
                                            style: 'currency',
                                            currency: 'BRL'
                                        }).format(context.parsed.y)}`;
                                    }

                                    return `${context.dataset.label}: ${context.parsed.y}`;
                                }
                            }
                        },
                        legend: {
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
                            ticks: {
                                color: '#4a5c4c'
                            },
                            grid: {
                                display: false
                            }
                        },
                        products: {
                            type: 'linear',
                            position: 'left',
                            beginAtZero: true,
                            ticks: {
                                color: '#4d7c0f',
                                precision: 0
                            },
                            title: {
                                display: true,
                                text: 'Produtos',
                                color: '#4d7c0f'
                            }
                        },
                        stockValue: {
                            type: 'linear',
                            position: 'right',
                            beginAtZero: true,
                            grid: {
                                drawOnChartArea: false
                            },
                            ticks: {
                                color: '#0f766e',
                                callback(value) {
                                    return new Intl.NumberFormat('pt-BR', {
                                        style: 'currency',
                                        currency: 'BRL',
                                        maximumFractionDigits: 0
                                    }).format(value);
                                }
                            },
                            title: {
                                display: true,
                                text: 'Valor em estoque',
                                color: '#0f766e'
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
