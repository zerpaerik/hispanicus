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
	   		"indicativo",
	   		"subjuntivo",
	   		"imperativo",
	   		"infinitivo",
	   		"gerundio",
	   		"participio"
       ];

       foreach ($data as $key => $value) {
       		TipoDesinencia::create([
       			"modo" => $value,
       		]);
       }


    }
}
