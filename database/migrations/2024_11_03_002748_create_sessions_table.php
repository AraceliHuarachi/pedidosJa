<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionsTable extends Migration
{
   /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sessions', function (Blueprint $table) {
            $table->id(); // Crea la columna 'id' como clave primaria
            $table->string('session_id')->unique(); // Cambia 'id' a 'session_id'
            $table->text('payload');
            $table->integer('last_activity');
            $table->unsignedBigInteger('user_id')->nullable(); // Añade la columna 'user_id'
            $table->string('ip_address')->nullable(); // Añade 'ip_address' si es necesario
            $table->string('user_agent')->nullable(); // Añade 'user_agent' si es necesario
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sessions');
    }
};
