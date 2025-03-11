<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'firstname' => fake()->firstName(),
            'lastname' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'company' => fake()->company(),
            'city' => fake()->city(),
            'country' => fake()->country(),
            'address' => fake()->address(),
            'phone' => fake()->phoneNumber(),
            'tva_number' => fake()->phoneNumber(),

        ];
    }
}
