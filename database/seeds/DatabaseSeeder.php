<?php

use Illuminate\Database\Seeder;
use \App\School;
use \App\Group;
use \App\User;
use \App\Student;
use \App\Creator;
use \App\Admin;
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

        $clubDeAlgoritmiaEntrevistas = new Group(['name' => 'Club de Algoritmia Entrevistas', 'school_id' => $ESCOM -> id]);
        $clubDeAlgoritmiaEntrevistas -> save();

        $user    = new User(['username' => 'jairsaidds', 'password' => '123', 'email' => 'jairsaidds@gmail.com']);
        $user       -> save();
        $student    = new Student(['user_id' => $user -> id, 'school_id' => $ESCOM -> id, 'group_id' => $clubDeAlgoritmiaEntrevistas -> id]);
        $student    -> save();
        $user    = new User(['username' => 'creator', 'password' => '123', 'email' => 'creator@gmail.com']);
        $user       -> save();
        $creator    = new Creator(['user_id' => $user -> id]);
        $creator    -> save();
        $user    = new User(['username' => 'admin', 'password' => '123', 'email' => 'admin@gmail.com']);
        $user       -> save();
        $admin      = new Admin(['user_id' => $user -> id]);
        $admin      -> save();
        $user    = new User(['username' => 'secondcreator', 'password' => '123', 'email' => 'secondcreator@gmail.com']);
        $user       -> save();
        $creator    = new Creator(['user_id' => $user -> id]);
        $creator    -> save();
    }
}
