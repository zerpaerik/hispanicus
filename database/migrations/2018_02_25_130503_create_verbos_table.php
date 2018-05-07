<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVerbosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('verbos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('infinitivo');
            $table->integer('tipo_verbo_id')->unsigned()->index();
            $table->foreign('tipo_verbo_id')->references('id')->on('tipo_verbos');
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
        Schema::dropIfExists('verbos');
    }
}
