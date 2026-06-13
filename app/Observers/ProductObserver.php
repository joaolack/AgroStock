<?php

namespace App\Observers;

use App\Models\Product;
use App\Services\AuditService;

class ProductObserver
{
    public function created(Product $product): void
    {
        if (AuditService::isSilenced()) {
            return;
        }

        $audit = app(AuditService::class);

        $audit->record(
            $product,
            'created',
            'products',
            null,
            $audit->snapshot($product),
            "Produto \"{$product->name}\" criado."
        );
    }

    public function updated(Product $product): void
    {
        if (AuditService::isSilenced()) {
            return;
        }

        $audit = app(AuditService::class);
        [$oldValues, $newValues] = $audit->changes($product);

        if ($oldValues === [] && $newValues === []) {
            return;
        }

        $audit->record(
            $product,
            'updated',
            'products',
            $oldValues,
            $newValues,
            "Produto \"{$product->name}\" atualizado."
        );
    }

    public function deleted(Product $product): void
    {
        if (AuditService::isSilenced()) {
            return;
        }

        $audit = app(AuditService::class);

        $audit->record(
            $product,
            'deleted',
            'products',
            $audit->snapshot($product),
            null,
            "Produto \"{$product->name}\" excluído."
        );
    }
}
