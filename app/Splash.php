<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Splash extends Model
{
    protected $table = 'splashes';
    public $timestamps = true;
    public $primaryKey = 'id';

    protected $fillable = ['photo' , 'title' , 'details'];
}
