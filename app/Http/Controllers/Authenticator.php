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
            $profile_picture = "data:image/png;base64,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";
            $user = new User([
                'username' => $request -> username,
                'password' => $request -> register_password,
                'email' => $request -> register_email,
                'profile_picture' => $profile_picture,
            ]);
            $user -> save();
            $student  = new Student(['user_id' => $user -> id]);
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
