<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

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
        return $this->normalizeAvailableStock(
            $this->filteredProductQuery($filters, $withRelations)
            ->orderBy('name')
            ->get()
        );
    }

    public function insights(array $filters): array
    {
        $products = $this->products($filters);

        return [
            'potential_sale_value' => (float) $products->sum(fn (Product $product) => (float) $product->selling_price * (int) $product->stock_quantity),
            'estimated_profit' => (float) $products->sum(fn (Product $product) => ((float) $product->selling_price - (float) $product->cost_price) * (int) $product->stock_quantity),
            'low_stock_items' => $products
                ->filter(fn (Product $product) => (int) $product->stock_quantity > 0
                    && (int) $product->stock_quantity <= (int) $product->minimum_stock)
                ->count(),
        ];
    }

    private function filteredProductQuery(array $filters, bool $withRelations = false): Builder
    {
        $query = $withRelations
            ? Product::with(['category', 'supplier'])
            : Product::query();

        $this->withAvailableStock($query);

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
        if ($stockStatus !== 'all') {
            $matchingIds = $this->productIdsForStockStatus((clone $query)->get(), $stockStatus);

            $matchingIds->isEmpty()
                ? $query->whereRaw('1 = 0')
                : $query->whereIn('products.id', $matchingIds);
        }

        return $query;
    }

    private function withAvailableStock(Builder $query): Builder
    {
        $today = Carbon::now()->toDateString();

        return $query->withSum([
            'batches as available_stock_quantity' => function ($query) use ($today) {
                $query->where('quantity', '>', 0)
                    ->where(function ($query) use ($today) {
                        $query->whereNull('expiration_date')
                            ->orWhereDate('expiration_date', '>=', $today);
                    });
            },
        ], 'quantity');
    }

    private function normalizeAvailableStock(Collection $products): Collection
    {
        return $products->map(function (Product $product) {
            $availableStock = (int) ($product->available_stock_quantity ?? 0);

            $product->setAttribute('available_stock_quantity', $availableStock);
            $product->setAttribute('stock_quantity', $availableStock);

            return $product;
        });
    }

    private function productIdsForStockStatus(Collection $products, string $stockStatus): Collection
    {
        return $this->normalizeAvailableStock($products)
            ->filter(function (Product $product) use ($stockStatus) {
                $availableStock = (int) $product->stock_quantity;
                $minimumStock = (int) $product->minimum_stock;

                return match ($stockStatus) {
                    'in_stock' => $availableStock > $minimumStock,
                    'low_stock' => $availableStock > 0 && $availableStock <= $minimumStock,
                    'out_of_stock' => $availableStock <= 0,
                    default => true,
                };
            })
            ->pluck('id')
            ->values();
    }
}
