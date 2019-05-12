<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company_file extends Model
{
    //
    protected $fillable = ['company_detail_id','name','type','location'];
    public function company_detail(){
        return $this->belongsTo('App\Company_detail');
    }
}
