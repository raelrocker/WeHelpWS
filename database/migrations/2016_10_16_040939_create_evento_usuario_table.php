<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventoUsuarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evento_usuario', function (Blueprint $table) {
            $table->integer('evento_id')->unsigned();
            $table->integer('usuario_id')->unsigned();
            $table->foreign('evento_id')->references('id')->on('eventos');
            $table->foreign('usuario_id')->references('id')->on('usuarios');
            $table->primary(['evento_id', 'usuario_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('evento_usuario');
    }
}
