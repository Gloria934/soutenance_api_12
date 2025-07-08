<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['pending', 'admin', 'admin_pharmacie', 'personnel_accueil', 'pharmacie', 'service_medical', 'patient'];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Créer des permissions
        // service médical
        Permission::create(['name' => 'voir liste rendez-vous']);
        Permission::create(['name' => 'définir date rendez-vous']);
        Permission::create(['name' => 'créer ordonnance']);

        // personnel accueil
        Permission::create(['name' => 'ajouter utilisateur']);
        Permission::create(['name' => 'créer rendez-vous']);
        Permission::create(['name' => 'consulter liste rendez-vous']);
        Permission::create(['name' => 'éditer rendez-vous']);
        Permission::create(['name' => 'effectuer paiements']);

        //
        Permission::create(['name' => 'view posts']);
        Permission::create(['name' => 'view posts']);
        Permission::create(['name' => 'view posts']);
        Permission::create(['name' => 'view posts']);
        Permission::create(['name' => 'view posts']);
        Permission::create(['name' => 'view posts']);
        Permission::create(['name' => 'view posts']);


        // Assigner des permissions à des rôles
        $adminRole = Role::findByName('admin');
        $adminRole->givePermissionTo(['create posts', 'edit posts', 'delete posts', 'view posts']);

        $editorRole = Role::findByName('editor');
        $editorRole->givePermissionTo(['create posts', 'edit posts', 'view posts']);

        $userRole = Role::findByName('user');
        $userRole->givePermissionTo(['view posts']);

    }
}
