<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApplicantPagesController extends Controller
{
    //
    public function index(){
        return view('applicant.index');
    }
    public function account(){
        return view('applicant.account');
    }
}
