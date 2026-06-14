<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductBatch;
use App\Services\ExportReportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExportReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_export_payload_uses_valid_stock_for_stock_filters_and_pdf_rows(): void
    {
        $category = Category::factory()->create();

        $lowStockProduct = Product::factory()->for($category)->create([
            'name' => 'Produto com lote vencido',
            'stock_quantity' => 12,
            'minimum_stock' => 5,
            'cost_price' => 10,
            'selling_price' => 20,
        ]);
        ProductBatch::factory()->for($lowStockProduct)->create([
            'original_quantity' => 10,
            'quantity' => 10,
            'expiration_date' => now()->subDay()->toDateString(),
        ]);
        ProductBatch::factory()->for($lowStockProduct)->create([
            'original_quantity' => 2,
            'quantity' => 2,
            'expiration_date' => now()->addMonth()->toDateString(),
        ]);

        $normalStockProduct = Product::factory()->for($category)->create([
            'name' => 'Produto com estoque normal',
            'stock_quantity' => 10,
            'minimum_stock' => 5,
            'cost_price' => 10,
            'selling_price' => 20,
        ]);
        ProductBatch::factory()->for($normalStockProduct)->create([
            'original_quantity' => 10,
            'quantity' => 10,
            'expiration_date' => now()->addMonth()->toDateString(),
        ]);

        $payload = app(ExportReportService::class)->reportPayload([
            'report_type' => 'general_stock',
            'stock_status' => 'low_stock',
        ]);

        $this->assertCount(1, $payload['rows']);
        $this->assertSame('Produto com lote vencido', $payload['rows']->first()->name);
        $this->assertSame(2, (int) $payload['rows']->first()->stock_quantity);
        $this->assertSame('Estoque válido', collect($payload['columns'])->firstWhere('key', 'stock_quantity')['label']);
    }

    public function test_export_insights_use_valid_stock_values(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->for($category)->create([
            'stock_quantity' => 12,
            'minimum_stock' => 5,
            'cost_price' => 10,
            'selling_price' => 20,
        ]);
        ProductBatch::factory()->for($product)->create([
            'original_quantity' => 10,
            'quantity' => 10,
            'expiration_date' => now()->subDay()->toDateString(),
        ]);
        ProductBatch::factory()->for($product)->create([
            'original_quantity' => 2,
            'quantity' => 2,
            'expiration_date' => now()->addMonth()->toDateString(),
        ]);

        $data = app(ExportReportService::class)->indexData([
            'report_type' => 'general_stock',
            'stock_status' => 'all',
        ]);

        $this->assertSame(40.0, $data['insights']['potential_sale_value']);
        $this->assertSame(20.0, $data['insights']['estimated_profit']);
        $this->assertSame(1, $data['insights']['low_stock_items']);
    }
}
