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
        $this->call([
            RoleSeeder::class,   //Création des rôles
            PermissionSeeder::class, // Création des permissions
            RolePermissionSeeder::class, // Association rôles/permissions
            AdminSeeder::class,       // Création d’un admin par exemple
            CategorieSeeder::class,
            SousCategorieSeeder::class,
            FormeSeeder::class,
            ClasseSeeder::class,
            DciSeeder::class,
        
        
        ]);

        // User::factory(10)->create();

        /*User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);*/
    }
}


                
                 
                
                
        
    
