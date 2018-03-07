<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Glance extends Model
{
    protected $fillable = ['type', 'user_id', 'group_id', 'school_id', 'topic_id', 'category_id'];

    public function users(){
        return $this -> belongsToMany('\App\User');
    }
}
