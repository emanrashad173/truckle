<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RateUser extends Model
{
    protected $table = 'rate_user';
    public $timestamps = true;
    public $primaryKey = 'id';

    protected $fillable = ['rated_id' , 'user_id','rating','order_id'];


}

//driver give rating to user