<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $fillable = ['category_id' ,
    'size'                 , 
    'detials'              ,
    'arrival_time'         ,
    'arrival_date'         ,
    'travel_time'          ,
    'travel_date'          ,
    'code'                 ,
    'travel_longitude'     ,
    'travel_latitude'	   ,
    'travel_address'       ,
    'arrival_longitude'    ,
    'arrival_latitude'	   ,
    'arrival_address'      ,
    'user_id'              ,
    'state'                ,
    'city_id'              ,
    'notes'                ,
    'price'                ,
    'details_driver'       ,
    'driver_id'            ,
    'paid'                 ,
    'commission_price'     ,
    'total_price'          ,
    'pay_photo'            ,
];


//paid (paid by driver  0 -> not paid, 1 -> paid but not confirmed by admin , 2 ->confirmed by admin)

    public function user()
    {
        return $this->belongsTo('App\User' , 'user_id');
    }

    public function driver()
    {
        return $this->belongsTo('App\User' , 'driver_id');
    }

    public function category()
    {
        return $this->belongsTo('App\Category' , 'category_id');
    }
    public function city()
    {
        return $this->belongsTo('App\City' , 'city_id');
    }
    public function orders()
    {
        return $this->hasMany('App\OrderDriver' , 'order_id');
    }

   

    
    
}
