<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnalyticsAbcCurveTest extends TestCase
{
    use RefreshDatabase;

    public function test_analytics_page_builds_abc_curve_by_stock_value(): void
    {
        $category = Category::create([
            'name' => 'Insumos',
            'description' => null,
        ]);

        $this->createProduct($category->id, 'Produto A1', 50, 10);
        $this->createProduct($category->id, 'Produto A2', 30, 10);
        $this->createProduct($category->id, 'Produto B1', 15, 10);
        $this->createProduct($category->id, 'Produto C1', 5, 10);
        $this->createProduct($category->id, 'Sem estoque', 20, 0);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('analytics.index'));

        $response->assertOk();
        $response->assertViewHas('abcCurve', function (array $abcCurve) {
            $summary = $abcCurve['summary']->keyBy('class');
            $classesByProduct = $abcCurve['products']->pluck('class', 'name');

            return $abcCurve['total_stock_value'] === 1000.0
                && $abcCurve['products']->count() === 4
                && $classesByProduct['Produto A1'] === 'A'
                && $classesByProduct['Produto A2'] === 'A'
                && $classesByProduct['Produto B1'] === 'B'
                && $classesByProduct['Produto C1'] === 'C'
                && $summary['A']['products_count'] === 2
                && $summary['A']['stock_value'] === 800.0
                && $summary['A']['stock_percentage'] === 80.0
                && $summary['B']['products_count'] === 1
                && $summary['B']['stock_value'] === 150.0
                && $summary['B']['stock_percentage'] === 15.0
                && $summary['C']['products_count'] === 1
                && $summary['C']['stock_value'] === 50.0
                && $summary['C']['stock_percentage'] === 5.0;
        });
    }

    private function createProduct(int $categoryId, string $name, float $costPrice, int $stockQuantity): void
    {
        Product::create([
            'name' => $name,
            'description' => null,
            'selling_price' => $costPrice * 1.5,
            'cost_price' => $costPrice,
            'stock_quantity' => $stockQuantity,
            'minimum_stock' => 0,
            'expiration_date' => null,
            'category_id' => $categoryId,
        ]);
    }
}
