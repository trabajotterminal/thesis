<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model{
    protected $fillable = ['user_id', 'creator_id', 'name'];

    public function topics(){
        return $this -> hasMany('\App\Topic');
    }
}
