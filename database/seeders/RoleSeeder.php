<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // On évite les doublons si jamais le seeder est relancé
        $roles = ['patient', 'secretaire', 'pharmacien', 'admin', 'service', 'accueil'];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }
    }
}
