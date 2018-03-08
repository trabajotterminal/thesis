<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

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
                'name' => '',
                'usernam' => $request -> username,
                'lastname' => '',
                'password' => $request -> password,
                'email' => $request -> email,
                'type' => 'alumno',
                'school_id' => 1,
                'group_id'  => 1,
            ]);
            $user -> save();
            $result = User::where('email','=', $request -> email)->where('password', '=', $request -> password) -> get() -> first();
            session(['user' => $result -> id, 'user_type' => $result -> type, 'user_id' => $result -> id]);
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
            session(['user' => $result -> id, 'user_type' => $result -> type, 'user_id' => $result -> id]);
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
