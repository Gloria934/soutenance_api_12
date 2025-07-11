<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        // Réinitialiser le cache des permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Créer des rôles
        $roles = ['pending', 'admin', 'admin_pharmacie', 'personnel_accueil', 'pharmacie', 'service_medical', 'spécialiste', 'patient'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Créer des permissions
        // Admin
        Permission::firstOrCreate(['name' => 'gerer_services']);
        Permission::firstOrCreate(['name' => 'gerer_roles_utilisateur']);
        Permission::firstOrCreate(['name' => 'gerer_notifications']);

        // Service médical et spécialiste
        Permission::firstOrCreate(['name' => 'voir_liste_rendez_vous']);
        Permission::firstOrCreate(['name' => 'definir_date_rendez_vous']);
        Permission::firstOrCreate(['name' => 'creer_ordonnance']);

        // Personnel accueil
        Permission::firstOrCreate(['name' => 'ajouter_utilisateur']);
        Permission::firstOrCreate(['name' => 'creer_rendez_vous']);
        Permission::firstOrCreate(['name' => 'consulter_liste_rendez_vous']);
        Permission::firstOrCreate(['name' => 'editer_rendez_vous']);
        Permission::firstOrCreate(['name' => 'effectuer_paiements']);

        // Pharmacie
        Permission::firstOrCreate(['name' => 'scanner_ordonnance']);

        // Admin pharmacie
        Permission::firstOrCreate(['name' => 'ajouter_medicament']);
        Permission::firstOrCreate(['name' => 'editer_medicament']);
        Permission::firstOrCreate(['name' => 'gerer_details_medicament']);

        // Assigner des permissions à des rôles
        $adminRole = Role::findByName('admin');
        $adminRole->givePermissionTo([
            'gerer_services',
            'gerer_roles_utilisateur',
            'gerer_notifications'
        ]);

        $serviceMedicalRole = Role::findByName('service_medical');
        $serviceMedicalRole->givePermissionTo([
            'voir_liste_rendez_vous',
            'definir_date_rendez_vous',
            'creer_ordonnance'
        ]);
        $specialisteRole = Role::findByName('spécialiste');
        $specialisteRole->givePermissionTo([
            'voir_liste_rendez_vous',
            'definir_date_rendez_vous',
            'creer_ordonnance'
        ]);

        $personnelAccueilRole = Role::findByName('personnel_accueil');
        $personnelAccueilRole->givePermissionTo([
            'ajouter_utilisateur',
            'creer_rendez_vous',
            'consulter_liste_rendez_vous',
            'editer_rendez_vous',
            'effectuer_paiements'
        ]);

        $pharmacieRole = Role::findByName('pharmacie');
        $pharmacieRole->givePermissionTo([
            'scanner_ordonnance',
            'creer_ordonnance'
        ]);

        $adminPharmacieRole = Role::findByName('admin_pharmacie');
        $adminPharmacieRole->givePermissionTo([
            'ajouter_medicament',
            'editer_medicament',
            'gerer_details_medicament'
        ]);

        // Rôles sans permissions pour l'instant
        // 'pending' et 'patient' n'ont pas de permissions assignées
        // $user = User::findOrFail(22);
        // $user->syncRoles(['service_medical']);

        \Log::info('Seeder de rôle terminé avec succès');
    }
}