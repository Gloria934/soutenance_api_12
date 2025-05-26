<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the migrations.
     */
    public function run(): void
    {
        // Initialiser Faker en français
        $faker = Faker::create('fr_FR');

        // Désactiver les contraintes de clés étrangères
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Vider la table avant insertion
        Category::truncate();

        // Liste de catégories pour une pharmacie
        $categories = [
            ['nom' => 'Antalgiques', 'description' => $faker->sentence(6)],
            ['nom' => 'Antibiotiques', 'description' => $faker->sentence(6)],
            ['nom' => 'Antihistaminiques', 'description' => $faker->sentence(6)],
            ['nom' => 'Vitamines', 'description' => $faker->sentence(6)],
            ['nom' => 'Dermatologiques', 'description' => $faker->sentence(6)],
            ['nom' => 'Antiparasitaires', 'description' => $faker->sentence(6)],
        ];

        // Insérer les catégories
        foreach ($categories as $category) {
            Category::create($category);
        }

        // Réactiver les contraintes de clés étrangères
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}