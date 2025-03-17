<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // 'with_invoice' => fake()->boolean(),
            'is_sent' => fake()->boolean(),
            'is_paid' => fake()->boolean(),
            'client_id' => fake()->numberBetween(1, 10),
        ];
    }
}
