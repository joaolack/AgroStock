<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ProductReportRepository
{
    public function categories(): Collection
    {
        return Category::orderBy('name')->get(['id', 'name']);
    }

    public function suppliers(): Collection
    {
        return Supplier::orderBy('name')->get(['id', 'name']);
    }

    public function categoryName(int|string $categoryId): ?string
    {
        return Category::find($categoryId)?->name;
    }

    public function supplierName(int|string $supplierId): ?string
    {
        return Supplier::find($supplierId)?->name;
    }

    public function products(array $filters, bool $withRelations = false): Collection
    {
        return $this->filteredProductQuery($filters, $withRelations)
            ->orderBy('name')
            ->get();
    }

    public function insights(array $filters): array
    {
        $query = $this->filteredProductQuery($filters);

        $totals = (clone $query)
            ->select([
                DB::raw('COALESCE(SUM(selling_price * stock_quantity), 0) as potential_sale_value'),
                DB::raw('COALESCE(SUM((selling_price - cost_price) * stock_quantity), 0) as estimated_profit'),
            ])
            ->first();

        return [
            'potential_sale_value' => (float) ($totals->potential_sale_value ?? 0),
            'estimated_profit' => (float) ($totals->estimated_profit ?? 0),
            'low_stock_items' => (clone $query)
                ->where('stock_quantity', '>', 0)
                ->whereColumn('stock_quantity', '<=', 'minimum_stock')
                ->count(),
        ];
    }

    private function filteredProductQuery(array $filters, bool $withRelations = false): Builder
    {
        $query = $withRelations
            ? Product::with(['category', 'supplier'])
            : Product::query();

        if (! empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (! empty($filters['supplier_id'])) {
            $query->where('supplier_id', $filters['supplier_id']);
        }

        if (! empty($filters['price_min'])) {
            $query->where('selling_price', '>=', $filters['price_min']);
        }

        if (! empty($filters['price_max'])) {
            $query->where('selling_price', '<=', $filters['price_max']);
        }

        $stockStatus = $filters['stock_status'] ?? 'all';
        if ($stockStatus === 'in_stock') {
            $query->whereColumn('stock_quantity', '>', 'minimum_stock');
        } elseif ($stockStatus === 'low_stock') {
            $query->where('stock_quantity', '>', 0)
                ->whereColumn('stock_quantity', '<=', 'minimum_stock');
        } elseif ($stockStatus === 'out_of_stock') {
            $query->where('stock_quantity', '<=', 0);
        }

        return $query;
    }
}
