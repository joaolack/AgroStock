<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\StockMovement;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DashboardMovementSeeder extends Seeder
{
    use WithoutModelEvents;

    private const USER_EMAIL = 'dashboard@agrostock.local';

    private const PRODUCT_NAME = 'Produto Demo - Grafico Dashboard';

    private const BATCH_NUMBER = 'DASHBOARD-DEMO-001';

    public function run(): void
    {
        $user = $this->user();
        $product = $this->product();
        $batch = $this->batch($product);

        StockMovement::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->where('reason', 'dashboard_demo')
            ->delete();

        foreach ($this->movements() as $movement) {
            $timestamp = now()
                ->subDays($movement['days_ago'])
                ->setTime($movement['hour'], 0);

            StockMovement::create([
                'product_id' => $product->id,
                'product_batch_id' => $batch->id,
                'user_id' => $user->id,
                'type' => $movement['type'],
                'reason' => 'dashboard_demo',
                'quantity' => $movement['quantity'],
                'previous_quantity' => $movement['previous_quantity'],
                'new_quantity' => $movement['new_quantity'],
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);
        }
    }

    private function user(): User
    {
        return User::firstOrCreate(
            ['email' => self::USER_EMAIL],
            [
                'name' => 'Dashboard Demo',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ]
        );
    }

    private function product(): Product
    {
        $category = Category::firstOrCreate(
            ['name' => 'Demonstracao'],
            ['description' => 'Dados criados para demonstracao do dashboard.']
        );

        $supplier = Supplier::firstOrCreate(
            ['email' => 'dashboard@fornecedor-demo.local'],
            [
                'name' => 'Fornecedor Demo Dashboard',
                'contact_name' => 'Contato Demo',
                'phone' => '(00) 0000-0000',
                'address' => 'Endereco demo',
                'city' => 'Sao Paulo',
                'state' => 'SP',
                'zip_code' => '01000-000',
                'notes' => 'Fornecedor criado apenas para demonstrar movimentacoes no dashboard.',
                'active' => true,
            ]
        );

        return Product::updateOrCreate(
            ['name' => self::PRODUCT_NAME],
            [
                'description' => 'Produto criado para gerar entradas e saidas no grafico do dashboard.',
                'selling_price' => 99.90,
                'cost_price' => 55.00,
                'category_id' => $category->id,
                'supplier_id' => $supplier->id,
                'stock_quantity' => 175,
                'minimum_stock' => 40,
                'expiration_date' => now()->addMonths(8)->toDateString(),
            ]
        );
    }

    private function batch(Product $product): ProductBatch
    {
        return $product->batches()->updateOrCreate(
            ['number' => self::BATCH_NUMBER],
            [
                'supplier_id' => $product->supplier_id,
                'original_quantity' => 320,
                'quantity' => 175,
                'expiration_date' => now()->addMonths(8)->toDateString(),
            ]
        );
    }

    private function movements(): array
    {
        return [
            ['type' => 'entry', 'quantity' => 120, 'previous_quantity' => 0, 'new_quantity' => 120, 'days_ago' => 28, 'hour' => 9],
            ['type' => 'exit', 'quantity' => 12, 'previous_quantity' => 120, 'new_quantity' => 108, 'days_ago' => 26, 'hour' => 15],
            ['type' => 'entry', 'quantity' => 45, 'previous_quantity' => 108, 'new_quantity' => 153, 'days_ago' => 23, 'hour' => 10],
            ['type' => 'exit', 'quantity' => 20, 'previous_quantity' => 153, 'new_quantity' => 133, 'days_ago' => 21, 'hour' => 16],
            ['type' => 'exit', 'quantity' => 15, 'previous_quantity' => 133, 'new_quantity' => 118, 'days_ago' => 18, 'hour' => 11],
            ['type' => 'entry', 'quantity' => 60, 'previous_quantity' => 118, 'new_quantity' => 178, 'days_ago' => 15, 'hour' => 9],
            ['type' => 'exit', 'quantity' => 32, 'previous_quantity' => 178, 'new_quantity' => 146, 'days_ago' => 13, 'hour' => 14],
            ['type' => 'entry', 'quantity' => 25, 'previous_quantity' => 146, 'new_quantity' => 171, 'days_ago' => 10, 'hour' => 8],
            ['type' => 'exit', 'quantity' => 18, 'previous_quantity' => 171, 'new_quantity' => 153, 'days_ago' => 8, 'hour' => 17],
            ['type' => 'exit', 'quantity' => 22, 'previous_quantity' => 153, 'new_quantity' => 131, 'days_ago' => 6, 'hour' => 13],
            ['type' => 'entry', 'quantity' => 40, 'previous_quantity' => 131, 'new_quantity' => 171, 'days_ago' => 4, 'hour' => 10],
            ['type' => 'exit', 'quantity' => 16, 'previous_quantity' => 171, 'new_quantity' => 155, 'days_ago' => 2, 'hour' => 15],
            ['type' => 'entry', 'quantity' => 30, 'previous_quantity' => 155, 'new_quantity' => 185, 'days_ago' => 1, 'hour' => 9],
            ['type' => 'exit', 'quantity' => 10, 'previous_quantity' => 185, 'new_quantity' => 175, 'days_ago' => 0, 'hour' => 11],
        ];
    }
}
