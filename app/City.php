<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'cities';
    public $primaryKey = 'id';
    public  $timestamps = true;
    protected $fillable = [
        'ar_name' , 'en_name' , 'hi_name','country_id'
    ];
    public function country()
    {
        return $this->belongsTo('App\Country' , 'country_id');
    }
    // public function users()
    // {
    //     return $this->hasMany('App\User' , 'city_id');
    // }
    public function orders()
    {
        return $this->hasMany('App\Order' , 'city_id');
    }
}
