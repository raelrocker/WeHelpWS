<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsOngs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ongs', function ($table) {
            $table->integer('numero')->nullable();
            $table->string('complemento')->nullable();
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
            $table->dropColumn('numero');
            $table->dropColumn('complemento');
        });
    }
}
