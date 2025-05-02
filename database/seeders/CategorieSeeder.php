<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorieSeeder extends Seeder
{
    public function run(): void
    {
        $categories = ['Médicament', 
                    'Produit sanitaire', 
                    'Dispositif médical', 
                    'Produit nutritionnel', 
                    'Vaccin'];

        foreach ($categories as $nom) {
            Category::create(['nom' => $nom]);
        }
    }
}
