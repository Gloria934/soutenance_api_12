<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Classe;

class ClasseSeeder extends Seeder
{
    public function run(): void
    {
        $classes = ['Antibiotique',
                    'Antipaludéen',
                    'Antalgique',
                    'Antipyrétique',
                    'Anti-inflammatoire',
                    'Antifongique'
                ];

        foreach ($classes as $nom) {
            Classe::create(['nom' => $nom]);
        }
    }
}
