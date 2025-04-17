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
        Schema::create('produit_sous_categories', function (Blueprint $table) {
            
            $table->foreignId('sous_categorie_id')->constrained('sous_categories');
            $table->foreignId('pharmaceutical_product_id')->constrained('pharmaceutical_products');
            $table->primary(['sous_categorie_id','pharmaceutical_product_id']);
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produit_sous_categories');
    }
};
