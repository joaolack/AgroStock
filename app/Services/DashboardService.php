<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\StockMovement;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function data(): array
    {
        $productsWithAvailableStock = $this->productsWithAvailableStock();
        [$movementStartDate, $movementEndDate] = $this->movementPeriod();

        $criticalStockItems = $productsWithAvailableStock
            ->filter(fn (Product $product) => (int) $product->available_stock_quantity <= (int) $product->minimum_stock)
            ->sortBy([
                ['available_stock_quantity', 'asc'],
                ['name', 'asc'],
            ])
            ->values();

        $criticalStockCount = $criticalStockItems->count();
        $criticalStock = $criticalStockItems->take(5);

        $exitsLast30Days = StockMovement::query()
            ->where('type', 'exit')
            ->whereBetween('created_at', [
                $movementStartDate->copy()->utc(),
                $movementEndDate->copy()->utc(),
            ])
            ->select('product_id', DB::raw('SUM(quantity) as quantity'))
            ->groupBy('product_id')
            ->pluck('quantity', 'product_id');

        $purchaseSuggestions = $criticalStockItems
            ->take(6)
            ->map(function (Product $product) use ($exitsLast30Days) {
                $monthlyExits = (int) ($exitsLast30Days[$product->id] ?? 0);
                $safetyTarget = (int) ceil($product->minimum_stock * 1.5);
                $targetStock = max($safetyTarget, $monthlyExits);

                $product->monthly_exits = $monthlyExits;
                $product->suggested_target_stock = $targetStock;
                $product->suggested_purchase_quantity = max(0, $targetStock - (int) $product->available_stock_quantity);

                return $product;
            });

        $closeToExpiryQuery = ProductBatch::query()
            ->where('quantity', '>', 0)
            ->whereNotNull('expiration_date')
            ->whereDate('expiration_date', '>=', Carbon::now()->startOfDay())
            ->whereDate('expiration_date', '<=', Carbon::now()->addDays(60)->endOfDay());

        $closeToExpiryCount = (clone $closeToExpiryQuery)->count();
        $closeToExpiry = (clone $closeToExpiryQuery)
            ->with('product')
            ->orderBy('expiration_date', 'asc')
            ->limit(5)
            ->get();

        $movementSeries = $this->movementSeries();
        $movementEntriesTotal = $movementSeries->sum('entries');
        $movementExitsTotal = $movementSeries->sum('exits');

        return [
            'criticalStock' => $criticalStock,
            'criticalStockCount' => $criticalStockCount,
            'purchaseSuggestions' => $purchaseSuggestions,
            'closeToExpiry' => $closeToExpiry,
            'closeToExpiryCount' => $closeToExpiryCount,
            'totalProducts' => Product::count(),
            'outOfStockProducts' => $productsWithAvailableStock
                ->filter(fn (Product $product) => (int) $product->available_stock_quantity === 0)
                ->count(),
            'totalStockValue' => $productsWithAvailableStock
                ->sum(fn (Product $product) => (float) $product->cost_price * (int) $product->available_stock_quantity),
            'todayMovementsCount' => StockMovement::query()
                ->whereBetween('created_at', $this->todayPeriodForDatabase())
                ->count(),
            'movementSeries' => $movementSeries,
            'movementEntriesTotal' => $movementEntriesTotal,
            'movementExitsTotal' => $movementExitsTotal,
            'stockTrend' => $this->stockTrend($movementEntriesTotal, $movementExitsTotal),
        ];
    }

    private function productsWithAvailableStock(): Collection
    {
        $today = Carbon::now()->toDateString();

        return Product::query()
            ->with('category')
            ->withSum([
                'batches as available_stock_quantity' => function ($query) use ($today) {
                    $query->where('quantity', '>', 0)
                        ->where(function ($query) use ($today) {
                            $query->whereNull('expiration_date')
                                ->orWhereDate('expiration_date', '>=', $today);
                        });
                },
            ], 'quantity')
            ->get()
            ->map(function (Product $product) {
                $product->setAttribute(
                    'available_stock_quantity',
                    (int) ($product->available_stock_quantity ?? 0)
                );

                return $product;
            });
    }

    private function movementSeries()
    {
        [$movementStartDate, $movementEndDate] = $this->movementPeriod();
        $timezone = config('app.display_timezone');

        $movementRows = StockMovement::query()
            ->whereBetween('created_at', [
                $movementStartDate->copy()->utc(),
                $movementEndDate->copy()->utc(),
            ])
            ->get()
            ->groupBy(fn (StockMovement $movement) => $movement->created_at
                ->copy()
                ->timezone($timezone)
                ->toDateString());

        return collect(range(0, 29))->map(function (int $offset) use ($movementStartDate, $movementRows) {
            $date = $movementStartDate->copy()->addDays($offset);
            $day = $date->toDateString();
            $row = $movementRows->get($day);

            return [
                'day' => $date->format('d/m'),
                'entries' => $row ? (int) $row->where('type', 'entry')->sum('quantity') : 0,
                'exits' => $row ? (int) $row->where('type', 'exit')->sum('quantity') : 0,
            ];
        });
    }

    private function movementPeriod(): array
    {
        $now = Carbon::now(config('app.display_timezone'));

        return [
            $now->copy()->subDays(29)->startOfDay(),
            $now->copy()->endOfDay(),
        ];
    }

    private function todayPeriodForDatabase(): array
    {
        $now = Carbon::now(config('app.display_timezone'));

        return [
            $now->copy()->startOfDay()->utc(),
            $now->copy()->endOfDay()->utc(),
        ];
    }

    private function stockTrend(int $movementEntriesTotal, int $movementExitsTotal): array
    {
        return match (true) {
            $movementEntriesTotal > $movementExitsTotal => [
                'label' => 'Estoque em crescimento',
                'description' => 'Entradas maiores que saídas nos últimos 30 dias.',
                'class' => 'text-green-700',
                'badge_style' => 'background:#dcfce7;color:#166534;',
                'icon' => '+',
            ],
            $movementEntriesTotal < $movementExitsTotal => [
                'label' => 'Estoque em declínio',
                'description' => 'Saídas maiores que entradas nos últimos 30 dias.',
                'class' => 'text-red-700',
                'badge_style' => 'background:#fee2e2;color:#991b1b;',
                'icon' => '-',
            ],
            default => [
                'label' => 'Estoque estável',
                'description' => 'Entradas e saídas estão equilibradas nos últimos 30 dias.',
                'class' => 'text-yellow-700',
                'badge_style' => 'background:#fef3c7;color:#92400e;',
                'icon' => '=',
            ],
        };
    }
}
