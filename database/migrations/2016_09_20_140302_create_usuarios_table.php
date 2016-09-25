<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsuariosTable extends Migration
{
    
    public function up()
    {
        Schema::create('Usuarios', function(Blueprint $table) {
            //$table->increments('id');
            $table->string('email', 60);
            $table->primary('email');
            $table->string('senha');
            $table->integer('pessoa_id')->unsigned()->nullable();
            $table->foreign('pessoa_id')->references('id')->on('pessoas');
            $table->integer('ong_id')->unsigned()->nullable();
            $table->foreign('ong_id')->references('id')->on('ongs');
            // Schema declaration
            // Constraints declaration
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('Usuarios');
    }
}
