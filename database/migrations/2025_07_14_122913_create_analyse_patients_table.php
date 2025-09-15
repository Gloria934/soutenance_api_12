<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\StatutEnum;


return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('analyse_patients', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('analyse_id')->nullable();
            $table->foreign('analyse_id')->references('id')->on('analyses')->nullonDelete()->onUpdate('cascade');
            $table->unsignedBigInteger('patient_id')->nullable();
            $table->foreign('patient_id')->references('id')->on('users')->nullonDelete()->onUpdate('cascade');
            $table->dateTime('date_rdv')->nullable();
            $table->string('code_analyse_patient')->nullable();
            $table->string('statut')
                ->default(StatutEnum::ENATTENTE->value)
                ->comment('Statut du rendez-vous: ' . implode(', ', StatutEnum::values()));
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analyse_patients');
    }
};
