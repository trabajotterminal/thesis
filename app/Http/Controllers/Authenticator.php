<?php

namespace App\Http\Controllers;

use App\School;
use Illuminate\Http\Request;
use App\User;
use App\Student;
use Log;

class Authenticator extends Controller
{
    public function register(Request $request){
        $request -> session() -> flash('indexPage', 1);

        $customMessages = [
            'required'  => 'Campo vacio',
            'email'     => 'Introduce un email válido',
            'max'       => 'El campo es demasiado largo',
        ];

        $this -> validate($request, [
            'username'  => 'required|max:20',
            'register_email'     => 'required|email|max:50',
            'register_password'  => 'required|max:30'
        ], $customMessages);

        $user_email = User::where('email', '=', $request -> email) -> first();
        $user_name  = User::where('username', '=', $request -> username) -> first();
        if($user_email || $user_name){
            if($user_email)
                $request -> session() -> flash('user_email_exists', 'Existe una cuenta asociada con el email introducido.');
            if($user_name)
                $request -> session() -> flash('user_name_exists', 'Existe una cuenta asociada con el nombre de usuario introducido.');
            return redirect('/login');
        }else{
            $user = new User([
                'username' => $request -> username,
                'password' => $request -> register_password,
                'email' => $request -> register_email,
            ]);
            $user -> save();
            $student  = new Student(['user_id' => $user -> id,  'group_id' => 1, 'school_id' => 1]);
            $student -> save();
            session(['user' => $user -> username, 'user_type' => 'student', 'user_id' => $user-> id]);
            return redirect('/');
        }
    }

    public function login(Request $request){
        $request -> session() -> flash('indexPage', 0);
        $customMessages = [
            'required'  => 'Campo vacio',
            'email'     => 'Introduce un email válido',
            'max'       => 'El campo es demasiado largo',
        ];

        $this -> validate($request, [
            'email'     => 'required|email|max:50',
            'password'  => 'required|max:30'
        ], $customMessages);
        $result = User::where('email','=', $request -> email)->where('password', '=', $request -> password) -> get() -> first();
        if($result){
            $type = "";
            $isStudent  = User::where('id', '=', $result -> id) -> get() -> first() -> student;
            $isCreator  = User::where('id', '=', $result -> id) -> get() -> first() -> creator;
            $isAdmin    = User::where('id', '=', $result -> id) -> get() -> first() -> admin;
            if($isStudent)
                $type = 'student';
            if($isAdmin)
                $type = 'admin';
            if($isCreator)
                $type = 'creator';
            Log::debug($type);
            session(['user' => $result -> username, 'user_type' => $type, 'user_id' => $result -> id]);
            return redirect('/');
        }else{
            $request -> session() -> flash('wrong_credentials', 'Usuario y/o contraseña invalidos.');
            return redirect('/login');
        }
    }

    public function logout(){
        session() -> forget('user');
        session() -> forget('user_type');
        session() -> forget('user_id');
        session() -> flush();
        return redirect('/');
    }
}
