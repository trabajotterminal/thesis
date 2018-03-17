<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mark extends Model{
    protected $fillable = ['try_number', 'points', 'user_id', 'student_id', 'group_id', 'school_id', 'topic_id', 'category_id'];
}
