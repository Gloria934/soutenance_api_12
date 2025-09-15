<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSimpleNotificationsTable extends Migration
{
    public function up()
    {
        Schema::create('simple_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personnel_sante_id')->constrained('users')->onDelete('cascade');
            $table->string('type')->default('personnel_registration'); // Type de notification
            $table->string('status')->default('pending'); // pending, accepted, rejected
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('simple_notifications');
    }
}