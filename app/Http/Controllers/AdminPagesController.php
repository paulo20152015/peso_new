<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Applicant_account;
use App\Company_account;
class AdminPagesController extends Controller
{
    //
 
    public function index(){
        $count = Applicant_account::where([['is_approved','=',0],['is_resume','=',1],['is_specialization','=',1]])->get()->count();
        return view('admin.index',['count'=>$count]);
    }
    public function adminsPage(){
        return view('admin.admins');
    }
    public function companies(){
        return view('admin.companies');
    }
    public function company($id){
        Company_account::findOrFail($id);
        session()->put('adminView_company_id',$id);
        session()->save();
        return view('admin.company');
    }
    public function applicants(){
        return view('admin.applicants');
    }
    
    public function applicant($id){
        Applicant_account::findOrFail($id);
        //saving applicant session view
        session()->put('adminView_applicant_id',$id);
        session()->save();
        return view('admin.applicant');
    }
    public function account_settings(){
        return view('admin.account');
    }
    public function archive(){
        return view('admin.archive');
    }
    public function jobPostCategories(){
        return view('admin.jobCategories');
    }
    //
    
}
