<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Client;
use App\Models\Ticket;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        Category::factory()->create([
            'name' => 'Décoration',
            'tva' => 21,
        ]);
        Category::factory()->create([
            'name' => 'Fleurs',
            'tva' => 6,
        ]);

        Client::factory(10)->create();

        Ticket::factory(30)->create();
    }
}
