<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExpirationDateTest extends TestCase
{
    use RefreshDatabase;

    public function test_expiration_page_identifies_expired_today_soon_and_safe_batches(): void
    {
        $this->createProductBatchWithExpiration('Produto vencido', now()->subDay()->toDateString());
        $this->createProductBatchWithExpiration('Produto vence hoje', now()->toDateString());
        $this->createProductBatchWithExpiration('Produto vence em breve', now()->addDays(30)->toDateString());
        $this->createProductBatchWithExpiration('Produto seguro', now()->addDays(90)->toDateString());

        $response = $this->actingAs(User::factory()->create())
            ->get(route('expiration-date.index', [
                'view' => 'batch',
                'stock_only' => '1',
            ]));

        $response
            ->assertOk()
            ->assertViewHas('summary', [
                'expired' => 1,
                'soon' => 2,
                'safe' => 1,
            ])
            ->assertViewHas('items', function ($items) {
                $statuses = $items->getCollection()
                    ->mapWithKeys(fn (array $item) => [$item['product']->name => $item['status']]);

                return $statuses['Produto vencido'] === 'Vencido'
                    && $statuses['Produto vence hoje'] === 'Vence em breve'
                    && $statuses['Produto vence em breve'] === 'Vence em breve'
                    && $statuses['Produto seguro'] === 'Seguro';
            });
    }

    private function createProductBatchWithExpiration(string $productName, string $expirationDate): void
    {
        $product = Product::factory()->create([
            'name' => $productName,
            'stock_quantity' => 5,
            'expiration_date' => $expirationDate,
        ]);

        ProductBatch::factory()->for($product)->create([
            'number' => 'LOTE-'.$product->id,
            'original_quantity' => 5,
            'quantity' => 5,
            'expiration_date' => $expirationDate,
            'supplier_id' => $product->supplier_id,
        ]);
    }
}
