<?php

namespace App\Services;

use App\Repositories\AnalyticsRepository;
use Carbon\Carbon;

class AnalyticsService
{
    public function __construct(
        private readonly AnalyticsRepository $analyticsRepository
    ) {}

    public function dashboardData(string $period, ?Carbon $customStart, ?Carbon $customEnd, int $staleDays): array
    {
        [$startDate, $endDate] = $this->resolvePeriod(
            $period,
            $customStart,
            $customEnd
        );
        $staleDays = max(1, $staleDays);
        $staleCutoffDate = Carbon::now()->subDays($staleDays)->endOfDay();

        $supplierDependencyDistribution = $this->analyticsRepository
            ->supplierDependencyDistribution()
            ->map(fn ($supplier) => [
                'name' => $supplier->name,
                'stock_value' => (float) $supplier->stock_value,
            ]);

        $unassignedStockValue = $this->analyticsRepository->unassignedStockValue();

        if ($unassignedStockValue > 0) {
            $supplierDependencyDistribution->push([
                'name' => 'Sem fornecedor',
                'stock_value' => $unassignedStockValue,
            ]);
        }

        return [
            'supplierRanking' => $this->supplierRanking(),
            'categoryAnalysis' => $this->categoryAnalysis(),
            'period' => $period,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'entries' => $this->analyticsRepository->movementQuantityByType($startDate, $endDate, 'entry'),
            'exits' => $this->analyticsRepository->movementQuantityByType($startDate, $endDate, 'exit'),
            'movementSeries' => $this->movementSeries($startDate, $endDate),
            'supplierDependencyDistribution' => $supplierDependencyDistribution,
            'staleDays' => $staleDays,
            'staleProducts' => $this->staleProducts($staleCutoffDate),
            'abcCurve' => $this->buildAbcCurve(),
        ];
    }

    private function supplierRanking()
    {
        return $this->analyticsRepository
            ->supplierRanking()
            ->map(fn ($supplier) => [
                'name' => $supplier->name,
                'products_count' => (int) $supplier->products_count,
                'stock_value' => (float) $supplier->stock_value,
            ]);
    }

    private function categoryAnalysis()
    {
        return $this->analyticsRepository
            ->categoryAnalysis()
            ->map(fn ($category) => [
                'name' => $category->name,
                'products_count' => (int) $category->products_count,
                'stock_quantity' => (int) $category->stock_quantity,
                'stock_value' => (float) $category->stock_value,
            ]);
    }

    private function movementSeries(Carbon $startDate, Carbon $endDate)
    {
        return $this->analyticsRepository
            ->movementSeries($startDate, $endDate)
            ->map(fn ($row) => [
                'day' => $row->day,
                'entries' => (int) $row->entries,
                'exits' => (int) $row->exits,
            ]);
    }

    private function staleProducts(Carbon $staleCutoffDate)
    {
        return $this->analyticsRepository
            ->staleProducts($staleCutoffDate)
            ->map(function ($product) {
                $lastActivityAt = Carbon::parse($product->last_activity_at);
                $lastMovementAt = $product->last_movement_at
                    ? Carbon::parse($product->last_movement_at)
                    : null;
                $daysWithoutMovement = (int) $lastActivityAt
                    ->copy()
                    ->startOfDay()
                    ->diffInDays(Carbon::now()->startOfDay());

                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'last_activity_at' => $lastActivityAt,
                    'last_movement_at' => $lastMovementAt,
                    'days_without_movement' => $daysWithoutMovement,
                    'has_movements' => (bool) $product->has_movements,
                    'valid_stock_quantity' => (int) $product->valid_stock_quantity,
                ];
            });
    }

    private function buildAbcCurve(): array
    {
        $products = $this->analyticsRepository->productsForAbcCurve();

        $totalStockValue = (float) $products->sum('stock_value');
        $cumulativeValue = 0.0;
        $summary = [
            'A' => [
                'class' => 'A',
                'products_count' => 0,
                'stock_value' => 0.0,
                'stock_percentage' => 0.0,
            ],
            'B' => [
                'class' => 'B',
                'products_count' => 0,
                'stock_value' => 0.0,
                'stock_percentage' => 0.0,
            ],
            'C' => [
                'class' => 'C',
                'products_count' => 0,
                'stock_value' => 0.0,
                'stock_percentage' => 0.0,
            ],
        ];

        $products = $products->map(function ($product) use (&$cumulativeValue, &$summary, $totalStockValue) {
            $stockValue = (float) $product->stock_value;
            $stockPercentage = $totalStockValue > 0 ? ($stockValue / $totalStockValue) * 100 : 0.0;

            $cumulativeValue += $stockValue;
            $cumulativePercentage = $totalStockValue > 0 ? ($cumulativeValue / $totalStockValue) * 100 : 0.0;
            $class = match (true) {
                $cumulativePercentage <= 80 => 'A',
                $cumulativePercentage <= 95 => 'B',
                default => 'C',
            };

            $summary[$class]['products_count']++;
            $summary[$class]['stock_value'] += $stockValue;

            return [
                'id' => $product->id,
                'name' => $product->name,
                'stock_quantity' => (int) $product->stock_quantity,
                'cost_price' => (float) $product->cost_price,
                'stock_value' => $stockValue,
                'stock_percentage' => $stockPercentage,
                'cumulative_percentage' => $cumulativePercentage,
                'class' => $class,
            ];
        });

        foreach ($summary as $class => $data) {
            $summary[$class]['stock_percentage'] = $totalStockValue > 0
                ? ($data['stock_value'] / $totalStockValue) * 100
                : 0.0;
        }

        return [
            'total_stock_value' => $totalStockValue,
            'summary' => collect($summary)->values(),
            'products' => $products,
        ];
    }

    private function resolvePeriod(string $period, ?Carbon $customStart, ?Carbon $customEnd): array
    {
        $now = Carbon::now();

        return match ($period) {
            'today' => [$now->copy()->startOfDay(), $now->copy()->endOfDay()],
            '7d' => [$now->copy()->subDays(6)->startOfDay(), $now->copy()->endOfDay()],
            '30d' => [$now->copy()->subDays(29)->startOfDay(), $now->copy()->endOfDay()],
            'month' => [$now->copy()->startOfMonth(), $now->copy()->endOfDay()],
            'custom' => [
                ($customStart ?? $now->copy()->subDays(29))->copy()->startOfDay(),
                ($customEnd ?? $now)->copy()->endOfDay(),
            ],
            default => [$now->copy()->subDays(29)->startOfDay(), $now->copy()->endOfDay()],
        };
    }
}
