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
        Schema::create('produit_classes', function (Blueprint $table) {
            
            $table->timestamps();
            $table->foreignId('classe_id')->constrained('classes');
            $table->foreignId('pharmaceutical_product_id')->constrained('pharmaceutical_products');
            $table->primary(['classe_id','pharmaceutical_product_id']);
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produit_classes');
    }
};
