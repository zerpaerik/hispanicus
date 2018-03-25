<?php

use Illuminate\Database\Seeder;
use hispanicus\TiempoVerbal;

class TiempoVerbalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $data = [
        "Presente de indicativo",
        "Pretérito perfecto simple",
        "Futuro de indicativo",
        "Pretérito imperfecto",
        "Condicional",
        "Presente de subjuntivo",
        "Pretérito imperfecto (1)",
        "Pretérito imperfecto (2)",
        "Futuro de subjuntivo",
        "Infinitivo presente ",
        "Infinitivo compuesto ",
        "Gerundio presente",
        "Gerundio compuesto",
        "Participio",
        "Imperativo "
       ];

       foreach ($data as $key => $value) {
       		TiempoVerbal::create([
       			"tiempo" => $value,
       		]);
       }


    }
}
