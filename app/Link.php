<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Link extends Model{
    protected $fillable = ['url', 'parameters', 'user_id', 'student_id', 'group_id', 'school_id', 'topic_id', 'category_id'];
}
