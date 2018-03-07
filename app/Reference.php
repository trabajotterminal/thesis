<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reference extends Model{
    protected $fillable = ['type', 'route', 'topic_id', 'category_id'];

    public function marks(){
        return $this -> hasMany('\App\Mark');
    }
}
