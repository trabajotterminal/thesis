<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable{
    protected $fillable = ['name', 'username', 'lastname', 'password', 'email', 'type', 'group_id', 'school_id'];

    public function links(){
        return $this -> hasMany('\App\Link');
    }

    public function glances(){
        return $this -> belongsToMany('\App\Glance');
    }

    public function marks(){
        return $this -> hasMany('\App\Mark');
    }

    protected $hidden = [
        'password', 'remember_token',
    ];
}
