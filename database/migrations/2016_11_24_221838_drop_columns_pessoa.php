<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumnsPessoa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pessoas', function ($table) {
            $table->dropColumn('telefone');
            $table->dropColumn('sexo');
            $table->dropColumn('data_nascimento');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pessoas', function ($table) {
            $table->string('telefone')->nullable();
            $table->char('sexo');
            $table->date('data_nascimento');
        });
    }
}
