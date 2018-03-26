<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reference extends Model{
    protected $fillable = ['type', 'pending_route', 'approved_route', 'needs_approval', 'is_approval_pending', 'topic_id', 'category_id'];

    public function marks(){
        return $this -> hasMany('\App\Mark');
    }
}
