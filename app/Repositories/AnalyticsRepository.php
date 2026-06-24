<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\StockMovement;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class AnalyticsRepository
{
    public function supplierRanking(int $limit = 10): Collection
    {
        return Supplier::query()
            ->active()
            ->leftJoin('products', function ($join) {
                $join->on('products.supplier_id', '=', 'suppliers.id')
                    ->where('products.stock_quantity', '>', 0);
            })
            ->select('suppliers.id', 'suppliers.name')
            ->selectRaw('COUNT(products.id) as products_count')
            ->selectRaw('COALESCE(SUM(products.stock_quantity), 0) as stock_quantity')
            ->selectRaw('COALESCE(SUM(products.cost_price * products.stock_quantity), 0) as stock_value')
            ->groupBy('suppliers.id', 'suppliers.name')
            ->havingRaw('COALESCE(SUM(products.cost_price * products.stock_quantity), 0) > 0')
            ->orderByDesc('stock_value')
            ->orderByDesc('products_count')
            ->limit($limit)
            ->get();
    }

    public function currentStockValue(): float
    {
        return (float) Product::query()
            ->where('stock_quantity', '>', 0)
            ->selectRaw('COALESCE(SUM(cost_price * stock_quantity), 0) as stock_value')
            ->value('stock_value');
    }

    public function categoryAnalysis(int $limit = 10): Collection
    {
        return Category::query()
            ->leftJoin('products', 'products.category_id', '=', 'categories.id')
            ->select('categories.id', 'categories.name')
            ->selectRaw('COUNT(products.id) as products_count')
            ->selectRaw('COALESCE(SUM(products.stock_quantity), 0) as stock_quantity')
            ->selectRaw('COALESCE(SUM(products.cost_price * products.stock_quantity), 0) as stock_value')
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('stock_value')
            ->limit($limit)
            ->get();
    }

    public function movementQuantityByType(Carbon $startDate, Carbon $endDate, string $type): int
    {
        return (int) StockMovement::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('type', $type)
            ->sum('quantity');
    }

    public function movementSeries(Carbon $startDate, Carbon $endDate): Collection
    {
        return StockMovement::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as day')
            ->selectRaw("SUM(CASE WHEN type = 'entry' THEN quantity ELSE 0 END) as entries")
            ->selectRaw("SUM(CASE WHEN type = 'exit' THEN quantity ELSE 0 END) as exits")
            ->groupBy('day')
            ->orderBy('day')
            ->get();
    }

    public function staleProducts(Carbon $staleCutoffDate, int $limit = 15): Collection
    {
        $today = Carbon::now()->toDateString();
        $validStockSubquery = ProductBatch::query()
            ->select('product_id')
            ->selectRaw('SUM(quantity) as valid_stock_quantity')
            ->where('quantity', '>', 0)
            ->where(function ($query) use ($today) {
                $query->whereNull('expiration_date')
                    ->orWhereDate('expiration_date', '>=', $today);
            })
            ->groupBy('product_id');

        $latestMovementsSubquery = StockMovement::query()
            ->selectRaw('product_id, MAX(created_at) as last_movement_at')
            ->groupBy('product_id');

        return Product::query()
            ->leftJoinSub($latestMovementsSubquery, 'latest_movements', function ($join) {
                $join->on('latest_movements.product_id', '=', 'products.id');
            })
            ->leftJoinSub($validStockSubquery, 'valid_stock', function ($join) {
                $join->on('valid_stock.product_id', '=', 'products.id');
            })
            ->whereRaw('COALESCE(valid_stock.valid_stock_quantity, 0) > 0')
            ->whereRaw('COALESCE(latest_movements.last_movement_at, products.created_at) <= ?', [$staleCutoffDate])
            ->select('products.id', 'products.name')
            ->selectRaw('COALESCE(latest_movements.last_movement_at, products.created_at) as last_activity_at')
            ->selectRaw('latest_movements.last_movement_at as last_movement_at')
            ->selectRaw('CASE WHEN latest_movements.last_movement_at IS NULL THEN 0 ELSE 1 END as has_movements')
            ->selectRaw('COALESCE(valid_stock.valid_stock_quantity, 0) as valid_stock_quantity')
            ->orderByRaw('COALESCE(latest_movements.last_movement_at, products.created_at)')
            ->limit($limit)
            ->get();
    }

    public function productsForAbcCurve(): Collection
    {
        $today = Carbon::now()->toDateString();
        $validStockSubquery = ProductBatch::query()
            ->select('product_id')
            ->selectRaw('SUM(quantity) as valid_stock_quantity')
            ->where('quantity', '>', 0)
            ->where(function ($query) use ($today) {
                $query->whereNull('expiration_date')
                    ->orWhereDate('expiration_date', '>=', $today);
            })
            ->groupBy('product_id');

        return Product::query()
            ->leftJoinSub($validStockSubquery, 'valid_stock', function ($join) {
                $join->on('valid_stock.product_id', '=', 'products.id');
            })
            ->whereRaw('COALESCE(valid_stock.valid_stock_quantity, 0) > 0')
            ->select('products.id', 'products.name', 'products.cost_price')
            ->selectRaw('COALESCE(valid_stock.valid_stock_quantity, 0) as stock_quantity')
            ->selectRaw('(products.cost_price * COALESCE(valid_stock.valid_stock_quantity, 0)) as stock_value')
            ->orderByDesc('stock_value')
            ->orderBy('products.name')
            ->get();
    }
}
