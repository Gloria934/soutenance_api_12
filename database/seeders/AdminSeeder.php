<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Admin;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Créer ou récupérer le rôle admin
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // 2. Vérifier si l'admin existe déjà
        $adminUser = User::where('email', 'admin@example.com')->first();

        if (!$adminUser) {
            // 3. Créer l'utilisateur admin
            $adminUser = User::create([
                'nom' => 'Super Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'), // Changez ce mot de passe en prod
            ]);

            // 4. Assigner le rôle
            $adminUser->assignRole($adminRole);

            // 5. Créer l'enregistrement admin (si pas géré automatiquement par l'Observer)
            if (!Admin::where('id', $adminUser->id)->exists()) {
                Admin::create([
                    'id' => $adminUser->id,
                ]);
            }

            $this->command->info('Super Admin créé avec succès !');
        } else {
            $this->command->info('Un admin existe déjà avec cet email.');
        }
    }
}
