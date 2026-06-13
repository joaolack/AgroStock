<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\StockMovement;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function data(): array
    {
        $criticalStockQuery = Product::whereColumn('stock_quantity', '<=', 'minimum_stock');

        $criticalStockCount = (clone $criticalStockQuery)->count();
        $criticalStock = (clone $criticalStockQuery)
            ->with('category')
            ->orderBy('stock_quantity', 'asc')
            ->limit(5)
            ->get();

        $purchaseSuggestions = (clone $criticalStockQuery)
            ->with('category')
            ->withSum([
                'stockMovements as exits_last_30_days' => function ($query) {
                    $query->where('type', 'exit')
                        ->where('created_at', '>=', Carbon::now()->subDays(30)->startOfDay());
                },
            ], 'quantity')
            ->orderBy('stock_quantity', 'asc')
            ->limit(5)
            ->get()
            ->map(function (Product $product) {
                $monthlyExits = (int) ($product->exits_last_30_days ?? 0);
                $safetyTarget = (int) ceil($product->minimum_stock * 1.5);
                $targetStock = max($safetyTarget, $monthlyExits);

                $product->monthly_exits = $monthlyExits;
                $product->suggested_target_stock = $targetStock;
                $product->suggested_purchase_quantity = max(0, $targetStock - (int) $product->stock_quantity);

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
            'outOfStockProducts' => Product::where('stock_quantity', 0)->count(),
            'totalStockValue' => Product::select(DB::raw('SUM(cost_price * stock_quantity) as total_cost'))
                ->value('total_cost') ?? 0,
            'todayMovementsCount' => StockMovement::query()
                ->whereBetween('created_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()])
                ->count(),
            'movementSeries' => $movementSeries,
            'movementEntriesTotal' => $movementEntriesTotal,
            'movementExitsTotal' => $movementExitsTotal,
            'stockTrend' => $this->stockTrend($movementEntriesTotal, $movementExitsTotal),
        ];
    }

    private function movementSeries()
    {
        $movementStartDate = Carbon::now()->subDays(29)->startOfDay();
        $movementEndDate = Carbon::now()->endOfDay();

        $movementRows = StockMovement::query()
            ->whereBetween('created_at', [$movementStartDate, $movementEndDate])
            ->selectRaw('DATE(created_at) as day')
            ->selectRaw("SUM(CASE WHEN type = 'entry' THEN quantity ELSE 0 END) as entries")
            ->selectRaw("SUM(CASE WHEN type = 'exit' THEN quantity ELSE 0 END) as exits")
            ->groupBy('day')
            ->orderBy('day')
            ->get()
            ->keyBy('day');

        return collect(range(0, 29))->map(function (int $offset) use ($movementStartDate, $movementRows) {
            $date = $movementStartDate->copy()->addDays($offset);
            $day = $date->toDateString();
            $row = $movementRows->get($day);

            return [
                'day' => $date->format('d/m'),
                'entries' => $row ? (int) $row->entries : 0,
                'exits' => $row ? (int) $row->exits : 0,
            ];
        });
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
