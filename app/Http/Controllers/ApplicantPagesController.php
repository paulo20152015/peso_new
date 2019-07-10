<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Company_account;
use App\Job_post;
class ApplicantPagesController extends Controller
{
    //
    public function index(){
        return view('applicant.index');
    }
    public function job_post($id){
        Job_post::where('is_archive','=',0)->findOrFail($id);
        session()->put('applicant_single_view_post',$id);
        session()->save();
        return view('applicant.job_post');
    }
    public function companies(){
        return view('applicant.companies');
    }
    public function company($id){
        Company_account::where('is_archive','=',0)->findOrFail($id);
        session()->put('applicantView_company_id',$id);
        session()->save();
        return view('applicant.company');
    }
    public function account(){
        return view('applicant.account');
    }
    public function employment(){
        return view('applicant.employment_track');
    }
}
