<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonasGramaticalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personas_gramaticals', function (Blueprint $table) {
            $table->increments('id');
            $table->string('pronombre');
            $table->boolean('plural');
            $table->boolean('formal');
            $table->integer('persona_gramatical');
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
        Schema::dropIfExists('personas_gramaticals');
    }
}
