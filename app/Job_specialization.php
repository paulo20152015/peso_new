<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job_specialization extends Model
{
    //
    protected $fillable = ['job_post_id','specialization_id'];

    public function specialization(){
        return $this->belongsTo('App\Specialization');
    }
    public function job_post(){
        return $this->belongsTo('App\Job_post');
    }
}
