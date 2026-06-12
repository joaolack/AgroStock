<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(3, true),
            'description' => fake()->sentence(),
            'selling_price' => fake()->randomFloat(2, 10, 500),
            'cost_price' => fake()->randomFloat(2, 1, 300),
            'stock_quantity' => fake()->numberBetween(1, 50),
            'minimum_stock' => fake()->numberBetween(1, 10),
            'expiration_date' => fake()->dateTimeBetween('+1 month', '+1 year')->format('Y-m-d'),
            'category_id' => Category::factory(),
            'supplier_id' => Supplier::factory(),
        ];
    }
}
