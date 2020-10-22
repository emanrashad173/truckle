<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    public $timestamps = true;
    public $primaryKey = 'id';

    protected $fillable = ['ar_name' , 'en_name','hi_name','photo'];
    public function users()
    {
        return $this->hasMany('App\User' , 'category_id');
    }
  
}
