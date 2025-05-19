<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use PhpParser\Node\Stmt\Foreach_;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $itemsWith21TVA = [
            'Déco',
            'Déco Noël',
            'Déco Pâques',
        ];

        $itemsWith6TVA = [
            'Fleurs',
            'Plante',
            'Bouquet saint-valentin',
            'Gerbe mortuaires',
        ];

        foreach ($itemsWith21TVA as  $item) {
            Category::create([
                'name' => $item,
                'tva' => 21
            ]);
        }

        foreach ($itemsWith6TVA as  $item) {
            Category::create([
                'name' => $item,
                'tva' => 6
            ]);
        }
    }
}
