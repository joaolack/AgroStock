<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StockMovement>
 */
class StockMovementFactory extends Factory
{
    public function definition(): array
    {
        $previousQuantity = fake()->numberBetween(10, 50);
        $quantity = fake()->numberBetween(1, 10);

        return [
            'product_id' => Product::factory(),
            'product_batch_id' => ProductBatch::factory(),
            'user_id' => User::factory(),
            'type' => 'entry',
            'reason' => 'manual',
            'quantity' => $quantity,
            'previous_quantity' => $previousQuantity,
            'new_quantity' => $previousQuantity + $quantity,
        ];
    }
}
