<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\StockMovement;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_calculates_inventory_summary(): void
    {
        $normalStockProduct = Product::factory()->create([
            'name' => 'Estoque Normal',
            'stock_quantity' => 20,
            'minimum_stock' => 5,
            'cost_price' => 10,
        ]);
        ProductBatch::factory()->for($normalStockProduct)->create([
            'original_quantity' => 20,
            'quantity' => 20,
            'expiration_date' => now()->addMonth()->toDateString(),
        ]);

        $lowStockProduct = Product::factory()->create([
            'name' => 'Estoque Baixo',
            'stock_quantity' => 3,
            'minimum_stock' => 5,
            'cost_price' => 4,
        ]);
        ProductBatch::factory()->for($lowStockProduct)->create([
            'original_quantity' => 3,
            'quantity' => 3,
            'expiration_date' => now()->addMonth()->toDateString(),
        ]);

        Product::factory()->create([
            'name' => 'Sem Estoque',
            'stock_quantity' => 0,
            'minimum_stock' => 5,
            'cost_price' => 7,
        ]);
        StockMovement::create([
            'product_id' => $lowStockProduct->id,
            'product_batch_id' => null,
            'user_id' => User::factory()->create()->id,
            'type' => 'exit',
            'reason' => 'manual',
            'quantity' => 12,
            'previous_quantity' => 15,
            'new_quantity' => 3,
        ]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('dashboard'));

        $response
            ->assertOk()
            ->assertViewHas('totalProducts', 3)
            ->assertViewHas('criticalStockCount', 2)
            ->assertViewHas('outOfStockProducts', 1)
            ->assertViewHas('totalStockValue', fn ($value) => (float) $value === 212.0)
            ->assertViewHas('purchaseSuggestions', function ($suggestions) use ($lowStockProduct) {
                $suggestion = $suggestions->firstWhere('id', $lowStockProduct->id);

                return $suggestion
                    && $suggestion->monthly_exits === 12
                    && $suggestion->suggested_target_stock === 12
                    && $suggestion->suggested_purchase_quantity === 9;
            });
    }

    public function test_dashboard_stock_value_and_out_of_stock_use_valid_batches(): void
    {
        $expiredStockProduct = Product::factory()->create([
            'name' => 'Produto com lote vencido',
            'stock_quantity' => 8,
            'minimum_stock' => 2,
            'cost_price' => 10,
        ]);
        ProductBatch::factory()->for($expiredStockProduct)->create([
            'original_quantity' => 8,
            'quantity' => 8,
            'expiration_date' => now()->subDay()->toDateString(),
        ]);

        $partiallyValidProduct = Product::factory()->create([
            'name' => 'Produto parcialmente valido',
            'stock_quantity' => 10,
            'minimum_stock' => 2,
            'cost_price' => 5,
        ]);
        ProductBatch::factory()->for($partiallyValidProduct)->create([
            'original_quantity' => 4,
            'quantity' => 4,
            'expiration_date' => now()->addMonth()->toDateString(),
        ]);
        ProductBatch::factory()->for($partiallyValidProduct)->create([
            'original_quantity' => 6,
            'quantity' => 6,
            'expiration_date' => now()->subDay()->toDateString(),
        ]);

        Product::factory()->create([
            'name' => 'Produto zerado',
            'stock_quantity' => 0,
            'minimum_stock' => 2,
            'cost_price' => 99,
        ]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('dashboard'));

        $response
            ->assertOk()
            ->assertViewHas('outOfStockProducts', 2)
            ->assertViewHas('totalStockValue', fn ($value) => (float) $value === 20.0);
    }

    public function test_dashboard_movements_use_display_timezone_for_today_and_chart_series(): void
    {
        config(['app.display_timezone' => 'America/Sao_Paulo']);
        Carbon::setTestNow(Carbon::parse('2026-06-22 02:30:00', 'UTC'));

        try {
            $user = User::factory()->create();
            $product = Product::factory()->create(['stock_quantity' => 20]);
            $batch = ProductBatch::factory()->for($product)->create([
                'original_quantity' => 20,
                'quantity' => 20,
                'expiration_date' => now()->addMonth()->toDateString(),
            ]);

            StockMovement::factory()->create([
                'product_id' => $product->id,
                'product_batch_id' => $batch->id,
                'user_id' => $user->id,
                'type' => 'entry',
                'quantity' => 4,
                'created_at' => Carbon::parse('2026-06-21 04:00:00', 'UTC'),
                'updated_at' => Carbon::parse('2026-06-21 04:00:00', 'UTC'),
            ]);
            StockMovement::factory()->create([
                'product_id' => $product->id,
                'product_batch_id' => $batch->id,
                'user_id' => $user->id,
                'type' => 'exit',
                'quantity' => 6,
                'created_at' => Carbon::parse('2026-06-22 02:00:00', 'UTC'),
                'updated_at' => Carbon::parse('2026-06-22 02:00:00', 'UTC'),
            ]);
            StockMovement::factory()->create([
                'product_id' => $product->id,
                'product_batch_id' => $batch->id,
                'user_id' => $user->id,
                'type' => 'entry',
                'quantity' => 9,
                'created_at' => Carbon::parse('2026-06-21 02:00:00', 'UTC'),
                'updated_at' => Carbon::parse('2026-06-21 02:00:00', 'UTC'),
            ]);

            $response = $this->actingAs($user)->get(route('dashboard'));

            $response
                ->assertOk()
                ->assertViewHas('todayMovementsCount', 2)
                ->assertViewHas('movementSeries', function ($series) {
                    $lastDay = $series->last();

                    return $lastDay['day'] === '21/06'
                        && $lastDay['entries'] === 4
                        && $lastDay['exits'] === 6;
                });
        } finally {
            Carbon::setTestNow();
        }
    }

    public function test_dashboard_uses_only_valid_stock_for_critical_alerts(): void
    {
        $expiredStockProduct = Product::factory()->create([
            'name' => 'Produto com estoque vencido',
            'stock_quantity' => 11,
            'minimum_stock' => 5,
        ]);
        ProductBatch::factory()->for($expiredStockProduct)->create([
            'original_quantity' => 11,
            'quantity' => 11,
            'expiration_date' => now()->subDay()->toDateString(),
        ]);

        $validStockProduct = Product::factory()->create([
            'name' => 'Produto com estoque válido',
            'stock_quantity' => 10,
            'minimum_stock' => 5,
        ]);
        ProductBatch::factory()->for($validStockProduct)->create([
            'original_quantity' => 10,
            'quantity' => 10,
            'expiration_date' => now()->addMonth()->toDateString(),
        ]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('dashboard'));

        $response
            ->assertOk()
            ->assertViewHas('criticalStockCount', 1)
            ->assertViewHas('criticalStock', function ($products) use ($expiredStockProduct, $validStockProduct) {
                $criticalProduct = $products->firstWhere('id', $expiredStockProduct->id);

                return $criticalProduct
                    && (int) $criticalProduct->available_stock_quantity === 0
                    && ! $products->contains('id', $validStockProduct->id);
            });
    }
}
