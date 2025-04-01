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
            'is_sent' => fake()->boolean(),
            'is_paid' => fake()->boolean(),
            'client_id' => fake()->numberBetween(1, 10),
            'reference' => fake()->randomNumber(),
            'with_tva' => true,
            'remise' => fake()->randomElement([0, 10, 20, 30, 50]),
            'created_at' => $this->faker->dateTimeBetween('-2 days', 'now'),
        ];
    }
}