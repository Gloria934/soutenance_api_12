<?php

use App\Enums\UniteMesureEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;



return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pharmaceutical_products', function (Blueprint $table) {
            $table->id();
            $table->string('image_path');
            $table->string('nom_produit');
            $table->string('dosage');
            $table->float('prix');
            $table->integer('stock');
            $table->text('description')->nullable();
            $table->date('date_expiration')->nullable();
            $table->foreignId('dci_id')->constrained('dcis')->onUpdate('cascade');
            $table->foreignId('classe_id')->constrained('classes')->onUpdate('cascade');
            $table->foreignId('categorie_id')->constrained('categories')->onUpdate('cascade');
            $table->foreignId('sous_categorie_id')->constrained('sous_categories')->onUpdate('cascade');
            $table->foreignId('forme_id')->constrained('formes')->onUpdate('cascade');
            $table->softDeletes();
            $table->timestamps();



        });





    }




    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pharmaceutical_products');
    }
};
