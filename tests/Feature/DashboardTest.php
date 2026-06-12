<?php

namespace Tests\Feature;

use App\Models\Product;
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
        Product::factory()->create([
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

        $response = $this->actingAs(User::factory()->create())
            ->get(route('dashboard'));

        $response
            ->assertOk()
            ->assertViewHas('totalProducts', 3)
            ->assertViewHas('criticalStockCount', 2)
            ->assertViewHas('outOfStockProducts', 1)
            ->assertViewHas('totalStockValue', '212');
    }
}
