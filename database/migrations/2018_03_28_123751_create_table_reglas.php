<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableReglas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reglas', function (Blueprint $table) {
            $table->increments('id');
            $table->text('regla')->nullable();
            $table->string('lang');
            $table->string('region');
            $table->string('tiempo');
            $table->integer('verbo_id')->index()->unsigned();
            $table->foreign('verbo_id')->references('id')->on('verbos');
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
         Schema::dropIfExists('reglas');
    }
}
