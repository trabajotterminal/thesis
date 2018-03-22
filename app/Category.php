<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model{
    protected $fillable = ['user_id', 'creator_id', 'pending_name', 'name', 'has_been_approved'];

    public function topics(){
        return $this -> hasMany('\App\Topic');
    }

    public function notifications(){
        return $this->hasMany('\App\Notification');
    }
}
