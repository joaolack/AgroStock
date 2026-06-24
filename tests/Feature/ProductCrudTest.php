<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_list_products(): void
    {
        $product = Product::factory()->create(['name' => 'Racao Inicial']);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('products.index'));

        $response
            ->assertOk()
            ->assertViewHas('products', fn ($products) => $products->getCollection()->contains($product));
    }

    public function test_product_summary_stock_value_uses_only_valid_batches(): void
    {
        $expiredStockProduct = Product::factory()->create([
            'stock_quantity' => 8,
            'cost_price' => 10,
        ]);
        ProductBatch::factory()->for($expiredStockProduct)->create([
            'original_quantity' => 8,
            'quantity' => 8,
            'expiration_date' => now()->subDay()->toDateString(),
        ]);

        $partiallyValidProduct = Product::factory()->create([
            'stock_quantity' => 10,
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

        $response = $this->actingAs(User::factory()->create())
            ->get(route('products.index'));

        $response
            ->assertOk()
            ->assertViewHas('totalStockValue', fn ($value) => (float) $value === 20.0);
    }

    public function test_product_can_be_created_with_valid_data(): void
    {
        $category = Category::factory()->create();
        $supplier = Supplier::factory()->create();

        $response = $this->actingAs(User::factory()->create())
            ->post(route('products.store'), $this->validProductPayload($category, $supplier));

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', [
            'name' => 'Adubo Organico',
            'category_id' => $category->id,
            'supplier_id' => $supplier->id,
            'stock_quantity' => 12,
            'minimum_stock' => 4,
        ]);
        $this->assertDatabaseHas('product_batches', [
            'supplier_id' => $supplier->id,
            'number' => 'LOTE-TESTE-001',
            'original_quantity' => 12,
            'quantity' => 12,
        ]);
    }

    public function test_product_cannot_be_created_with_invalid_data(): void
    {
        $response = $this->actingAs(User::factory()->create())
            ->from(route('products.create'))
            ->post(route('products.store'), [
                'name' => '',
                'selling_price' => 0,
                'cost_price' => -1,
                'category_id' => 999,
                'stock_quantity' => -1,
                'minimum_stock' => -1,
            ]);

        $response
            ->assertRedirect(route('products.create'))
            ->assertSessionHasErrors([
                'name',
                'selling_price',
                'cost_price',
                'category_id',
                'stock_quantity',
                'minimum_stock',
            ]);
        $this->assertDatabaseCount('products', 0);
    }

    public function test_product_can_be_updated(): void
    {
        $product = Product::factory()->create(['name' => 'Produto Antigo']);
        $category = Category::factory()->create();
        $supplier = Supplier::factory()->create();

        $response = $this->actingAs(User::factory()->create())
            ->put(route('products.update', $product), [
                'name' => 'Produto Atualizado',
                'description' => 'Descricao atualizada',
                'selling_price' => '29.90',
                'cost_price' => '14.50',
                'category_id' => $category->id,
                'supplier_id' => $supplier->id,
                'minimum_stock' => 8,
            ]);

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Produto Atualizado',
            'category_id' => $category->id,
            'supplier_id' => $supplier->id,
            'minimum_stock' => 8,
        ]);
    }

    public function test_product_can_be_deleted(): void
    {
        $product = Product::factory()->create();

        $response = $this->actingAs(User::factory()->create())
            ->delete(route('products.destroy', $product));

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_product_appears_in_database_after_creation(): void
    {
        $category = Category::factory()->create();
        $supplier = Supplier::factory()->create();
        $expirationDate = now()->addDays(45)->toDateString();

        $this->actingAs(User::factory()->create())
            ->post(route('products.store'), $this->validProductPayload($category, $supplier, [
                'name' => 'Semente Fiscalizada',
                'expiration_date' => $expirationDate,
            ]));

        $product = Product::where('name', 'Semente Fiscalizada')->firstOrFail();

        $this->assertSame($category->id, $product->category_id);
        $this->assertSame($supplier->id, $product->supplier_id);
        $this->assertSame($expirationDate, $product->expiration_date?->toDateString());
    }

    private function validProductPayload(Category $category, Supplier $supplier, array $overrides = []): array
    {
        return array_merge([
            'name' => 'Adubo Organico',
            'description' => 'Produto para reposicao de estoque.',
            'selling_price' => '35.90',
            'cost_price' => '18.25',
            'category_id' => $category->id,
            'supplier_id' => $supplier->id,
            'stock_quantity' => 12,
            'minimum_stock' => 4,
            'expiration_date' => now()->addMonths(6)->toDateString(),
            'batch_number' => 'LOTE-TESTE-001',
        ], $overrides);
    }
}
