<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ['message', 'sender_id', 'recipient_id',  'type', 'topic_id', 'category_id', 'reference_id', 'additional_params'];

}
