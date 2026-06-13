<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_calculates_inventory_summary(): void
    {
        Product::factory()->create([
            'name' => 'Estoque Normal',
            'stock_quantity' => 20,
            'minimum_stock' => 5,
            'cost_price' => 10,
        ]);
        $lowStockProduct = Product::factory()->create([
            'name' => 'Estoque Baixo',
            'stock_quantity' => 3,
            'minimum_stock' => 5,
            'cost_price' => 4,
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
            ->assertViewHas('totalStockValue', '212')
            ->assertViewHas('purchaseSuggestions', function ($suggestions) use ($lowStockProduct) {
                $suggestion = $suggestions->firstWhere('id', $lowStockProduct->id);

                return $suggestion
                    && $suggestion->monthly_exits === 12
                    && $suggestion->suggested_target_stock === 12
                    && $suggestion->suggested_purchase_quantity === 9;
            });
    }
}
