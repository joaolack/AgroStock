<?php

namespace App\Observers;

use App\Models\Category;
use App\Services\AuditService;

class CategoryObserver
{
    public function created(Category $category): void
    {
        if (AuditService::isSilenced()) {
            return;
        }

        $audit = app(AuditService::class);

        $audit->record(
            $category,
            'created',
            'categories',
            null,
            $audit->snapshot($category),
            "Categoria \"{$category->name}\" criada."
        );
    }

    public function updated(Category $category): void
    {
        if (AuditService::isSilenced()) {
            return;
        }

        $audit = app(AuditService::class);
        [$oldValues, $newValues] = $audit->changes($category);

        if ($oldValues === [] && $newValues === []) {
            return;
        }

        $audit->record(
            $category,
            'updated',
            'categories',
            $oldValues,
            $newValues,
            "Categoria \"{$category->name}\" atualizada."
        );
    }

    public function deleted(Category $category): void
    {
        if (AuditService::isSilenced()) {
            return;
        }

        $audit = app(AuditService::class);

        $audit->record(
            $category,
            'deleted',
            'categories',
            $audit->snapshot($category),
            null,
            "Categoria \"{$category->name}\" excluida."
        );
    }
}
