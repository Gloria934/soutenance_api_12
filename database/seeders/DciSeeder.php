<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dci;

class DciSeeder extends Seeder
{
    public function run(): void
    {
        $dcis = ['Paracétamol',
                'Amoxicilline',
                'Metronidazole',
                'Ciprofloxacine',
                'Cotrimoxazole',
                'Ibuprofène'];

        foreach ($dcis as $nom) {
            Dci::create(['nom' => $nom]);
        }
    }
}
