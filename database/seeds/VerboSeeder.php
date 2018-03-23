<?php

use Illuminate\Database\Seeder;
use hispanicus\Verbo;

class VerboSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $verbo = Verbo::create([
            'nombre' => 'comer',
            'raiz' => 'com',
            'tipo_verbo_id' => 2,
        ]);
    }
}
