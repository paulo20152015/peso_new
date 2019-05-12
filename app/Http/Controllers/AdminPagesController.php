<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminPagesController extends Controller
{
    //
 
    public function index(){
        return view('admin.index');
    }
    public function adminsPage(){
        return view('admin.admins');
    }
    public function companies(){
        return view('admin.companies');
    }
    public function company(){
        return view('admin.company');
    }
    public function applicants(){
        return view('admin.applicants');
    }
    public function account_settings(){
        return view('admin.account');
    }
    //
    
}
