<?php

namespace App\Observers;

use App\Models\StockMovement;
use App\Services\AuditService;

class StockMovementObserver
{
    public function created(StockMovement $movement): void
    {
        $movement->loadMissing(['product', 'productBatch']);

        $typeLabel = $movement->type === 'entry' ? 'Entrada' : 'Saida';
        $action = $movement->type === 'entry' ? 'entry' : 'exit';
        $productName = $movement->product?->name ?? "Produto #{$movement->product_id}";

        app(AuditService::class)->record(
            $movement,
            $action,
            'stock_movements',
            [
                'product_id' => $movement->product_id,
                'stock_quantity' => $movement->previous_quantity,
            ],
            [
                'product_id' => $movement->product_id,
                'product_batch_id' => $movement->product_batch_id,
                'type' => $movement->type,
                'reason' => $movement->reason,
                'quantity' => $movement->quantity,
                'stock_quantity' => $movement->new_quantity,
            ],
            "{$typeLabel} de {$movement->quantity} unidade(s) registrada para {$productName}.",
            $movement->user_id
        );
    }
}
