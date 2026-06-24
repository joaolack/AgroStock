<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Database\Seeder;

class StockMovementSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'demo@agrostock.local')->firstOrFail();
        $productNames = collect($this->movements())->pluck('product')->unique()->all();
        $productIds = Product::whereIn('name', $productNames)->pluck('id');

        StockMovement::where('user_id', $user->id)
            ->whereIn('product_id', $productIds)
            ->delete();

        foreach ($this->movements() as $movement) {
            $product = Product::where('name', $movement['product'])->firstOrFail();
            $batch = ProductBatch::where('product_id', $product->id)
                ->where('number', $movement['batch'])
                ->first();
            $timestamp = now()->subDays($movement['days_ago'])->setTime($movement['hour'], 0);

            StockMovement::create([
                'product_id' => $product->id,
                'product_batch_id' => $batch?->id,
                'user_id' => $user->id,
                'type' => $movement['type'],
                'reason' => 'manual',
                'quantity' => $movement['quantity'],
                'previous_quantity' => $movement['previous_quantity'],
                'new_quantity' => $movement['new_quantity'],
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);
        }
    }

    private function movements(): array
    {
        return [
            ['product' => 'Racao Aves Postura 40kg', 'batch' => 'DEMO-RAC-2606-A', 'type' => 'entry', 'quantity' => 180, 'previous_quantity' => 0, 'new_quantity' => 180, 'days_ago' => 28, 'hour' => 9],
            ['product' => 'Racao Aves Postura 40kg', 'batch' => 'DEMO-RAC-2606-A', 'type' => 'exit', 'quantity' => 40, 'previous_quantity' => 180, 'new_quantity' => 140, 'days_ago' => 9, 'hour' => 15],
            ['product' => 'Suplemento Mineral Bovino 30kg', 'batch' => 'DEMO-SUP-2604-A', 'type' => 'entry', 'quantity' => 18, 'previous_quantity' => 0, 'new_quantity' => 18, 'days_ago' => 75, 'hour' => 10],
            ['product' => 'Suplemento Mineral Bovino 30kg', 'batch' => 'DEMO-SUP-2604-A', 'type' => 'exit', 'quantity' => 4, 'previous_quantity' => 22, 'new_quantity' => 18, 'days_ago' => 65, 'hour' => 16],
            ['product' => 'Semente de Milho Hibrido 20kg', 'batch' => 'DEMO-MIL-2606-A', 'type' => 'entry', 'quantity' => 25, 'previous_quantity' => 0, 'new_quantity' => 25, 'days_ago' => 42, 'hour' => 11],
            ['product' => 'Semente de Milho Hibrido 20kg', 'batch' => 'DEMO-MIL-2606-A', 'type' => 'exit', 'quantity' => 17, 'previous_quantity' => 25, 'new_quantity' => 8, 'days_ago' => 12, 'hour' => 14],
            ['product' => 'Fertilizante NPK 10-10-10 25kg', 'batch' => 'DEMO-NPK-2606-A', 'type' => 'entry', 'quantity' => 90, 'previous_quantity' => 0, 'new_quantity' => 90, 'days_ago' => 24, 'hour' => 8],
            ['product' => 'Fertilizante NPK 10-10-10 25kg', 'batch' => 'DEMO-NPK-2605-B', 'type' => 'exit', 'quantity' => 18, 'previous_quantity' => 90, 'new_quantity' => 72, 'days_ago' => 4, 'hour' => 13],
            ['product' => 'Vacina Clostridioses 50 doses', 'batch' => 'DEMO-VAC-2606-A', 'type' => 'entry', 'quantity' => 30, 'previous_quantity' => 0, 'new_quantity' => 30, 'days_ago' => 16, 'hour' => 10],
            ['product' => 'Vacina Clostridioses 50 doses', 'batch' => 'DEMO-VAC-2606-A', 'type' => 'exit', 'quantity' => 18, 'previous_quantity' => 30, 'new_quantity' => 12, 'days_ago' => 3, 'hour' => 17],
            ['product' => 'Antiparasitario Bovino 500ml', 'batch' => 'DEMO-ANT-2605-A', 'type' => 'entry', 'quantity' => 15, 'previous_quantity' => 0, 'new_quantity' => 15, 'days_ago' => 35, 'hour' => 9],
            ['product' => 'Antiparasitario Bovino 500ml', 'batch' => 'DEMO-ANT-2605-A', 'type' => 'exit', 'quantity' => 15, 'previous_quantity' => 15, 'new_quantity' => 0, 'days_ago' => 2, 'hour' => 15],
            ['product' => 'Herbicida Pos-emergente 1L', 'batch' => 'DEMO-HER-2602-V', 'type' => 'entry', 'quantity' => 10, 'previous_quantity' => 0, 'new_quantity' => 10, 'days_ago' => 80, 'hour' => 8],
            ['product' => 'Herbicida Pos-emergente 1L', 'batch' => 'DEMO-HER-2602-V', 'type' => 'exit', 'quantity' => 6, 'previous_quantity' => 10, 'new_quantity' => 4, 'days_ago' => 70, 'hour' => 12],
            ['product' => 'Pulverizador Costal 20L', 'batch' => 'DEMO-PUL-2606-A', 'type' => 'entry', 'quantity' => 10, 'previous_quantity' => 0, 'new_quantity' => 10, 'days_ago' => 20, 'hour' => 9],
            ['product' => 'Pulverizador Costal 20L', 'batch' => 'DEMO-PUL-2606-A', 'type' => 'exit', 'quantity' => 4, 'previous_quantity' => 10, 'new_quantity' => 6, 'days_ago' => 5, 'hour' => 11],
            ['product' => 'Pa Agricola Reforcada', 'batch' => 'DEMO-PA-2605-A', 'type' => 'entry', 'quantity' => 14, 'previous_quantity' => 0, 'new_quantity' => 14, 'days_ago' => 18, 'hour' => 10],
            ['product' => 'Luva Nitrilica Rural caixa 100 un', 'batch' => 'DEMO-LUV-2604-A', 'type' => 'entry', 'quantity' => 30, 'previous_quantity' => 0, 'new_quantity' => 30, 'days_ago' => 55, 'hour' => 14],
        ];
    }
}
