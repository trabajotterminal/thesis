<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model{
    protected $fillable = ['user_id', 'creator_id', 'pending_name', 'approved_name', 'needs_approval', 'is_approval_pending'];

    public function topics(){
        return $this -> hasMany('\App\Topic');
    }

    public function notifications(){
        return $this->hasMany('\App\Notification');
    }
}
