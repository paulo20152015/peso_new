<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job_application extends Model
{
    //is_approved value 1=default;2=rejected;3:approved;
    protected $fillable = ['job_post_id','applicant_account_id','is_approved','status'];
    public function applicant_account(){
        return $this->belongsTo('App\Applicants_account');
    }
    public function job_post(){
        return $this->belongsTo('App\Job_post');
    }
}
