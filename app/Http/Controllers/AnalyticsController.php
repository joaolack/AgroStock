<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $period = $request->string('period', '30d')->value();
        $staleDays = max(1, (int) $request->integer('stale_days', 90));
        [$startDate, $endDate] = $this->resolvePeriod(
            $period,
            $request->date('start_date'),
            $request->date('end_date')
        );
        $staleCutoffDate = Carbon::now()->subDays($staleDays)->endOfDay();

        $supplierRanking = Supplier::query()
            ->active()
            ->leftJoin('products', 'products.supplier_id', '=', 'suppliers.id')
            ->select('suppliers.id', 'suppliers.name')
            ->selectRaw('COUNT(products.id) as products_count')
            ->selectRaw('COALESCE(SUM(products.cost_price * products.stock_quantity), 0) as stock_value')
            ->groupBy('suppliers.id', 'suppliers.name')
            ->orderByDesc('products_count')
            ->orderByDesc('stock_value')
            ->limit(10)
            ->get()
            ->map(fn ($supplier) => [
                'name' => $supplier->name,
                'products_count' => (int) $supplier->products_count,
                'stock_value' => (float) $supplier->stock_value,
            ]);

        $categoryAnalysis = Category::query()
            ->leftJoin('products', 'products.category_id', '=', 'categories.id')
            ->select('categories.id', 'categories.name')
            ->selectRaw('COUNT(products.id) as products_count')
            ->selectRaw('COALESCE(SUM(products.stock_quantity), 0) as stock_quantity')
            ->selectRaw('COALESCE(SUM(products.cost_price * products.stock_quantity), 0) as stock_value')
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('stock_value')
            ->limit(10)
            ->get()
            ->map(fn ($category) => [
                'name' => $category->name,
                'products_count' => (int) $category->products_count,
                'stock_quantity' => (int) $category->stock_quantity,
                'stock_value' => (float) $category->stock_value,
            ]);

        $supplierDependencyDistribution = Supplier::query()
            ->active()
            ->leftJoin('products', function ($join) {
                $join->on('products.supplier_id', '=', 'suppliers.id')
                    ->where('products.stock_quantity', '>', 0);
            })
            ->select('suppliers.name')
            ->selectRaw('COALESCE(SUM(products.cost_price * products.stock_quantity), 0) as stock_value')
            ->groupBy('suppliers.id', 'suppliers.name')
            ->havingRaw('COALESCE(SUM(products.cost_price * products.stock_quantity), 0) > 0')
            ->orderByDesc('stock_value')
            ->get()
            ->map(fn ($supplier) => [
                'name' => $supplier->name,
                'stock_value' => (float) $supplier->stock_value,
            ]);

        $unassignedStockValue = Product::query()
            ->whereNull('supplier_id')
            ->where('stock_quantity', '>', 0)
            ->selectRaw('COALESCE(SUM(cost_price * stock_quantity), 0) as stock_value')
            ->value('stock_value');

        if ((float) $unassignedStockValue > 0) {
            $supplierDependencyDistribution->push([
                'name' => 'Sem fornecedor',
                'stock_value' => (float) $unassignedStockValue,
            ]);
        }

        $movementBaseQuery = StockMovement::query()
            ->whereBetween('created_at', [$startDate, $endDate]);

        $entries = (clone $movementBaseQuery)
            ->where('type', 'entry')
            ->sum('quantity');

        $exits = (clone $movementBaseQuery)
            ->where('type', 'exit')
            ->sum('quantity');

        $movementSeries = (clone $movementBaseQuery)
            ->selectRaw('DATE(created_at) as day')
            ->selectRaw("SUM(CASE WHEN type = 'entry' THEN quantity ELSE 0 END) as entries")
            ->selectRaw("SUM(CASE WHEN type = 'exit' THEN quantity ELSE 0 END) as exits")
            ->groupBy('day')
            ->orderBy('day')
            ->get()
            ->map(fn ($row) => [
                'day' => $row->day,
                'entries' => (int) $row->entries,
                'exits' => (int) $row->exits,
            ]);

        $latestMovementsSubquery = StockMovement::query()
            ->selectRaw('product_id, MAX(created_at) as last_movement_at')
            ->groupBy('product_id');

        $staleProducts = Product::query()
            ->leftJoinSub($latestMovementsSubquery, 'latest_movements', function ($join) {
                $join->on('latest_movements.product_id', '=', 'products.id');
            })
            ->where('products.stock_quantity', '>', 0)
            ->whereNotNull('latest_movements.last_movement_at')
            ->where('latest_movements.last_movement_at', '<=', $staleCutoffDate)
            ->select('products.id', 'products.name', 'latest_movements.last_movement_at')
            ->orderBy('latest_movements.last_movement_at')
            ->limit(15)
            ->get()
            ->map(function ($product) {
                $lastMovementAt = Carbon::parse($product->last_movement_at);
                $daysWithoutMovement = (int) $lastMovementAt
                    ->copy()
                    ->startOfDay()
                    ->diffInDays(Carbon::now()->startOfDay());

                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'last_movement_at' => $lastMovementAt,
                    'days_without_movement' => $daysWithoutMovement,
                ];
            });

        return view('analytics.index', compact(
            'supplierRanking',
            'categoryAnalysis',
            'period',
            'startDate',
            'endDate',
            'entries',
            'exits',
            'movementSeries',
            'supplierDependencyDistribution',
            'staleDays',
            'staleProducts'
        ));
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
