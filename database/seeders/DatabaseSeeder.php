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

        User::factory()->create([
            'name' => env('ADMIN_NAME'),
            'email' => env('ADMIN_EMAIL'),
        ]);

        // $this->call([
        //     CategorySeeder::class
        // ]);
        // $this->call([
        //     ClientSeeder::class,
        // ]);


        // Category::factory()->create([
        //     'name' => 'Déco',
        //     'tva' => 21,
        // ]);
        // Category::factory()->create([
        //     'name' => 'Déco Noël',
        //     'tva' => 21,
        // ]);
        // Category::factory()->create([
        //     'name' => 'Déco Pâques',
        //     'tva' => 21,
        // ]);
        // Category::factory()->create([
        //     'name' => 'Fleurs',
        //     'tva' => 6,
        // ]);
        // Category::factory()->create([
        //     'name' => 'Plante',
        //     'tva' => 6,
        // ]);
        // Category::factory()->create([
        //     'name' => 'Bouquet saint-valentin',
        //     'tva' => 6,
        // ]);
        // Category::factory()->create([
        //     'name' => 'Gerbe mortuaires',
        //     'tva' => 6,
        // ]);


        // Ticket::factory(30)->create();
    }
}
