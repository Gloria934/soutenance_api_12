<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Forme;

class FormeSeeder extends Seeder
{
    public function run(): void
    {
        $formes = [
            'Comprimé',
            'Gélule',
            'Sirop',
            'Pommade',
            'Crème',
            'Injection intraveineuse',
            'Gouttes ophtalmiques',
            'Poudre orale',
            'Gel',
            'Inhalateur',
        ];

        foreach ($formes as $nom) {
            Forme::create(['nom' => $nom]);
        }
    }
}






