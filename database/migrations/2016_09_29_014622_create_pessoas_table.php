<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePessoasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pessoas', function(Blueprint $table) {
            $table->increments('id');
            $table->string('nome', 50);
            $table->binary('foto')->nullable();
            $table->string('telefone')->nullable();
            $table->integer('ranking')->default(0);
            $table->boolean('moderador')->default(false);
            $table->char('sexo');
            $table->date('data_nascimento');
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
        Schema::dropIfExists('pessoas');
    }
}
