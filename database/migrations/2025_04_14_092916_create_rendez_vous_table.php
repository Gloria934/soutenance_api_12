<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

use App\Enums\StatutEnum;


return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rendez_vous', function (Blueprint $table) {
            $table->id();
            $table->string('nom_visiteur')->nullable();
            $table->string('prenom_visiteur')->nullable();
            $table->string('numero_visiteur')->nullable();
            $table->dateTime('date_rdv')->nullable();
            $table->string('code_rendez_vous')->nullable();
            $table->string('statut')
                ->default(StatutEnum::ENATTENTE->value)
                ->comment('Statut du rendez-vous: ' . implode(', ', StatutEnum::values()));
            $table->softDeletes();
            $table->timestamps();
            $table->foreignId('patient_id')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('service_id')->nullable();
            $table->foreign('service_id')->references('id')->on('services')->nullonDelete()->onUpdate('cascade');
            $table->unsignedBigInteger('specialiste_id')->nullable();
            $table->foreign('specialiste_id')->references('id')->on('users')->nullonDelete()->onUpdate('cascade');
        });


        DB::statement(
            "ALTER TABLE rendez_vous 
             ADD CONSTRAINT statut_check 
             CHECK (statut IN ('" . implode("','", StatutEnum::values()) . "'))"
        );
    }




    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rendez-vous');
    }
};
