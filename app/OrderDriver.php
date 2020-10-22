<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDriver extends Model
{

    protected $table='order_drivers';
    protected $fillable = [
        'driver_id', 'order_id', 'price', 'details_driver'
    ];

    public function driver()
    {
        return $this->belongsTo('App\User' , 'driver_id');
    }
    public function order(){
        return $this->belongsTo('App\Order' , 'order_id');
    }
    
}
