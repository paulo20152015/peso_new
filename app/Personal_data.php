<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Personal_data extends Model
{
    //
    protected $fillable = ['fname','mname','lname','date_of_birth',
                            'email','gender','contact_num','civil_status',
                            'applicant_account_id'
                            ];
    public function applicant_account(){
        return $this->belongsTo('App\Applicants_account');
    }
}
