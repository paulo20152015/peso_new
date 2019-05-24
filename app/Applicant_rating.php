<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Applicant_rating extends Model
{
    //
    protected $fillable = ['message','rate','applicant_account_id','company_account_id'];
    public function company_account(){
        return $this->belongsTo('App\Company_account');
    }
    public function applicant_account(){
        return $this->belongsTo('App\Applicant_account');
    }
}
