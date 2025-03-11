<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket_row>
 */
class TicketRowFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ticket_id' => fake()->numberBetween(1, 30),
            'category_id' => fake()->numberBetween(1, 2),
            'quantity' => fake()->numberBetween(1, 10),
            'price' => fake()->randomFloat(2, 10, 1000),
        ];
    }
}
