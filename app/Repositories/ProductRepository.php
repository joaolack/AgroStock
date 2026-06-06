<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ProductRepository
{
    public function indexData(array $input): array
    {
        $filters = $this->filters($input);

        return [
            'products' => $this->paginatedProducts($filters),
            'categories' => $this->categories(),
            'filters' => $filters,
            'totalProducts' => $this->totalProducts(),
            'criticalStock' => $this->criticalStock(),
            'outOfStockProducts' => $this->outOfStockProducts(),
            'totalStockValue' => $this->totalStockValue(),
        ];
    }

    public function categories(): Collection
    {
        return Category::all();
    }

    public function activeSuppliers(): Collection
    {
        return Supplier::active()->orderBy('name')->get();
    }

    private function paginatedProducts(array $filters): LengthAwarePaginator
    {
        return $this->filteredProductsQuery($filters)
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();
    }

    private function filteredProductsQuery(array $filters): Builder
    {
        $query = Product::with(['category', 'supplier', 'batches.supplier']);

        $query->when($filters['search'] !== '', function ($query) use ($filters) {
            $search = $filters['search'];

            $query->where(function ($query) use ($search) {
                if (mb_strlen($search) <= 2) {
                    $query->where('name', 'LIKE', "{$search}%")
                        ->orWhere('name', 'LIKE', "% {$search}%");

                    return;
                }

                $query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");

                if (mb_strlen($search) >= 3) {
                    $query->orWhereHas('category', function ($categoryQuery) use ($search) {
                        $categoryQuery->where('name', 'LIKE', "%{$search}%");
                    })->orWhereHas('supplier', function ($supplierQuery) use ($search) {
                        $supplierQuery->where('name', 'LIKE', "%{$search}%");
                    });
                }
            });
        });

        if ($filters['category_id'] !== '') {
            $query->where('category_id', $filters['category_id']);
        }

        if ($filters['stock_status'] !== '') {
            match ($filters['stock_status']) {
                'Em Falta' => $query->where('stock_quantity', 0),
                'Estoque Baixo' => $query->where('stock_quantity', '>', 0)
                    ->whereColumn('stock_quantity', '<=', 'minimum_stock'),
                'Estoque Normal' => $query->whereColumn('stock_quantity', '>', 'minimum_stock'),
                default => null,
            };
        }

        return $query;
    }

    private function filters(array $input): array
    {
        return [
            'search' => trim((string) ($input['search'] ?? '')),
            'category_id' => $input['category_id'] ?? '',
            'stock_status' => $input['stock_status'] ?? '',
        ];
    }

    private function totalProducts(): int
    {
        return Product::count();
    }

    private function criticalStock(): Collection
    {
        return Product::whereColumn('stock_quantity', '<=', 'minimum_stock')
            ->with('category')
            ->orderBy('stock_quantity', 'asc')
            ->get();
    }

    private function outOfStockProducts(): int
    {
        return Product::where('stock_quantity', 0)->count();
    }

    private function totalStockValue(): float
    {
        return (float) (Product::select(DB::raw('SUM(cost_price * stock_quantity) as total_cost'))
            ->value('total_cost') ?? 0);
    }
}
