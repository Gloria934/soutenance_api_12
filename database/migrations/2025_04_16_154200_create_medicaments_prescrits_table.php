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
        Schema::create('medicaments_prescrits', function (Blueprint $table) {


            $table->integer('quantite');
            $table->boolean('statut')
                ->default(false) // 'non sélectionné' par défaut
                ->comment('false: non sélectionné, true: sélectionné');
            $table->string('posologie')->nullable();
            $table->string('duree')->nullable();
            $table->string('avis')->nullable();
            $table->boolean('substitution_autorisee')->default(false);
            $table->softDeletes();
            $table->timestamps();



            $table->foreignId('pharmaceutical_product_id')->constrained('pharmaceutical_products');
            $table->foreignId('ordonnance_id')->constrained('ordonnances');
            $table->primary(['pharmaceutical_product_id', 'ordonnance_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicaments_prescrits');
    }
};
