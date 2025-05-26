<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FormeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $formes = [
            ['nom' => 'Comprimé'],
            ['nom' => 'Gélule'],
            ['nom' => 'Sirop'],
            ['nom' => 'Injectable'],
            ['nom' => 'Pommade'],
            ['nom' => 'Suppositoire'],
            ['nom' => 'Crème'],
            ['nom' => 'Solution buvable'],
            ['nom' => 'Poudre'],
            ['nom' => 'Collyre'],
        ];

        foreach ($formes as $forme) {
            DB::table('formes')->insert([
                'nom' => $forme['nom'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}