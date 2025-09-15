<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DciSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dcis = [
            ['nom' => 'Paracétamol'],
            ['nom' => 'Ibuprofène'],
            ['nom' => 'Amoxicilline'],
            ['nom' => 'Metformine'],
            ['nom' => 'Atorvastatine'],
            ['nom' => 'Oméprazole'],
            ['nom' => 'Amlodipine'],
            ['nom' => 'Acide acétylsalicylique'],
            ['nom' => 'Ciprofloxacine'],
            ['nom' => 'Lévothyroxine'],
        ];

        foreach ($dcis as $dci) {
            DB::table('dcis')->insert([
                'nom' => $dci['nom'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}