<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company_address extends Model
{
    //
    protected $fillable =['company_detail_id'];
    public function company_detail(){
        return $this->belongsTo('App\Company_detail');
    }
}
