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
            'name' => 'Déco',
            'tva' => 21,
        ]);
        Category::factory()->create([
            'name' => 'Déco Noël',
            'tva' => 21,
        ]);
        Category::factory()->create([
            'name' => 'Déco Pâques',
            'tva' => 21,
        ]);
        Category::factory()->create([
            'name' => 'Fleurs',
            'tva' => 6,
        ]);
        Category::factory()->create([
            'name' => 'Plante',
            'tva' => 6,
        ]);
        Category::factory()->create([
            'name' => 'Bouquet saint-valentin',
            'tva' => 6,
        ]);
        Category::factory()->create([
            'name' => 'Gerbe mortuaires',
            'tva' => 6,
        ]);

        Client::factory(10)->create();

        Client::factory()->create([
            'firstname' => 'Hadrien',
            'lastname' => 'Janssens',
            'email' => 'hadrien.janssens7@gmail.com',
            'company' => 'MyCompany',
            'city' => 'Morlanwelz',
            'country' => 'Belgique',
            'address' => 'Rue joseph Wauters 81',
            'phone' => '0499/12.34.56',
            'tva_number' => 'BE0123456789',
        ]);

        // Ticket::factory(30)->create();
    }
}
