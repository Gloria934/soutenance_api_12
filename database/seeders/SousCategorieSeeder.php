<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SousCategory;
use App\Models\Category;

class SousCategorieSeeder extends Seeder
{
    public function run(): void
    {

        $sousCategories = [
            ['nom' => 'Générique', 'categorie_id' => 1],
            ['nom' => 'Désinfectants', 'categorie_id' => 2],
            ['nom' => 'Gants médicaux', 'categorie_id' => 2],
            ['nom' => 'Seringues', 'categorie_id' => 3],
            ['nom' => 'Thermomètres', 'categorie_id' => 3],
            ['nom' => 'Laits thérapeutiques', 'categorie_id' => 4],
            ['nom' => 'Compléments nutritionnels', 'categorie_id' => 4],
            ['nom' => 'Vaccins pédiatriques', 'categorie_id' => 5],
            ['nom' => 'Vaccins adultes', 'categorie_id' => 5]
        ];

        foreach ($sousCategories as $data) {
            SousCategory::create($data);
        }
    }
}




