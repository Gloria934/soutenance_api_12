<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [

            //Permissions liées à l'entité users
            'voir utilisateurs',
            'créer utilisateurs',
            'mettre à jour utilisateurs',
            'supprimer utilisateurs',
            'attribuer roles',
            'voir rôles',
            'exporter utilisateurs',

            
            'créer ordonnance',
            'gérer patients',
            'gérer pharmacie',
            'accéder aux statistiques',
            'view patients',
            
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }
    }
}




