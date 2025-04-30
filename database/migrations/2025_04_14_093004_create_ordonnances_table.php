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
        Schema::create('ordonnances', function (Blueprint $table) {
            $table->id();
            $table->float('montant');
            $table->date('date_ordonnance');
            $table->softDeletes();
            $table->timestamps();
            // clé étrangère pour le pharmacien qui établit l'ordonnance
            $table->foreignId('pharmacien_id')->constrained('pharmaciens');
            //clé étrangère du patient pour qui on établit l'ordonnance
            $table->foreignId('patient_id')->constrained('patients');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordonnances');
    }
};
