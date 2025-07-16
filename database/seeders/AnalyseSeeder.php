<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AnalyseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('analyses')->insert([
            [
                'nom' => 'Analyse de sang complète',
                'description' => 'Bilan hématologique complet',
                'prix' => 3000.0,
            ],
            [
                'nom' => 'Glycémie à jeun',
                'description' => 'Mesure du taux de glucose dans le sang après jeûne',
                'prix' => 1500.0,
            ],
            [
                'nom' => 'Cholestérol total',
                'description' => 'Mesure du cholestérol total dans le sang',
                'prix' => 2500.0,
            ],
            [
                'nom' => 'Analyse d\'urine',
                'description' => 'Examen physique et chimique de l\'urine',
                'prix' => 1000.0,
            ],
            [
                'nom' => 'Analyse de selles',
                'description' => 'Recherche de parasites et autres anomalies dans les selles',
                'prix' => 1500.0,
            ],
            [
                'nom' => 'Test VIH',
                'description' => 'Dépistage du VIH',
                'prix' => 4000.0,
            ],
            [
                'nom' => 'Test paludisme',
                'description' => 'Dépistage du paludisme',
                'prix' => 1000.0,
            ],
            [
                'nom' => 'Test de grossesse',
                'description' => 'Dépistage de la grossesse',
                'prix' => 1000.0,
            ],
        ]);
    }
}