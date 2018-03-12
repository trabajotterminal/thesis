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

        $ESCOM = new School(['name' => 'ESCOM']);
        $ESCOM -> save();

        $group = new Group(['name' => 'Sin Asignar', 'school_id' => $school -> id]);
        $group -> save();

        $clubDeAlgoritmia = new Group(['name' => 'Club de Algoritmia', 'school_id' => $ESCOM -> id]);
        $clubDeAlgoritmia -> save();

        $me    = new User([
            'name' => 'Jair',
            'username' => 'rjairsaid',
            'lastname'  => 'Hernandez',
            'password'  => '123',
            'email'     => 'rjairsaid@gmail.com',
            'type'      => 'alumno',
            'group_id'  => $clubDeAlgoritmia -> id,
            'school_id' => $ESCOM -> id,
        ]);
        $me -> save();

        $bolitas    = new User([
            'name' => 'Esteban',
            'username' => 'bolitas',
            'lastname'  => 'Morales',
            'password'  => '123',
            'email'     => 'bolitas@gmail.com',
            'type'      => 'alumno',
            'group_id'  => $clubDeAlgoritmia -> id,
            'school_id' => $ESCOM -> id,
        ]);
        $bolitas -> save();

        $fili    = new User([
            'name' => 'Filiberto',
            'username' => 'fili',
            'lastname'  => 'Fuentes',
            'password'  => '123',
            'email'     => 'fili@gmail.com',
            'type'      => 'alumno',
            'group_id'  => $clubDeAlgoritmia -> id,
            'school_id' => $ESCOM -> id,
        ]);
        $fili -> save();

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
