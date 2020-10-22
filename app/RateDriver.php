<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RateDriver extends Model
{
    protected $table = 'rate_driver';
    public $timestamps = true;
    public $primaryKey = 'id';

    protected $fillable = ['rated_id' , 'user_id','rating','order_id'];


}

//user give rating to driver