<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

use App\Enums\UniteVenteEnum;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pharmaceutical_products', function (Blueprint $table) {
            $table->id();
            $table->string('nom_produit');
            $table->float('prix');
            $table->text('description');
            $table->string('unite_vente')
                  ->comment('Unité de vente: ' . implode(', ', UniteVenteEnum::values()));
            $table->date('date_expiration');
            $table->softDeletes();
            $table->timestamps();
        });


        DB::statement(
            "ALTER TABLE pharmaceutical_products 
             ADD CONSTRAINT unite_vente_check 
             CHECK (unite_vente IN ('" . implode("','", UniteVenteEnum::values()) . "'))"
        );
    }


    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pharmaceutical_products');
    }
};
