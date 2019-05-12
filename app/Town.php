<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Town extends Model
{
    //
    protected $fillable = ['town','city_id'];
    public function city(){
        return $this->belongsTo('App\City');
    }
    public function job_posts(){
        return $this->hasMany('App\Job_post');
    }
}
