<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Truckle extends Model
{
    protected $table = 'truckles';
    protected $fillable = ['ar_name' , 'en_name' , 'hi_name',];

    public function users()
    {
        return $this->hasMany('App\User' , 'truckle_id');
    }
    
}
