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
            $table->date('date_rdv')->nullable();
            $table->string('statut')
                ->default(StatutEnum::ENCOURS->value)
                ->comment('Statut du rendez-vous: ' . implode(', ', StatutEnum::values()));
            $table->softDeletes();
            $table->timestamps();
            $table->foreignId('patient_id')->constrained('users')->onDelete('cascade')->onUpdate('cascade');

            $table->foreignId('service_id')->constrained('services')->onDelete('cascade')->onUpdate('cascade');
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
