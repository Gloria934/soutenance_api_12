<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ClasseMedicamentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $classes = [
            ['nom' => 'Antibiotiques'],
            ['nom' => 'Antihypertenseurs'],
            ['nom' => 'Antidépresseurs'],
            ['nom' => 'Anti-inflammatoires'],
            ['nom' => 'Antalgiques'],
            ['nom' => 'Antidiabétiques'],
            ['nom' => 'Antihistaminiques'],
            ['nom' => 'Bronchodilatateurs'],
            ['nom' => 'Anticoagulants'],
            ['nom' => 'Statines'],
        ];

        foreach ($classes as $classe) {
            DB::table('classes')->insert([
                'nom' => $classe['nom'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}