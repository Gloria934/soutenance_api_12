<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\SousCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SousCategorySeeder extends Seeder
{
    /**
     * Run the migrations.
     */
    public function run(): void
    {
        // Désactiver les contraintes de clés étrangères
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Vider la table avant insertion
        SousCategory::truncate();

        // Réactiver les contraintes de clés étrangères
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Liste de sous-catégories par catégorie, inspirée de l'exemple
        $sousCategories = [
            'Antalgiques' => [
                'Antipyrétiques',
                'Anti-inflammatoires',
                'Opioïdes',
                'Analgésiques topiques',
            ],
            'Antibiotiques' => [
                'Antibactériens',
                'Antiviraux',
                'Antifongiques',
                'Antituberculeux',
            ],
            'Antihistaminiques' => [
                'Statines', // Conservé de ton exemple, bien que moins courant pour les antihistaminiques
                'H1-antagonistes',
                'H2-antagonistes',
            ],
            'Vitamines' => [
                'Vitamine C',
                'Vitamine D',
                'Vitamine B',
                'Multivitamines',
            ],
            'Dermatologiques' => [
                'Corticostéroïdes',
                'Antifongiques topiques',
                'Antiacnéiques',
                'Hydratants',
            ],
            'Antiparasitaires' => [
                'Anthelminthiques',
                'Antiprotozoaires',
                'Ectoparasiticides',
            ],
        ];

        // Insérer les sous-catégories
        foreach ($sousCategories as $categoryName => $subCategories) {
            $category = Category::where('nom', $categoryName)->first();
            if ($category) {
                foreach ($subCategories as $subCategoryName) {
                    SousCategory::create([
                        'nom' => $subCategoryName,
                        'categorie_id' => $category->id,
                    ]);
                }
            }
        }
    }
}