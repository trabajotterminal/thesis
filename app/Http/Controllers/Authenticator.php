<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class Authenticator extends Controller
{
    public function register(Request $request){
        $this -> validate($request, [
            'email'     => 'required|email|max:50',
            'password'  => 'required|max:30'
        ]);
        $user_exists = User::where('email', '=', $request -> email) -> first();
        if($user_exists){
            $request -> session() -> flash('user_exists', 'Existe una cuenta asociada con el email introducido.');
            return redirect('/login#secondItem');
        }else{
            $user = new User([
                'name' => '',
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
        $this -> validate($request, [
            'email'     => 'required|email|max:50',
            'password'  => 'required|max:30'
        ]);
        $result = User::where('email','=', $request -> email)->where('password', '=', $request -> password) -> get() -> first();
        if($result){
            session(['user' => $result -> id, 'user_type' => $result -> type, 'user_id' => $result -> id]);
            return redirect('/');
        }else{
            $request -> session() -> flash('wrong_credentials', 'Usuario y/o contraseña invalidos.');
            return redirect('/login#firstItem');
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
