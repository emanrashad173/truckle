<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'countries';
    protected $fillable = ['ar_name' , 'en_name' , 'hi_name',];

    public function users()
    {
        return $this->hasMany('App\User' , 'country_id');
    }
    public function ads()
    {
        return $this->hasMany('App\Ad' , 'ad_id');
    }
}
