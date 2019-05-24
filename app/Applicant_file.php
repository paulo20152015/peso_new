<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Applicant_file extends Model
{
    //
    protected $fillable = ['url_name','type','main_location','applicant_account_id'];
    public function applicant_account(){
        return $this->belongsTo('App\Applicant_account');
    }
}
