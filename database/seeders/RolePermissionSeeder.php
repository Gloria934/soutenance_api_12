<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::where('name', 'admin')->first();
        $patient = Role::where('name', 'patient')->first();
        $secretaire = Role::where('name', 'secretaire')->first();
        $pharmacien = Role::where('name', 'pharmacien')->first();
        $service_medical = Role::where('name', 'service_medical')->first();
        $accueil = Role::where('name', 'accueil')->first();

        $admin->syncPermissions(Permission::all());

        $patient->givePermissionTo([
            'voir utilisateurs',
        ]);

        $secretaire->givePermissionTo([
            'créer ordonnance',
            'view patients',
        ]);

        $pharmacien->givePermissionTo([
            'gérer pharmacie',
        ]);

        $service_medical->givePermissionTo([
            'créer ordonnance',
            'view patients',
        ]);

        $accueil->givePermissionTo([
            'créer ordonnance',
            'view patients',
        ]);
    }
}

