<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Specialization extends Model
{
    //
    protected $fillable = ['specialization'];
    public function job_specializations(){
        return $this->hasMany('App\Job_specialization');
    }
    public function applicant_specializations(){
        return $this->hasMany('App\Applicant_specialization');
    }
}
