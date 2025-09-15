<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LangueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $langues = [
            ['nom' => 'Mina'],
            ['nom' => 'Xwla'],
            ['nom' => 'Fon'],
            ['nom' => 'Français'],
            ['nom' => 'Adja'],
            ['nom' => 'Anglais'],
            ['nom' => 'Mahi'],
            ['nom' => 'Watchi'],
            ['nom' => 'Nago'],
            ['nom' => 'Yoruba'],
            ['nom' => 'Goun'],

        ];

        foreach ($langues as $langue) {
            DB::table('langues')->insert([
                'nom' => $langue['nom'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}