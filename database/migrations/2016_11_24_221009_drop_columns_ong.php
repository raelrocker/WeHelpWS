<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumnsOng extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ongs', function ($table) {
            $table->dropColumn('telefone');
            $table->dropColumn('nacionalidade');
            $table->dropColumn('numero');
            $table->dropColumn('complemento');
            $table->dropColumn('bairro');
            $table->dropColumn('responsavel_nome');
            $table->dropColumn('responsavel_cpf');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ongs', function ($table) {
            $table->string('telefone');
            $table->string('nacionalidade');
            $table->string('bairro');
            $table->integer('numero')->nullable();
            $table->string('complemento')->nullable();
            $table->string('responsavel_nome');
            $table->string('responsavel_cpf');
        });
    }
}
