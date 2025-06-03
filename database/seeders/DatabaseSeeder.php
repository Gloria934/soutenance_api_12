<?php

namespace Database\Seeders;

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

        // Appeler les seeders dans l'ordre pour respecter les dépendances
        $this->call([
            CategorySeeder::class,
            SousCategorySeeder::class,
            DciSeeder::class,
            ClasseMedicamentSeeder::class,
            FormeSeeder::class,
            ServiceSeeder::class,
            RolesSeeder::class,
        ]);
    }
}
