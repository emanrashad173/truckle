<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Track extends Model
{
    protected $table = 'track';
    public $timestamps = true;
    public $primaryKey = 'id';

    protected $fillable = ['driver_id' , 'order_id','latitude','longitude'];
  
}
