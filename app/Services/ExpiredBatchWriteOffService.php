<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\StockMovement;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ExpiredBatchWriteOffService
{
    public function writeOff(ProductBatch $batch, int $quantity): int
    {
        return DB::transaction(function () use ($batch, $quantity) {
            $lockedBatch = ProductBatch::query()
                ->whereKey($batch->id)
                ->lockForUpdate()
                ->firstOrFail();

            $expirationDate = $lockedBatch->expiration_date
                ? Carbon::parse($lockedBatch->expiration_date)->startOfDay()
                : null;

            if (! $expirationDate || $expirationDate->greaterThanOrEqualTo(Carbon::now()->startOfDay())) {
                throw ValidationException::withMessages([
                    'quantity' => "A baixa por vencimento s\u{00F3} pode ser feita em lotes vencidos.",
                ]);
            }

            $product = Product::query()
                ->whereKey($lockedBatch->product_id)
                ->lockForUpdate()
                ->firstOrFail();

            $availableQuantity = min((int) $lockedBatch->quantity, (int) $product->stock_quantity);

            if ($availableQuantity <= 0) {
                throw ValidationException::withMessages([
                    'quantity' => "Este lote vencido n\u{00E3}o possui quantidade dispon\u{00ED}vel para baixa.",
                ]);
            }

            if ($quantity > $availableQuantity) {
                throw ValidationException::withMessages([
                    'quantity' => "A quantidade m\u{00E1}xima dispon\u{00ED}vel para baixa \u{00E9} {$availableQuantity}.",
                ]);
            }

            $previousQuantity = (int) $product->stock_quantity;

            $lockedBatch->quantity -= $quantity;
            $lockedBatch->save();

            $product->stock_quantity -= $quantity;
            AuditService::withoutModelAudit(fn () => $product->save());

            StockMovement::create([
                'product_id' => $product->id,
                'product_batch_id' => $lockedBatch->id,
                'user_id' => auth()->id(),
                'type' => 'exit',
                'reason' => 'expired',
                'quantity' => $quantity,
                'previous_quantity' => $previousQuantity,
                'new_quantity' => $product->stock_quantity,
            ]);

            return $quantity;
        });
    }
}
