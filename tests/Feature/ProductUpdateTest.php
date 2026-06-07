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

    public function test_updating_product_expiration_date_updates_active_batch_expiration_date(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'name' => 'Insumos',
            'description' => null,
        ]);
        $oldExpirationDate = now()->addYear()->toDateString();
        $newExpirationDate = now()->addMonth()->toDateString();
        [$product, $batch] = $this->createProductWithBatch($category, $oldExpirationDate, $oldExpirationDate);

        $response = $this->actingAs($user)->put(route('products.update', $product), [
            'name' => $product->name,
            'description' => $product->description,
            'selling_price' => '15.00',
            'cost_price' => '10.00',
            'category_id' => $category->id,
            'supplier_id' => null,
            'minimum_stock' => 5,
            'expiration_date' => $newExpirationDate,
        ]);

        $response->assertRedirect(route('products.index'));
        $this->assertProductAndBatchExpirationDate($product->id, $batch->id, $newExpirationDate);
    }

    public function test_saving_product_again_fixes_existing_expiration_date_mismatch_with_active_batch(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'name' => 'Insumos',
            'description' => null,
        ]);
        $oldBatchExpirationDate = now()->addYear()->toDateString();
        $productExpirationDate = now()->addMonth()->toDateString();
        [$product, $batch] = $this->createProductWithBatch($category, $productExpirationDate, $oldBatchExpirationDate);

        $response = $this->actingAs($user)->put(route('products.update', $product), [
            'name' => $product->name,
            'description' => $product->description,
            'selling_price' => '15.00',
            'cost_price' => '10.00',
            'category_id' => $category->id,
            'supplier_id' => null,
            'minimum_stock' => 5,
            'expiration_date' => $productExpirationDate,
        ]);

        $response->assertRedirect(route('products.index'));
        $this->assertProductAndBatchExpirationDate($product->id, $batch->id, $productExpirationDate);
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

    private function assertProductAndBatchExpirationDate(int $productId, int $batchId, string $expirationDate): void
    {
        $this->assertDatabaseHas('products', [
            'id' => $productId,
            'expiration_date' => $expirationDate,
        ]);
        $this->assertDatabaseHas('product_batches', [
            'id' => $batchId,
            'expiration_date' => $expirationDate,
        ]);
    }
}
