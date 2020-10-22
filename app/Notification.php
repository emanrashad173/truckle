<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{

    protected $fillable = [
        'user_id', 'type', 'ar_message', 'ar_title','en_message', 'en_title','hi_message', 'hi_title','order_id'
    ];
}
