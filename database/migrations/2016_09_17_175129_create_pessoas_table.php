<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePessoasTable extends Migration
{
    
    public function up()
    {
        Schema::create('Pessoas', function(Blueprint $table) {
            $table->increments('pessoa_id');
            $table->string('nome', 50);
            $table->integer('cpf')->unique();
            $table->binary('foto');
            $table->bigInteger('telefone');
            $table->integer('ranking')->default(0);
            $table->boolean('moderador')->default(false);
            $table->char('sexo');
            $table->date('data_nascimento');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('Pessoas');
    }
}
