<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOngsTable extends Migration
{
    
    public function up()
    {
        Schema::create('Ongs', function(Blueprint $table) {
            $table->increments('id');
            $table->string('nome');
            $table->bigInteger('cnpj')->unique();
            $table->binary('foto');
            $table->bigInteger('telefone');
            $table->string('nacionalidade');
            $table->string('uf', 2);
            $table->string('cidade');
            $table->string('rua');
            $table->integer('numero')->nullable();
            $table->string('complemento')->nullable();
            $table->string('cep');
            $table->string('bairro');
            $table->integer('ranking');
            $table->string('responsavel_nome');
            $table->string('responsavel_cpf');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('Ongs');
    }
}
