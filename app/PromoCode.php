<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    protected $table = 'promo_codes';
    public $timestamps = true;
    public $primaryKey = 'id';

    protected $fillable = ['name', 'percentage'];
  
}
