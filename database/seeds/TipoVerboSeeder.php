<?php

use Illuminate\Database\Seeder;
use hispanicus\TipoVerbo;

class TipoVerboSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tipos = ["regular", "regular (cambio ortografico)", "irregular"];
        foreach ($tipos as $key => $value) {
            $tipoVerbo = TipoVerbo::create([
            'nombre' => $value,
            ]);            
        }
    }
}
