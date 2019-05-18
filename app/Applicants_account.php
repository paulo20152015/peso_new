<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Applicants_account extends Authenticatable
{
    //
    protected $guard = 'applicant';

    protected $fillable = ['username','is_approved','is_log'];
    protected $hidden = [
        'password', 'remember_token',
    ];
    public function personal_data(){
        return $this->hasOne('App\Pesonal_data');
    }
    public function applicant_files(){
        return $this->hasMany('App\Applicant_file');
    }
    public function applicant_specializations(){
        return $this->hasMany('App\Applicant_specialization');
    }
    public function job_applications(){
        return $this->hasMany('App\Job_application');
    }
    public function employment_tracks(){
        return $this->hasMany('App\Employment_track');
    }
    public function company_ratings(){
        return $this->hasMany('App\Company_rating');
    }
    public function applicant_ratings(){
        return $this->hasMany('App\Applicant_rating');
    }
}
