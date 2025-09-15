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
        Schema::create('fournitures_patients', function (Blueprint $table) {

            $table->float('montant');
            $table->integer('quantite');
            $table->string('code_fourniture');

            $table->boolean('livre')
                ->default(false) // 'non sélectionné' par défaut
                ->comment('false: non livré, true: livré');
            $table->softDeletes();
            $table->timestamps();



            $table->foreignId('fourniture_id')->constrained('fournitures');
            $table->foreignId('patient_id')->constrained('users');
            // $table->primary(['pharmaceutical_product_id', 'ordonnance_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fournitures_patients');
    }
};
