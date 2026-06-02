<?php

namespace App\Services;

use App\Models\AuditLog;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AuditService
{
    private static int $silenced = 0;

    public static function isSilenced(): bool
    {
        return self::$silenced > 0;
    }

    public static function withoutModelAudit(callable $callback): mixed
    {
        self::$silenced++;

        try {
            return $callback();
        } finally {
            self::$silenced--;
        }
    }

    public function record(
        Model $auditable,
        string $action,
        string $module,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?string $description = null,
        ?int $userId = null
    ): AuditLog {
        return AuditLog::create([
            'user_id' => $userId ?? Auth::id(),
            'action' => $action,
            'module' => $module,
            'auditable_id' => $auditable->getKey(),
            'auditable_type' => $auditable::class,
            'old_values' => $this->normalizeValues($oldValues),
            'new_values' => $this->normalizeValues($newValues),
            'description' => $description,
            'ip_address' => request()?->ip(),
        ]);
    }

    public function snapshot(Model $model): array
    {
        $attributes = $model->getAttributes();

        unset($attributes['created_at'], $attributes['updated_at']);

        return $this->normalizeValues($attributes);
    }

    public function changes(Model $model): array
    {
        $changedAttributes = collect($model->getChanges())
            ->except(['created_at', 'updated_at'])
            ->keys();

        $oldValues = [];
        $newValues = [];

        foreach ($changedAttributes as $attribute) {
            $oldValues[$attribute] = $model->getRawOriginal($attribute);
            $newValues[$attribute] = $model->getAttribute($attribute);
        }

        return [
            $this->normalizeValues($oldValues),
            $this->normalizeValues($newValues),
        ];
    }

    private function normalizeValues(?array $values): ?array
    {
        if ($values === null) {
            return null;
        }

        return collect($values)
            ->map(fn ($value) => $this->normalizeValue($value))
            ->all();
    }

    private function normalizeValue(mixed $value): mixed
    {
        if ($value instanceof DateTimeInterface) {
            return $value->format('Y-m-d H:i:s');
        }

        if (is_array($value)) {
            return $this->normalizeValues($value);
        }

        return $value;
    }
}
