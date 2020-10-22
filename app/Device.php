<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $table = 'devices';
    public $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = ['device_token'];
    
}
