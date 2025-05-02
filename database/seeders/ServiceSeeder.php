<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\Admin;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
         // Récupérer un admin existant (le premier trouvé)
        $admin = Admin::first();
        
        if (!$admin) {
            throw new \Exception("Aucun admin trouvé. Veuillez exécuter AdminSeeder d'abord.");
        }
        // 3. Liste des services à créer
        $services = [
            [
                'nom' => 'Pédiatrie',
                'sous_rdv' => true,
                'admin_id' => $admin->id
            ],
            [
                'nom' => 'Consultation générale', 
                'sous_rdv' => true,
                'admin_id' => $admin->id
            ],
            [
                'nom' => 'Urgence',
                'sous_rdv' => false,
                'admin_id' => $admin->id
            ],
            [
                'nom' => 'Consultation générale',
                'sous_rdv' => true,
                'admin_id' => $admin->id
            ],
            [
                'nom' => 'Cardiologie',
                'sous_rdv' => true,
                'admin_id' => $admin->id
            ]
        ];

        // 4. Création des services avec vérification d'existence
        foreach ($services as $serviceData) {
            Service::firstOrCreate(
                ['nom' => $serviceData['nom']],
                $serviceData
            );
        }
    }
}