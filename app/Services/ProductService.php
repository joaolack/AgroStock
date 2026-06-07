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

    public function updateWithActiveBatches(Product $product, array $validated): Product
    {
        return DB::transaction(function () use ($product, $validated) {
            $originalExpirationDate = $product->expiration_date?->toDateString();

            $product->update($validated);

            if (array_key_exists('expiration_date', $validated)) {
                $this->syncActiveBatchExpirationDate(
                    $product,
                    $originalExpirationDate,
                    $product->expiration_date?->toDateString()
                );
            }

            return $product->refresh();
        });
    }

    private function syncActiveBatchExpirationDate(
        Product $product,
        ?string $originalExpirationDate,
        ?string $newExpirationDate
    ): void {
        $activeBatches = $product->batches()
            ->where('quantity', '>', 0)
            ->get();

        if ($activeBatches->isEmpty()) {
            return;
        }

        $batchIds = $activeBatches
            ->filter(function ($batch) use ($activeBatches, $originalExpirationDate) {
                return $activeBatches->count() === 1
                    || $batch->expiration_date?->toDateString() === $originalExpirationDate;
            })
            ->modelKeys();

        if ($batchIds === []) {
            return;
        }

        $product->batches()
            ->whereKey($batchIds)
            ->update(['expiration_date' => $newExpirationDate]);
    }
}
