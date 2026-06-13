<?php

namespace App\Observers;

use App\Models\Supplier;
use App\Services\AuditService;

class SupplierObserver
{
    public function created(Supplier $supplier): void
    {
        if (AuditService::isSilenced()) {
            return;
        }

        $audit = app(AuditService::class);

        $audit->record(
            $supplier,
            'created',
            'suppliers',
            null,
            $audit->snapshot($supplier),
            "Fornecedor \"{$supplier->name}\" criado."
        );
    }

    public function updated(Supplier $supplier): void
    {
        if (AuditService::isSilenced()) {
            return;
        }

        $audit = app(AuditService::class);
        [$oldValues, $newValues] = $audit->changes($supplier);

        if ($oldValues === [] && $newValues === []) {
            return;
        }

        $audit->record(
            $supplier,
            'updated',
            'suppliers',
            $oldValues,
            $newValues,
            "Fornecedor \"{$supplier->name}\" atualizado."
        );
    }

    public function deleted(Supplier $supplier): void
    {
        if (AuditService::isSilenced()) {
            return;
        }

        $audit = app(AuditService::class);

        $audit->record(
            $supplier,
            'deleted',
            'suppliers',
            $audit->snapshot($supplier),
            null,
            "Fornecedor \"{$supplier->name}\" excluído."
        );
    }
}
