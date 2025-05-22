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
            $table->string('nom_produit');
            $table->float('dosage');
            $table->string('unite_mesure')
                ->comment('Unité de mesure du dosage: ' . implode(', ', UniteMesureEnum::values()));
            $table->string('code');
            $table->string('nom_laboratoire')->nullable();
            $table->longtext('image_produit')->nullable();
            $table->float('prix');
            $table->text('description');
            $table->date('date_expiration');
            $table->softDeletes();
            $table->timestamps();

            $table->foreignId('stock_id')->constrained('stocks')->onDelete('restrict');
            // clé étrangère en lien avec le secrétariat médical


            $table->foreignId('dci_id')->constrained('dcis')->onUpdate('cascade');
            $table->foreignId('forme_id')->constrained('formes')->onUpdate('cascade');


        });

        DB::statement(
            "ALTER TABLE pharmaceutical_products 
             ADD CONSTRAINT unite_check 
             CHECK (unite_mesure IN ('" . implode("','", UniteMesureEnum::values()) . "'))"
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
