<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public function createWithInitialBatch(array $validated): Product
    {
        return DB::transaction(function () use ($validated) {
            $batchNumber = $validated['batch_number'] ?? null;
            unset($validated['batch_number']);

            $product = Product::create($validated);

            if ($product->stock_quantity > 0) {
                $product->batches()->create([
                    'supplier_id' => $product->supplier_id,
                    'number' => $batchNumber,
                    'original_quantity' => $product->stock_quantity,
                    'quantity' => $product->stock_quantity,
                    'expiration_date' => $product->expiration_date,
                ]);
            }

            return $product;
        });
    }
}
