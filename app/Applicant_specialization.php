<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Applicant_specialization extends Model
{
    //
    protected $fillable = ['applicant_account_id','specialization_id'];

    public function specialization(){
        return $this->belongsTo('App\Specialization');
    }
    public function applicant_account(){
        return $this->belongsTo('App\Applicant_account');
    }
}
