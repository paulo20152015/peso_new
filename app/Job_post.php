<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Job_post extends Model
{
    //
    protected $fillable = ['title','full_address','work_experience',
                            'job_description','job_requirements','is_archive',
                            'town_id','company_account_id',
                        ];
    public function company_account(){
        return $this->belongsTo('App\Company_account');
    }
    public function town(){
        return $this->belongsTo('App\Town');
    }
    public function job_specializations(){
        return $this->hasMany('App\Job_specialization');
    }
    public function job_applications(){
        return $this->hasMany('App\Job_application');
    }
}
