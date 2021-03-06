<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleSeed::class);
        $this->call(UserSeed::class);
        $this->call(PersonasGramaticalSeeder::class);
        $this->call(TipoVerboSeed::class);
        $this->call(TiempoVerbalSeeder::class);
        //$this->call(VerboSeed::class);

    }
}
