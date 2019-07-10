<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job_application extends Model
{
    //is_approved value 1=default;2=not qualified;3:approved;
    //status pending,approved,not qualified
    protected $fillable = ['job_post_id','applicant_account_id','is_approved','status'];
    public function applicant_account(){
        return $this->belongsTo('App\Applicant_account');
    }
    public function job_post(){
        return $this->belongsTo('App\Job_post');
    }
}
