<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    //
    protected $fillable = ['city'];
    public function towns(){
        return $this->hasMany('App\Town');
    }
}
