<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;

class AuditLogSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'demo@agrostock.local')->firstOrFail();

        AuditLog::where('user_id', $user->id)
            ->where('description', 'like', 'Demo:%')
            ->delete();

        $this->createLog(
            $user,
            'create',
            'suppliers',
            Supplier::where('email', 'compras@agrovale.example')->first(),
            null,
            ['name' => 'Agro Vale Distribuidora', 'active' => true],
            'Demo: fornecedor Agro Vale Distribuidora cadastrado.',
            29
        );

        $this->createLog(
            $user,
            'create',
            'products',
            Product::where('name', 'Fertilizante NPK 10-10-10 25kg')->first(),
            null,
            ['name' => 'Fertilizante NPK 10-10-10 25kg', 'stock_quantity' => 72],
            'Demo: produto Fertilizante NPK 10-10-10 25kg cadastrado.',
            24
        );

        $this->createLog(
            $user,
            'entry',
            'stock_movements',
            Product::where('name', 'Racao Aves Postura 40kg')->first(),
            ['stock_quantity' => 0],
            ['stock_quantity' => 180, 'quantity' => 180, 'type' => 'entry'],
            'Demo: entrada de 180 unidades registrada para Racao Aves Postura 40kg.',
            28
        );

        $this->createLog(
            $user,
            'exit',
            'stock_movements',
            Product::where('name', 'Vacina Clostridioses 50 doses')->first(),
            ['stock_quantity' => 30],
            ['stock_quantity' => 12, 'quantity' => 18, 'type' => 'exit'],
            'Demo: saida de 18 unidades registrada para Vacina Clostridioses 50 doses.',
            3
        );

        $this->createLog(
            $user,
            'update',
            'products',
            Product::where('name', 'Semente de Milho Hibrido 20kg')->first(),
            ['minimum_stock' => 10],
            ['minimum_stock' => 15],
            'Demo: estoque minimo da Semente de Milho Hibrido 20kg ajustado.',
            2
        );
    }

    private function createLog(
        User $user,
        string $action,
        string $module,
        ?object $auditable,
        ?array $oldValues,
        ?array $newValues,
        string $description,
        int $daysAgo
    ): void {
        AuditLog::create([
            'user_id' => $user->id,
            'action' => $action,
            'module' => $module,
            'auditable_id' => $auditable?->id,
            'auditable_type' => $auditable ? $auditable::class : null,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'description' => $description,
            'ip_address' => '127.0.0.1',
            'created_at' => now()->subDays($daysAgo)->setTime(10, 30),
        ]);
    }
}
