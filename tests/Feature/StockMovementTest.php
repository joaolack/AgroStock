<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\StockMovement;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockMovementTest extends TestCase
{
    use RefreshDatabase;

    public function test_entry_increases_product_stock_and_registers_movement(): void
    {
        $user = User::factory()->create();
        $supplier = Supplier::factory()->create();
        $product = Product::factory()->create([
            'stock_quantity' => 5,
            'supplier_id' => null,
            'expiration_date' => null,
        ]);

        $response = $this->actingAs($user)
            ->post(route('stock-movements.store'), [
                'product_id' => $product->id,
                'quantity' => 7,
                'type' => 'entry',
                'batch_number' => 'ENTRADA-001',
                'supplier_id' => $supplier->id,
                'expiration_date' => now()->addMonths(2)->toDateString(),
            ]);

        $response->assertRedirect(route('stock-movements.index'));
        $this->assertSame(12, $product->refresh()->stock_quantity);
        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $product->id,
            'user_id' => $user->id,
            'type' => 'entry',
            'quantity' => 7,
            'previous_quantity' => 5,
            'new_quantity' => 12,
        ]);
        $this->assertDatabaseHas('product_batches', [
            'product_id' => $product->id,
            'supplier_id' => $supplier->id,
            'number' => 'ENTRADA-001',
            'quantity' => 7,
        ]);
    }

    public function test_exit_reduces_product_stock_and_registers_movement(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock_quantity' => 10]);
        $batch = ProductBatch::factory()->for($product)->create([
            'original_quantity' => 10,
            'quantity' => 10,
        ]);

        $response = $this->actingAs($user)
            ->post(route('stock-movements.store'), [
                'product_id' => $product->id,
                'quantity' => 4,
                'type' => 'exit',
            ]);

        $response->assertRedirect(route('stock-movements.index'));
        $this->assertSame(6, $product->refresh()->stock_quantity);
        $this->assertSame(6, $batch->refresh()->quantity);
        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $product->id,
            'product_batch_id' => $batch->id,
            'user_id' => $user->id,
            'type' => 'exit',
            'quantity' => 4,
            'previous_quantity' => 10,
            'new_quantity' => 6,
        ]);
    }

    public function test_exit_does_not_allow_negative_stock(): void
    {
        $product = Product::factory()->create(['stock_quantity' => 3]);
        ProductBatch::factory()->for($product)->create([
            'original_quantity' => 3,
            'quantity' => 3,
        ]);

        $response = $this->actingAs(User::factory()->create())
            ->from(route('stock-movements.index'))
            ->post(route('stock-movements.store'), [
                'product_id' => $product->id,
                'quantity' => 4,
                'type' => 'exit',
            ]);

        $response
            ->assertRedirect(route('stock-movements.index'))
            ->assertSessionHasErrors('quantity');
        $this->assertSame(3, $product->refresh()->stock_quantity);
        $this->assertDatabaseCount('stock_movements', 0);
    }

    public function test_exit_does_not_consume_expired_batches(): void
    {
        $product = Product::factory()->create([
            'name' => 'Fertilizante NPK 10-10-10 (25kg)',
            'stock_quantity' => 12,
        ]);
        $expiredBatch = ProductBatch::factory()->for($product)->create([
            'number' => 'NPK-VENCIDO',
            'original_quantity' => 10,
            'quantity' => 10,
            'expiration_date' => now()->subDay()->toDateString(),
        ]);
        $validBatch = ProductBatch::factory()->for($product)->create([
            'number' => 'NPK-VALIDO',
            'original_quantity' => 2,
            'quantity' => 2,
            'expiration_date' => now()->addDay()->toDateString(),
        ]);

        $response = $this->actingAs(User::factory()->create())
            ->from(route('stock-movements.index'))
            ->post(route('stock-movements.store'), [
                'product_id' => $product->id,
                'quantity' => 12,
                'type' => 'exit',
            ]);

        $response
            ->assertRedirect(route('stock-movements.index'))
            ->assertSessionHasErrors('quantity');
        $this->assertSame(12, $product->refresh()->stock_quantity);
        $this->assertSame(10, $expiredBatch->refresh()->quantity);
        $this->assertSame(2, $validBatch->refresh()->quantity);
        $this->assertDatabaseCount('stock_movements', 0);
    }

    public function test_exit_form_shows_only_valid_stock_available_for_exit(): void
    {
        $product = Product::factory()->create([
            'name' => 'Fertilizante NPK 10-10-10 (25kg)',
            'stock_quantity' => 12,
        ]);
        ProductBatch::factory()->for($product)->create([
            'number' => 'NPK-VENCIDO',
            'original_quantity' => 10,
            'quantity' => 10,
            'expiration_date' => now()->subDay()->toDateString(),
        ]);
        ProductBatch::factory()->for($product)->create([
            'number' => 'NPK-VALIDO',
            'original_quantity' => 2,
            'quantity' => 2,
            'expiration_date' => now()->addDay()->toDateString(),
        ]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('stock-movements.index'));

        $response
            ->assertOk()
            ->assertSee('Fertilizante NPK 10-10-10 (25kg) (Disponivel para saida: 2)')
            ->assertDontSee('Fertilizante NPK 10-10-10 (25kg) (Disponivel para saida: 12)');
    }

    public function test_movement_is_associated_with_product_and_user(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock_quantity' => 8]);
        ProductBatch::factory()->for($product)->create([
            'original_quantity' => 8,
            'quantity' => 8,
        ]);

        $this->actingAs($user)
            ->post(route('stock-movements.store'), [
                'product_id' => $product->id,
                'quantity' => 2,
                'type' => 'exit',
            ]);

        $movement = StockMovement::firstOrFail();

        $this->assertTrue($movement->product->is($product));
        $this->assertTrue($movement->user->is($user));
    }
}
