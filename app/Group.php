<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model{
    protected $fillable = ['name', 'school_id'];

    public function users(){
        return $this -> hasMany('\App\User');
    }

}
