<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRaizDesinenciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('desinencia_raizs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('desinencia_id')->unsigned()->index();
            $table->foreign('desinencia_id')->references('id')->on('desinencias');
            $table->integer('tiempo_verbal_id')->unsigned()->index();
            $table->foreign('tiempo_verbal_id')->references('id')->on('tiempo_verbals');
            $table->integer('forma_verbal_id')->unsigned()->index()->nullable();
            $table->foreign('forma_verbal_id')->references('id')->on('forma_verbals');
            $table->integer('pronombre_reflex_id')->unsigned()->index()->nullable();
            $table->foreign('pronombre_reflex_id')->references('id')->on('pronombre_reflexes');
            $table->string('negativo')->nullable();
            $table->integer('pronombre_id')->unsigned()->index()->nullable();
            $table->foreign('pronombre_id')->references('id')->on('personas_gramaticals');
            $table->integer('pronombre_formal_id')->unsigned()->index()->nullable();
            $table->foreign('pronombre_formal_id')->references('id')->on('personas_gramaticals');
            $table->integer('raiz_id')->unsigned()->index();
            $table->foreign('raiz_id')->references('id')->on('raizs');
            $table->integer('regla_id')->unsigned()->index()->nullable();
            $table->foreign('regla_id')->references('id')->on('reglas');
            $table->integer('verbo_auxiliar_id')->unsigned()->index()->nullable();
            $table->foreign('verbo_auxiliar_id')->references('id')->on('verbo_auxiliars');
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
        Schema::dropIfExists('desinencia_raizs');
    }
}
