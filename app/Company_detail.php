<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company_detail extends Model
{
    //
    protected $fillable = ['name','email','company_account_id'];
    public function company_account(){
        return $this->belongsTo('App\Company_account');
    }
    public function company_address(){
        return $this->hasOne('App\Company_address');
    }
    public function company_files(){
        return $this->hasMany('App\Company_file');
    }
    public function employment_tracks(){
        return $this->hasMany('App\Employment_track');
    }
}
