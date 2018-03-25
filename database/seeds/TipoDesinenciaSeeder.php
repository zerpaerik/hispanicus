<?php

use Illuminate\Database\Seeder;
use hispanicus\TipoDesinencia;

class TipoDesinenciaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $data = [
        "Afirmativo informal",
        "Negativo informal",
        "Afirmativo reflexivo informal",
        "Negativo reflexivo informal",
        "Afirmativo formal",
        "Negativo formal",
        "Afirmativo reflexivo formal",
        "Negativo reflexivo formal"
       ];

       foreach ($data as $key => $value) {
       		TipoDesinencia::create([
       			"modo" => $value,
       		]);
       }


    }
}
