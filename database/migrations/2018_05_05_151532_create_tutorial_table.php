<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTutorialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tutorials', function (Blueprint $table) {
            $table->increments('id');
            $table->text('tutorial')->nullable();
            $table->string('model')->nullable();
            $table->text('def')->nullable();
            $table->string('lang')->nullable();
            $table->string('region')->nullable();
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
        Schema::dropIfExists('tutorials');
    }
}
