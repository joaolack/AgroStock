<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\Supplier;
use Illuminate\Support\Collection;

class ExpirationDateRepository
{
    public function products(array $filters): Collection
    {
        $query = Product::with(['category', 'supplier', 'batches']);

        if ($filters['search'] !== '') {
            $query->where(function ($query) use ($filters) {
                $query->where('name', 'like', "%{$filters['search']}%")
                    ->orWhereHas('supplier', fn ($supplierQuery) => $supplierQuery->where('name', 'like', "%{$filters['search']}%"));
            });
        }

        if ($filters['supplier_id'] !== '') {
            $query->where('supplier_id', $filters['supplier_id']);
        }

        if ($filters['stock_only']) {
            $query->where('stock_quantity', '>', 0);
        }

        return $query->get();
    }

    public function batches(array $filters): Collection
    {
        $query = ProductBatch::with(['product.category', 'supplier'])
            ->whereNotNull('expiration_date');

        if ($filters['search'] !== '') {
            $query->where(function ($query) use ($filters) {
                $query->where('number', 'like', "%{$filters['search']}%")
                    ->orWhereHas('product', fn ($productQuery) => $productQuery->where('name', 'like', "%{$filters['search']}%"))
                    ->orWhereHas('supplier', fn ($supplierQuery) => $supplierQuery->where('name', 'like', "%{$filters['search']}%"));
            });
        }

        if ($filters['supplier_id'] !== '') {
            $query->where('supplier_id', $filters['supplier_id']);
        }

        if ($filters['stock_only']) {
            $query->where('quantity', '>', 0);
        }

        return $query->get();
    }

    public function suppliers(): Collection
    {
        return Supplier::orderBy('name')->get(['id', 'name']);
    }
}
