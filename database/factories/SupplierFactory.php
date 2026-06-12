<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Supplier>
 */
class SupplierFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->company(),
            'contact_name' => fake()->name(),
            'phone' => fake()->numerify('(##) #####-####'),
            'email' => fake()->unique()->safeEmail(),
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'state' => fake()->lexify('??'),
            'zip_code' => fake()->numerify('#####-###'),
            'notes' => fake()->sentence(),
            'active' => true,
        ];
    }
}
