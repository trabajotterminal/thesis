<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable{
    protected $fillable = ['username', 'password', 'email'];

    public function student(){
            return $this -> hasOne('\App\Student');
    }

    public function admin(){
        return $this -> hasOne('\App\Admin');
    }

    public function creator(){
        return $this -> hasOne('\App\Creator');
    }

    public function notifications(){
        return $this -> hasMany('\App\Notification');
    }
    protected $hidden = [
        'password', 'remember_token',
    ];
}
