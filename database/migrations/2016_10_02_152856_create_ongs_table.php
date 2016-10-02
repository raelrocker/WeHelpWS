<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOngsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Ongs', function(Blueprint $table) {
            $table->increments('id');
            $table->string('nome');
            $table->bigInteger('cnpj')->unique();
            $table->binary('foto');
            $table->string('telefone');
            $table->string('nacionalidade');
            $table->string('uf', 2);
            $table->string('cidade');
            $table->string('rua');
            $table->integer('numero')->nullable();
            $table->string('complemento')->nullable();
            $table->string('cep');
            $table->string('bairro');
            $table->integer('ranking')->default(0);
            $table->string('responsavel_nome');
            $table->string('responsavel_cpf');
            $table->tinyInteger('ativo')->default(0);
            $table->float('lat', 10, 6);
            $table->float('lng', 10, 6);
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
        Schema::dropIfExists('ongs');
    }
}
