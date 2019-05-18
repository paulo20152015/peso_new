<?php

namespace App;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Company_account extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $guard = 'company';
    
    protected $fillable = [
        'username', 'password','number','is_archive','is_log'
    ];

    /**
     * The attributes that should be hidden for arrays.
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    public function company_detail(){
        return $this->hasOne('App\Company_detail');
    }
    public function job_posts(){
        return $this->hasMany('App\Job_post');
    }
    public function company_ratings(){
        return $this->hasMany('App\Company_rating');
    }
    public function applicant_ratings(){
        return $this->hasMany('App\Applicant_rating');
    }
}
