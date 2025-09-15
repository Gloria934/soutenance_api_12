<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Commands\UpgradeForTeams;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ordonnances', function (Blueprint $table) {
            $table->id();
            $table->float('montant_total');
            $table->float('montant_paye');
            $table->string('code_ordonnance')->nullable();
            $table->foreignId('patient_id')->constrained('users');
            $table->foreignId('service_id')->nullable()->constrained('services');
            $table->foreignId('specialiste_id')->nullable()->constrained('users');
            $table->softDeletes();
            $table->timestamps();

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
