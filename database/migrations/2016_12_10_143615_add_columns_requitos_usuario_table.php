<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsRequitosUsuarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('requisito_usuario', function ($table) {
            $table->integer('quant');
            $table->string('un')->default('un');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('requisito_usuario', function ($table) {
            $table->dropColumn('quant');
            $table->dropColumn('un');
        });
    }
}
