<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employment_track extends Model
{
    //
    protected $fillable = ['company_detail_id','applicant_account_id','start_date',
                            'position','status',
                            ];
    public function company_detail(){
        return $this->belongsTo('App\Company_detail');
    }
    public function applicant_account(){
        return $this->belongsTo('App\Applicant_account');
    }
}
