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
            $table->foreign('pessoa_id')->references('pessoa_id')->on('pessoas');
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
