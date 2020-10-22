<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    //
    protected $fillable=[
        'phone_number',
        'order_time',
        'commission',
        'order_count',
        'image',
        'bank_name' ,
        'account_number'
    ];
}
