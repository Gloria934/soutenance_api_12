<?php

namespace Database\Seeders;

use App\Models\SousCategory;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Truncate tables in the correct order to avoid foreign key constraints
        // We are not disabling foreign key checks due to permission issues on Render.
        SousCategory::truncate();
        Category::truncate();

        // User::factory(10)->create();

        // Appeler les seeders dans l'ordre pour respecter les dépendances
        $this->call([
            CategorySeeder::class,
            SousCategorySeeder::class,
            DciSeeder::class,
            ClasseMedicamentSeeder::class,
            FormeSeeder::class,
            LangueSeeder::class,
            ServiceSeeder::class,
            RolesSeeder::class,
            AnalyseSeeder::class,
        ]);
    }
}
