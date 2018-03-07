<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class User extends Controller{
    public function profile(){
        return view('user_profile');
    }
}
