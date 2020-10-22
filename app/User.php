<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',  'password',
        'email',
        'phone_number',
        'photo',
        'api_token',
        'active',
        'type',
        'last_name',
        'country_id',
        'truckle_id',
        'category_id',
        'identity',
        'license',
        'car_form',
        'transportation_card',
        'insurance',
        'verification_code',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    // public function city()
    // {
    //     return $this->belongsTo('App\City' , 'city_id');
    // }

    public function country()
    {
        return $this->belongsTo('App\Country' , 'country_id');
    }
    public function order()
    {
        return $this->hasMAny('App\Order' , 'user_id');
    }
    public function orders()
    {
        return $this->hasMAny('App\Order' , 'driver_id');
    }

    public function truckle()
    {
        return $this->belongsTo('App\Truckle' , 'truckle_id');
    }
    
    public function category()
    {
        return $this->belongsTo('App\Category' , 'category_id');
    }
   

    public function drivers()
    {
        return $this->hasMany('App\OrderDriver' , 'driver_id');
    }



    public function rate()
    {
      return $this->belongsToMany('App\User','rate','user_id','rated_id');
    }

    public function rates()
    {
      return $this->belongsToMany('App\User','rate','rated_id','user_id');
    }


}
