<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Job_post;
use Illuminate\Support\Facades\Auth;

class CompanyPagesController extends Controller
{
    //
    public function index(){
        return view('company.index');
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
    public function post_archive(){
        return view('company.post_archives');
    }
}
