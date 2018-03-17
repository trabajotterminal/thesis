<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Creator extends Model
{
    protected $fillable = ['user_id'];

    public function topics(){
        return $this -> hasMany('\App\Topic');
    }

    public function categories(){
        return $this -> hasMany('\App\Category');
    }
}
