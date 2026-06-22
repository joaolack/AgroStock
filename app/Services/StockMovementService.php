<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StockMovementService
{
    public function register(array $validated): string
    {
        $quantity = (int) $validated['quantity'];
        $type = $validated['type'];

        return DB::transaction(function () use ($validated, $quantity, $type) {
            $product = Product::whereKey($validated['product_id'])->lockForUpdate()->firstOrFail();
            $previousQuantity = $product->stock_quantity;

            if ($type === 'entry') {
                return $this->registerEntry($product, $validated, $quantity, $previousQuantity);
            }

            return $this->registerExit($product, $quantity, $type);
        });
    }

    private function registerEntry(Product $product, array $validated, int $quantity, int $previousQuantity): string
    {
        $batch = $product->batches()->create([
            'supplier_id' => $validated['supplier_id'],
            'number' => $validated['batch_number'],
            'original_quantity' => $quantity,
            'quantity' => $quantity,
            'expiration_date' => $validated['expiration_date'] ?? null,
        ]);

        $product->stock_quantity += $quantity;
        $product->supplier_id = $validated['supplier_id'];
        $product->expiration_date = $validated['expiration_date'] ?? null;
        AuditService::withoutModelAudit(fn () => $product->save());

        StockMovement::create([
            'product_id' => $product->id,
            'product_batch_id' => $batch->id,
            'user_id' => auth()->id(),
            'type' => 'entry',
            'reason' => 'manual',
            'quantity' => $quantity,
            'previous_quantity' => $previousQuantity,
            'new_quantity' => $product->stock_quantity,
        ]);

        return "Entrada de {$quantity} unidades registrada no lote {$batch->number}.";
    }

    private function registerExit(Product $product, int $quantity, string $type): string
    {
        if ($product->stock_quantity < $quantity) {
            throw ValidationException::withMessages([
                'quantity' => 'Estoque insuficiente! Disponivel: '.$product->stock_quantity,
            ]);
        }

        $batches = $product->availableBatches()->lockForUpdate()->get();
        $availableQuantity = (int) $batches->sum('quantity');

        if ($availableQuantity < $quantity) {
            throw ValidationException::withMessages([
                'quantity' => 'Estoque disponivel para saida: '.$availableQuantity.'. Itens vencidos devem ser baixados pela tela de validade.',
            ]);
        }

        $remaining = $quantity;
        $movementTimestamp = now();

        foreach ($batches as $batch) {
            if ($remaining <= 0) {
                break;
            }

            $consumed = min($batch->quantity, $remaining);
            $movementPreviousQuantity = $product->stock_quantity;

            $batch->quantity -= $consumed;
            $batch->save();

            $product->stock_quantity -= $consumed;
            AuditService::withoutModelAudit(fn () => $product->save());

            StockMovement::create([
                'product_id' => $product->id,
                'product_batch_id' => $batch->id,
                'user_id' => auth()->id(),
                'type' => $type,
                'reason' => 'manual',
                'quantity' => $consumed,
                'previous_quantity' => $movementPreviousQuantity,
                'new_quantity' => $product->stock_quantity,
                'created_at' => $movementTimestamp,
                'updated_at' => $movementTimestamp,
            ]);

            $remaining -= $consumed;
        }

        return "Saida de {$quantity} unidades registrada usando FIFO.";
    }
}
