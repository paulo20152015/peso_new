<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Job_post;
use Illuminate\Support\Facades\Auth;
use App\Applicant_account;
class CompanyPagesController extends Controller
{
    //
    public function index(){
        $id = Auth::guard('company')->user()->id;
        $job_post = Job_post::where('company_account_id','=',$id)->whereHas('job_applications',function($q){
            $q->where('status','=','pending');
        })->get();
        return view('company.index',['pendings'=>$job_post]);
    }
    public function account_setting(){
        return view('company.account');
    }
    public function job_posts(){
        return view('company.job_posts');
    }
    public function job_post($id,Request $request){
        $post = Job_post::findOrFail($id);
        if(Auth::guard('company')->user()->id != $post->company_account_id){
            abort(403,'Unauthorized.');
        }
        session()->put('post_id',$id);
        session()->save();
        return view('company.job_post');
    }
    public function applicant($id){
        Applicant_account::findOrFail($id);
        //saving applicant session view
        session()->put('companyView_applicant_id',$id);
        session()->save();
        return view('company.applicant');
    }
    
    public function post_archive(){
        return view('company.post_archives');
    }

    public function employment(){
        
        return view('company.employment');
    }
}
