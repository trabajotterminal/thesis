<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Glance extends Model{
    protected $fillable = ['type', 'topic_id', 'category_id'];
    public function students(){
        return $this -> belongsToMany('\App\Student');
    }
}
