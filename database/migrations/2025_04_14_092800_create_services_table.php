<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('telephone');
            $table->string('email')->unique();
            $table->longtext('profile_illustratif');
            $table->float('prix_rdv');
            $table->time('heure_ouverture');
            $table->time('heure_fermeture');
            $table->time('duree_moy_rdv');
            $table->boolean('sous_rdv');
            $table->softDeletes();
            $table->timestamps();

            // clé étrangère d'utilisateur, mais de l'admin
            $table->foreignId('admin_id')->constrained('admins');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
