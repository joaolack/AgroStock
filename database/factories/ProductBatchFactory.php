<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductBatch>
 */
class ProductBatchFactory extends Factory
{
    public function definition(): array
    {
        $quantity = fake()->numberBetween(1, 50);

        return [
            'product_id' => Product::factory(),
            'supplier_id' => Supplier::factory(),
            'number' => fake()->unique()->bothify('LOTE-####'),
            'original_quantity' => $quantity,
            'quantity' => $quantity,
            'expiration_date' => fake()->dateTimeBetween('+1 month', '+1 year')->format('Y-m-d'),
        ];
    }
}
