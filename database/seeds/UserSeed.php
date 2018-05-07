<?php

use Illuminate\Database\Seeder;
use hispanicus\User;

class UserSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'Jacobo',
            'email' => 'hispanicusverbos@gmail.com',
            'password' => bcrypt('password')
        ]);
        $user->assign('administrator');
    }
}
