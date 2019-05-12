<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::post('/admin/logout','AdminController@logout');
Route::post('/company/logout','CompanyLogicController@logout');
Route::post('/global/all-cities','GlobalLogicContoller@returnCities');
Route::post('/global/towns','GlobalLogicContoller@returnTowns');
Route::post('/global/specializations','GlobalLogicContoller@returnSpecializations');
Route::post('/global/get-posts','GlobalLogicContoller@getPosts');
//guest routes
Route::middleware(['guest'])->group(function(){
    Route::post('/admin/login','AdminController@login');
    Route::post('/company/login','CompanyLogicController@login');
    Route::get('/',function(){
        return view('index');
    })->name('index');
});

//admin routes
Route::middleware(['auth:admin'])->group(function(){
    Route::get('/admin','AdminPagesController@index');
    Route::get('/admin/Admins','AdminPagesController@adminsPage')->middleware('AdminAuthority');
    Route::patch('/admin/admins-archive/{id}','AdminController@archiveAdmin')->middleware('AdminAuthority');
    Route::post('/admin/store','AdminController@store')->middleware('AdminAuthority');
    Route::post('/admin/filter','AdminController@adminFilter')->middleware('AdminAuthority');
    Route::post('/admin/update/{id}','AdminController@update')->middleware('AdminAuthority');
    Route::post('/admin/data','AdminController@getAdminData');
    Route::post('/admin/updateself-pass','AdminController@updatePass');
    Route::post('/admin/updateself','AdminController@updateNumber');
    //companies routes
    Route::get('/admin/companies','AdminPagesController@companies');
    Route::get('/admin/company/{id}','AdminPagesController@company');
    Route::post('/admin/companies/data','AdminController@returnCompaniesData');
    Route::post('/admin/companies/create','AdminController@createCompany');
    Route::patch('/admin/company-archive/{id}','AdminController@archiveCompany');
    //applicants routes
    Route::get('/admin/applicants','AdminPagesController@applicants');

    Route::get('/admin/account','AdminPagesController@account_settings');
});


//user routes
Route::middleware(['auth:web'])->group(function(){
    Route::get('/welcome',function(){
        return view('index');
    });
});

//company routes
Route::middleware(['auth:company'])->group(function(){
    Route::get('/company','CompanyPagesController@index');
    Route::get('/company/job_posts','CompanyPagesController@job_posts');
    Route::get('/company/job_post/{id}','CompanyPagesController@job_post');
    Route::get('/company/post_archive','CompanyPagesController@post_archive');//post archive page
    Route::get('/company/account','CompanyPagesController@account_setting');
    Route::post('/company/data','CompanyLogicController@getCompanyData');
    Route::post('/company/all-data','CompanyLogicController@getAllcompanyData');
    Route::post('/company/updateself-pass','CompanyLogicController@updatePass');
    Route::post('/company/updateself','CompanyLogicController@updateNumber');
    Route::post('/company/upload-logo','CompanyLogicController@uploadlogo');
    Route::post('/company/save-profile','CompanyLogicController@saveProfile');
    Route::post('/company/job-posts','CompanyLogicController@returnJobPosts');
    Route::patch('/company/post-archive/{id}','CompanyLogicController@archivePost');
    Route::post('/company/post_create','CompanyLogicController@createPost');//creating a post
    Route::patch('/company/post_update','CompanyLogicController@updatePost');//creating a post
    Route::post('/company/getSinglePost','CompanyLogicController@getSinglePost');//viewing post single
    Route::post('/company/specializationDoesnt','CompanyLogicController@getSpecializationDoesnt');//specialization doesnt
    Route::post('/company/specializationHas','CompanyLogicController@getSpecializationHas');//specialization has
    Route::post('/company/desOrCreate','CompanyLogicController@desOrCreate');//create or delete specialization
});

