<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDesinenciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('desinencias', function (Blueprint $table) {
            $table->increments('id');
            $table->string('desinencia');
            $table->string('tiempo');
            $table->string('cambia_neg')->nullable();
            $table->integer('pronombre_id')->unsigned()->index();
            $table->foreign('pronombre_id')->references('id')->on('personas_gramaticals');
            $table->integer('verbo_id')->unsigned()->index();
            $table->foreign('verbo_id')->references('id')->on('verbos');
            $table->integer('tipo_desinencia_id')->unsigned()->index();
            $table->foreign('tipo_desinencia_id')->references('id')->on('tipo_desinencias');
            $table->integer('region_id')->unsigned()->index();
            $table->foreign('region_id')->references('id')->on('config_regions');
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
        Schema::dropIfExists('desinenecias');
    }
}
