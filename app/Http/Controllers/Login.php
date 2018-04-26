<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Login extends Controller
{
    public function index(Request $request){
        $goRegister = false;
        if($request -> has('register')){
            $goRegister = true;
            return view('login', compact(['goRegister']));
        }else {
            return view('login', compact(['goRegister']));
        }
    }
}
