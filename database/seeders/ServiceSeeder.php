<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'nom' => 'Pédiatrie',
                'telephone' => '0151439322',
                'email' => 'pediatrie@centre-sante.com',
                'prix_rdv' => 2000,
                'heure_ouverture' => '08:00:00',
                'heure_fermeture' => '16:00:00',
                'duree_moy_rdv' => '00:30:00',
                'sous_rdv' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Oncologie',
                'telephone' => '0151439323',
                'email' => 'oncologie@centre-sante.com',
                'prix_rdv' => 5000,
                'heure_ouverture' => '09:00:00',
                'heure_fermeture' => '17:00:00',
                'duree_moy_rdv' => '01:00:00',
                'sous_rdv' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Médecine Générale',
                'telephone' => '0151439324',
                'email' => 'medecine.generale@centre-sante.com',
                'prix_rdv' => 1500,
                'heure_ouverture' => '07:30:00',
                'heure_fermeture' => '18:00:00',
                'duree_moy_rdv' => '00:20:00',
                'sous_rdv' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Cardiologie',
                'telephone' => '0151439325',
                'email' => 'cardiologie@centre-sante.com',
                'prix_rdv' => 3500,
                'heure_ouverture' => '08:30:00',
                'heure_fermeture' => '16:30:00',
                'duree_moy_rdv' => '00:45:00',
                'sous_rdv' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Gynécologie',
                'telephone' => '0151439326',
                'email' => 'gynecologie@centre-sante.com',
                'prix_rdv' => 2500,
                'heure_ouverture' => '09:00:00',
                'heure_fermeture' => '17:00:00',
                'duree_moy_rdv' => '00:30:00',
                'sous_rdv' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Dermatologie',
                'telephone' => '0151439327',
                'email' => 'dermatologie@centre-sante.com',
                'prix_rdv' => 2000,
                'heure_ouverture' => '10:00:00',
                'heure_fermeture' => '18:00:00',
                'duree_moy_rdv' => '00:25:00',
                'sous_rdv' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Ophtalmologie',
                'telephone' => '0151439328',
                'email' => 'ophtalmologie@centre-sante.com',
                'prix_rdv' => 3000,
                'heure_ouverture' => '08:00:00',
                'heure_fermeture' => '15:00:00',
                'duree_moy_rdv' => '00:30:00',
                'sous_rdv' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}