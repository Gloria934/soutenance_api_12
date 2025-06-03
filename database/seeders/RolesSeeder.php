<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['pending', 'admin', 'admin_pharmacie', 'personnel_accueil', 'pharmacie', 'service_medical', 'patient'];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }
    }
}
