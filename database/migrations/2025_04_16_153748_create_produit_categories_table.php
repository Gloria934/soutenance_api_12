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
        Schema::create('produit_categories', function (Blueprint $table) {
            $table->foreignId('categorie_id')->constrained('categories');
            $table->foreignId('pharmaceutical_product_id')->constrained('pharmaceutical_products');
            $table->primary(['categorie_id','pharmaceutical_product_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produit_categories');
    }
};
