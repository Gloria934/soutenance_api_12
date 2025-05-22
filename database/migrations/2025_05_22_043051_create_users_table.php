<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('nom');
            $table->string('prenom');
            $table->string('telephone', 20)->unique(); // Ajout de la contrainte unique
            $table->string('code_patient', 40)->nullable();
            $table->string('genre');
            $table->date('date_naissance')->nullable();
            $table->string('id_personnel')->nullable();
            $table->integer('role_voulu')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};