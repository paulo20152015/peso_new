<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Applicant_account extends Authenticatable
{
    use Notifiable;
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $guard = 'applicant';

    protected $fillable = [
        'username','is_approved','is_log','is_archive','is_resume','is_specialization'
    ];
    
    public function personal_data(){
        return $this->hasOne('App\Personal_data');
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
    protected $hidden = [
        'password', 'remember_token',
    ];
}
