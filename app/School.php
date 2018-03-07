<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $fillable = ['name'];

    public function groups(){
        return $this -> hasMany('\App\Group');
    }
}
