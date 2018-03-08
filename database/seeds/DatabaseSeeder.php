<?php

use Illuminate\Database\Seeder;
use \App\School;
use \App\Group;
use \App\User;
class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $school = new School(['name' => 'Sin Asignar']);
        $school -> save();
        $group = new Group(['name' => 'Sin Asignar', 'school_id' => $school -> id]);
        $group -> save();
        $me    = new User([
            'name' => 'Jair',
            'username' => 'rjairsaid',
            'lastname'  => 'Hernandez',
            'password'  => '123',
            'email'     => 'rjairsaid@gmail.com',
            'type'      => 'alumno',
            'group_id'  => $group -> id,
            'school_id' => $school -> id,
        ]);
        $me -> save();

        $admin    = new User([
            'name' => 'Jair',
            'username' => 'jairsaidds',
            'lastname'  => 'Hernandez',
            'password'  => '123',
            'email'     => 'jairsaidds@gmail.com',
            'type'      => 'administrador',
        ]);
        $admin -> save();
    }
}
