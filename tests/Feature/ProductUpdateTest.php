<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ProductUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_edit_page_shows_batch_validity_without_expiration_date_field(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'name' => 'Insumos',
            'description' => null,
        ]);
        [$product] = $this->createProductWithBatch(
            $category,
            now()->addYear()->toDateString(),
            now()->addMonth()->toDateString()
        );

        $response = $this->actingAs($user)->get(route('products.edit', $product));

        $response
            ->assertOk()
            ->assertSee('Validade dos lotes', false)
            ->assertDontSee('name="expiration_date"', false);
    }

    public function test_updating_product_keeps_product_and_batch_expiration_dates_unchanged(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'name' => 'Insumos',
            'description' => null,
        ]);
        $productExpirationDate = now()->addYear()->toDateString();
        $batchExpirationDate = now()->addMonth()->toDateString();
        [$product, $batch] = $this->createProductWithBatch($category, $productExpirationDate, $batchExpirationDate);

        $response = $this->actingAs($user)->put(route('products.update', $product), [
            'name' => $product->name.' atualizado',
            'description' => $product->description,
            'selling_price' => '15.00',
            'cost_price' => '10.00',
            'category_id' => $category->id,
            'supplier_id' => null,
            'minimum_stock' => 5,
            'expiration_date' => now()->addDays(10)->toDateString(),
        ]);

        $response->assertRedirect(route('products.index'));
        $this->assertSame(
            $productExpirationDate,
            Product::findOrFail($product->id)->expiration_date?->toDateString()
        );
        $this->assertSame(
            $batchExpirationDate,
            $batch->refresh()->expiration_date?->toDateString()
        );
    }

    private function createProductWithBatch(Category $category, string $productExpirationDate, string $batchExpirationDate): array
    {
        $product = Product::create([
            'name' => 'Produto com validade '.Str::random(8),
            'description' => 'Cadastro inicial',
            'selling_price' => 15,
            'cost_price' => 10,
            'stock_quantity' => 20,
            'minimum_stock' => 5,
            'expiration_date' => $productExpirationDate,
            'category_id' => $category->id,
            'supplier_id' => null,
        ]);

        $batch = $product->batches()->create([
            'supplier_id' => null,
            'number' => 'INICIAL-'.Str::random(8),
            'original_quantity' => 20,
            'quantity' => 20,
            'expiration_date' => $batchExpirationDate,
        ]);

        return [$product, $batch];
    }

}
