<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = ['user_id', 'group_id', 'school_id'];

    public function links(){
        return $this -> hasMany('\App\Link');
    }

    public function glances(){
        return $this -> belongsToMany('\App\Glance');
    }

    public function marks(){
        return $this -> hasMany('\App\Mark');
    }
}
