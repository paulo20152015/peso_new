<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Applicant_account;
use App\Personal_data;
use App\Applicant_file;
use App\Applicant_specialization;
use App\Specialization;
use App\Job_post;
use App\Job_specialization;
use App\Company_file;
use App\Job_application;
use App\Employment_track;
use App\Company_rating;
use App\Applicant_rating;
use App\Reset_code;
class ApplicantLogicController extends Controller
{
    //
    public function register(Request $request){
        $validated  = $request->validate([
            'first_name'=>'required|min:1|max:40|alpha',
            'middle_name'=>'nullable|min:1|max:40|alpha',
            'last_name'=>'required|min:1|max:40|alpha',
            'email'=>'required|email|min:8|max:70',
            'number'=>'required|digits:11',
            'civil_status'=>'required|in:Married,Widowed,Separated,Divorced,Single',
            'date_of_birth'=>'required|date|after:1950-01-01|before:2002-01-01',
            'username'=>'required|min:8|max:30|unique:applicant_accounts,username',
            'password'=>'required|min:8|max:30|confirmed',
            'gender'=>'required|in:0,1',
            ]);
        $Applicant_account = new Applicant_account();
        $Applicant_account->username = $validated['username'];
        $Applicant_account->password = Hash::make($validated['password']);
        $Applicant_account->save();
        
        $Personal_data = new Personal_data();
        $Personal_data->fname = ucfirst($validated['first_name']) ;
        $Personal_data->mname = ucfirst($validated['middle_name']);
        $Personal_data->lname = ucfirst($validated['last_name']);
        $Personal_data->date_of_birth = $validated['date_of_birth'];
        $Personal_data->email = $validated['email'];
        $Personal_data->gender = $validated['gender'];
        $Personal_data->contact_num = $validated['number'];
        $Personal_data->civil_status = $validated['civil_status'];
        $Personal_data->applicant_account_id = $Applicant_account->id;
        $Personal_data->save();
        return 'Registered Successfully';
    }
    // /applicant/login
    public function login(Request $request){
        $validated = $request->validate([
            'username'=>'required',
            'password'=>'required'
        ]);
        $remember = $request->remember;
        $applicant = Applicant_account::where([['username','=',$validated['username']]])->get();
        $count = $applicant->count();
        if($count >= 1){
            if (Auth::guard('applicant')->attempt(['username'=>$request->username,'password'=>$request->password,'is_archive'=>0],$remember)) {
                // Authentication passed...
                $id = Auth::guard('applicant')->user()->id;
                $appli = Applicant_account::findOrFail($id);
                $appli->is_log = 1;
                $appli->last_log = now();
                $appli->save();
                
                return ['success'=>1,'redirect'=>'/applicant'];
            }else{
                return ['success'=>2,'redirect'=>''];
            }
        }else{
            return ['success'=>3,'redirect'=>''];
        }
    }
    public function logout(){
        if( Auth::guard('applicant')->check()){
            $id = Auth::guard('applicant')->user()->id;
            $compa = Applicant_account::findOrFail($id);
            $compa->is_log = 0;
            $compa->save();
            Auth::guard('applicant')->logout();
        }else{
            Auth::guard('applicant')->logout();
        }
        return redirect('/');
    }
    //this is for generating code
    public function resetCode(Request $request){
        $username = $request->username;
        $number = $request->number;
        $applicant = Applicant_account::where([['username','=',$username]])->get();
        if($applicant->count() > 0){
            //account does exist
            $applicant = Applicant_account::where([['username','=',$username]])->firstOrFail();
            if($number == $applicant->personal_data->contact_num){
                //"number is equal"
                //generate the code
                $code = generateRandomString(5);//
                //if has existing reset code mass update to invalid
                $Reset_codes = Reset_code::where([['ref_id','=',$applicant->id],['user_type','=','applicant']])->get();
                if($Reset_codes->count() > 0){
                Reset_code::where([
                    ['ref_id','=',$applicant->id],
                    ['user_type','=','applicant']
                    ])
                ->update([
                    'status'=>'invalid'
                ]);//mass update to invalid
                }
                //create reset code entry
                $Reset_code = new Reset_code();
                $Reset_code->ref_id = $applicant->id;
                $Reset_code->user_type = 'applicant';
                $Reset_code->status = 'valid';
                $Reset_code->code = $code;
                $Reset_code->save();
                //send sms
                $message = "$username your password reset code is $code";
                return conver_sms(sms($number,$message));
            }else{
                return "Mobile number is incorrect!";
            }
        }else{
            return "Account does not exist";
        }
    }
    //for reseting password
    public function resetPass(Request $request){
        $username = $request->username;
        $number = $request->number;
        $code = $request->code;
        $applicant = Applicant_account::where([['username','=',$username]])->get();
        if($applicant->count() > 0){
            //account does exist
            $applicant = Applicant_account::where([['username','=',$username]])->firstOrFail();
            if($number == $applicant->personal_data->contact_num){
                //"number is equal"
                //check for code
                $Reset_codes = Reset_code::where([
                    ['ref_id','=',$applicant->id],
                    ['user_type','=','applicant'],
                    ['status','=','valid'],
                    ['code','=',$code]
                    ])
                    ->get();
                if($Reset_codes->count() > 0){
                    //generate the new password
                    $newpass = generateRandomString(10);//
                    $applicant->password = Hash::make($newpass);
                    $applicant->save();
                    //send sms
                    $message = "$username your new password is $newpass";
                    conver_sms(sms($number,$message));
                    Reset_code::where([
                        ['ref_id','=',$applicant->id],
                        ['user_type','=','applicant']
                        ])
                    ->update([
                        'status'=>'invalid'
                    ]);//mass update to invalid
                    return  'You will receive an SMS message containing your new password, Please wait for it';
                }else{
                    return "Invalid code";
                }
            }else{
                return "Mobile number is incorrect!";
            }
        }else{
            return "Account does not exist";
        }
    }
    public function profileData(){
        $id = Auth::guard('applicant')->user()->id;
        $personal_data   = Personal_data::where([['applicant_account_id','=',$id]])->firstOrFail();
        $applicant_pic   = Applicant_file::where([['applicant_account_id','=',$id],['type','=','profile_pic']])->get();
        $profile_pic = 'na';
        if($applicant_pic->count() >=1){
            $applicant_pic = Applicant_file::where([['applicant_account_id','=',$id],['type','=','profile_pic']])->firstOrFail();
            $profile_pic = $applicant_pic->url_name;
        }
        $data = ['personal_data'=>$personal_data,'profile_pic'=>$profile_pic];
        return $data;
    }
    public function account_data(){
        $id = Auth::guard('applicant')->user()->id;
        $Applicant_account_data   = Applicant_account::findOrFail($id);
        $data = ['applicant_account_data'=>$Applicant_account_data];
        return $data;
    }
    public function updatePersonalData(Request $request){
        $validated  = $request->validate([
            'first_name'=>'required|min:1|max:40|alpha',
            'middle_name'=>'nullable|min:1|max:40|alpha',
            'last_name'=>'required|min:1|max:40|alpha',
            'email'=>'required|email|min:8|max:70',
            'number'=>'required|digits:11',
            'civil_status'=>'required|in:Married,Widowed,Separated,Divorced,Single',
            'date_of_birth'=>'required|date|after:1950-01-01|before:2002-01-01',
        ]);
        $id = Auth::guard('applicant')->user()->id;
        $personal_data = Personal_data::where([['applicant_account_id','=',$id]])->firstOrFail();
        $personal_data->fname = ucfirst($validated['first_name']) ;
        $personal_data->mname = ucfirst($validated['middle_name']);
        $personal_data->lname = ucfirst($validated['last_name']);
        $personal_data->date_of_birth = $validated['date_of_birth'];
        $personal_data->email = $validated['email'];
        $personal_data->contact_num = $validated['number'];
        $personal_data->civil_status = $validated['civil_status'];
        $personal_data->save();
    }
    public function updateResume(Request $request){
        $validated  = $request->validate([
            'resume'=>'required|min:20',
        ]);
        $id = Auth::guard('applicant')->user()->id;
        $personal_data = Personal_data::where([['applicant_account_id','=',$id]])->firstOrFail();
        $personal_data->resume = $validated['resume'];
        $personal_data->save();
    }
    public function uploadImage(Request $request){
        $request->validate([
            'filetoupload'=>'required|image|max:2000'
        ]);
        if($request->type == 'profile_pic'){
            $id = Auth::guard('applicant')->user()->id;
            $Applicant_file = Applicant_file::where([
                ['type','=',$request->type],
                ['applicant_account_id','=',$id]
                ])
            ->get();
            if($Applicant_file->count() <=0){
                //create
                $storage =  $request->filetoupload->store('public/applicant_image');
                $store =  Storage::url($storage);
                $profile = new Applicant_file();
                $profile->url_name = $store;
                $profile->type = $request->type;
                $profile->main_location = $storage;
                $profile->applicant_account_id = $id;
                if($profile->save()){
                    return $message = 'uploaded successfully';
                }else{
                    return $message = 'Failed to upload';
                }
            }else{
                //update
                $profile = Applicant_file::where([
                    ['type','=',$request->type],
                    ['applicant_account_id','=',$id]
                    ])
                ->firstOrFail();
                Storage::delete($profile->main_location);
                $storage =  $request->filetoupload->store('public/applicant_image');//do something with the file
                $store =  Storage::url($storage);//when displaying
                $profile->url_name = $store;
                $profile->type = $request->type;
                $profile->main_location = $storage;
                if($profile->save()){
                    return $message = 'Logo has been uploaded successfully';
                }else{
                    return $message = 'Failed to upload';
                }
            }

        }else{
            return $message = 'Failed to upload';
        }
    }
    public function changePass(Request $request){
        $id = Auth::guard('applicant')->user()->id;
        $applicant = Applicant_account::findOrFail($id);
        if(Hash::check($request->pass,$applicant->password)){
            $request->validate([
                'password'=>'required|min:8|max:30|confirmed'
                ]);
            $applicant->password = Hash::make($request->password);
            if($applicant->save()){
                return "New password is save successfully";
            }else{
                return "Failed to save new password";
            }
        }else{
            return "Current password is incorrect";
        }
    }
    public function uploadResume(Request $request){
        $request->validate([
            'filetoupload'=>'required|mimes:doc,docx|max:2000'
        ]);
        if($request->type == 'resume'){
            $id = Auth::guard('applicant')->user()->id;
            $Applicant_file = Applicant_file::where([
                ['type','=',$request->type],
                ['applicant_account_id','=',$id]
                ])
            ->get();
            if($Applicant_file->count() <=0){
                //create
                $storage =  $request->filetoupload->store('public/applicant_resume');
                $store =  Storage::url($storage);
                $profile = new Applicant_file();
                $profile->url_name = $store;
                $profile->type = $request->type;
                $profile->main_location = $storage;
                $profile->applicant_account_id = $id;
                if($profile->save()){
                    $docObj = docxtostring(substr($store,1));
                    $explodetext = preg_split('/\r\n|\r|\n/', $docObj);
                    $blank = '';
                    foreach($explodetext as $newtext){
                        $blank .= $newtext.'<br>';
                    }
                    $id = Auth::guard('applicant')->user()->id;
                    $personal_data = Personal_data::where([['applicant_account_id','=',$id]])->firstOrFail();
                    $personal_data->resume = $blank;
                    $personal_data->save();
                    $applicant = Applicant_account::findOrFail($id);
                    $applicant->is_resume = 1;
                    $applicant->save();
                    return $message = 'uploaded successfully';
                }else{
                    return $message = 'Failed to upload';
                }
            }else{
                //update
                $profile = Applicant_file::where([
                    ['type','=',$request->type],
                    ['applicant_account_id','=',$id]
                    ])
                ->firstOrFail();
                Storage::delete($profile->main_location);
                $storage =  $request->filetoupload->store('public/applicant_resume');//do something with the file
                $store =  Storage::url($storage);//when displaying
                $profile->url_name = $store;
                $profile->type = $request->type;
                $profile->main_location = $storage;
                if($profile->save()){
                    $docObj = docxtostring(substr($store,1));
                    $explodetext = preg_split('/\r\n|\r|\n/', $docObj);
                    $blank = '';
                    foreach($explodetext as $newtext){
                        $blank .= $newtext.'<br>';
                    }
                    $id = Auth::guard('applicant')->user()->id;
                    $personal_data = Personal_data::where([['applicant_account_id','=',$id]])->firstOrFail();
                    $personal_data->resume = $blank;
                    $personal_data->save();
                    $applicant = Applicant_account::findOrFail($id);
                    $applicant->is_resume = 1;
                    $applicant->save();
                    return $message = 'Logo has been uploaded successfully';
                }else{
                    return $message = 'Failed to upload';
                }
            }

        }else{
            return $message = 'Failed to upload';
        }
    }
    public function getSpecializationDoesnt(){
        $id = Auth::guard('applicant')->user()->id;
        $specialization = Specialization::whereDoesntHave('applicant_specializations',function($q) use($id){
            $q->where('applicant_account_id','=',$id);
        })->get();
        return $specialization;
    }
    public function getSpecializationHas(){
        $id = Auth::guard('applicant')->user()->id;
        $specialization = Applicant_specialization::with(['specialization'])->where('applicant_account_id','=',$id)->get();
        return $specialization;
    }
    public function desOrCreate(Request $request){
        
        $id = Auth::guard('applicant')->user()->id;
        $toadd =  collect($request->checkNot);
        $todelete = collect($request->check);
        $message='';
        if($request->action =='added'){
            
            $count = $toadd->each(function($item,$key) use($id){
                $applicant_specializations = Applicant_specialization::where([['applicant_account_id','=',$id],['specialization_id','=',$item]])->get();
                if($applicant_specializations->count() == 0){ 
                    Applicant_specialization::create([
                        'specialization_id'=>$item,
                        'applicant_account_id'=>$id
                    ]);
                    return $item;
                }
            });
            $message = $count->count()." specialization added";
            $applicant = Applicant_account::findOrFail($id);
            $applicant->is_specialization = 1;
            $applicant->save();
        }elseif($request->action =='removed'){
            $applicant_specializations = Applicant_specialization::where([['applicant_account_id','=',$id]])->get();     
            if($applicant_specializations->count() == $todelete->count()){
                $message = 'you must have atleast one specialization';
            }else{
                $result = Applicant_specialization::destroy($todelete);
                $message = 'Deleted successfully';
            }       
                
        }
        return $message;
    }
    public function getJobPost(){
        $id = Auth::guard('applicant')->user()->id;
        $post_id = session('applicant_single_view_post');
        $job_post = Job_post::findOrFail($post_id);//post details
        $company_details = Job_post::findOrFail($post_id)->company_account->company_detail; //company details
        $company_account = Job_post::findOrFail($post_id)->company_account;//company acccount
        $logo = Company_file::where([['type','=','company_logo'],['company_detail_id','=',$company_details->id]])->get();//logo
        $town = $job_post->town;$job_post->town->city;//town
        $specialization = Job_specialization::with(['specialization'])->where('job_post_id','=',$post_id)->get();//specialization
        $job_application = Job_application::where([['job_post_id','=',$post_id],['applicant_account_id','=',$id]])->get();
        return [
            'company_account'=>$company_account,
            'company_details'=>$company_details,
            'company_logo'=>$logo,
            'job_post'=>$job_post,
            'town'=>$town,
            'job_specializations'=>$specialization,
            'job_application'=>$job_application
        ];
    }
    public function createJobApplication($post_id){
        $company = Job_post::findOrFail($post_id)->company_account;
        $user = Auth::guard('applicant')->user()->personal_data;
        $id = Auth::guard('applicant')->user()->id;
        $employment = Employment_track::where([['applicant_account_id','=',$id],['status','=','ongoing']])->get();
        $jobapplication = Job_application::where([['job_post_id','=',$post_id],['applicant_account_id','=',$id]])->get();   
        if($employment->count() == 0){
            if($jobapplication->count() > 0 ){
                return ['message'=>"You already have an existing application to this post",'sms'=>''];
            }else{
                Job_application::create([
                    'job_post_id'=>$post_id,
                    'applicant_account_id'=>$id,
                    'status'=>'pending',
                ]);
                $message = "$user->fname, $user->fname has applied to your job post";
                $res = conver_sms_result(sms($company->number,$message));
                return ['message'=>"Successfully applied",'sms'=>'The company has been notified'];
            }
        }else{
                return ['message'=>"You are currently employed",'sms'=>''];
        }   
            
    }
    public function deleteJobApplication($post_id){
        $company = Job_post::findOrFail($post_id)->company_account;
        $user = Auth::guard('applicant')->user()->personal_data;
        $id = Auth::guard('applicant')->user()->id;
        $Job_application = Job_application::where([['job_post_id','=',$post_id],['applicant_account_id','=',$id]]);
        if($Job_application->delete() > 0){
            $message = "$user->fname, $user->fname has cancelled hisapplication to your job post";
            $res = conver_sms_result(sms($company->number,$message));
            return ['message'=>"Application has been cancelled",'sms'=>'The company has been notified'];
        }else{
            return ['message'=>"cancellation error",'sms'=>''];
        }
    }
    public function ifEmployed(){
        $id = Auth::guard('applicant')->user()->id;
        $employment = Employment_track::where([['applicant_account_id','=',$id],['status','=','ongoing']])->get();
        $count = $employment->count();
        if($count > 0){
            $Job_application = Job_application::where([['applicant_account_id','=',$id],['status','=','pending']])->delete();
        }
        return $count;
    }
    public function returnEmploymentRecord(){
        $id = Auth::guard('applicant')->user()->id;
        $employment = Employment_track::with(['company_detail'])->where([['applicant_account_id','=',$id]])
        ->orderByRaw('created_at ASC')
        ->get();
        return $employment;
    }
    public function getCompanyRatingsByApplicant($id){
        $applicant_id   = Auth::guard('applicant')->user()->id;
        $company_id     = $id;
        $companyRatingsByApplicant = Company_rating::where([['applicant_account_id','=',$applicant_id],['company_account_id','=',$company_id]])
        ->get();
        return $companyRatingsByApplicant;
    }
    public function rateCompany($id,Request $request){
        //rate applicant
        $applicant_id   = Auth::guard('applicant')->user()->id;
        $company_id     = $id;
        $request->validate([
            'rate'=>'required|integer|min:1|max:10',
            'comment'=>'required|min:5|max:150'
        ]);
        $rate =     Company_rating::create([
            'message'=>$request->comment,
            'rate'=>$request->rate,
            'applicant_account_id'=> $applicant_id,
            'company_account_id'=>$company_id
        ]);
        return "rated";
    }
    public function updateCompanyRating(Company_rating $id,Request $request){
        $request->validate([
            'rate'=>'required|integer|min:1|max:10',
            'comment'=>'required|min:5|max:150'
        ]);
        $id->rate = $request->rate;
        $id->message = $request->comment;
        $id->save();
    }
    //returning all of applicant ratings
    public function getApplicantRatings(){
        $applicant_id   = Auth::guard('applicant')->user()->id;
        $ratings = Applicant_rating::where([['applicant_account_id','=',$applicant_id]])
        ->join('company_details','company_details.company_account_id','=','applicant_ratings.company_account_id')
        ->select('applicant_ratings.*','company_details.name')
        ->get();
        return $ratings;
    }
    ///global/get-posts
    public function getApplicationPost(Request $request){
        $applicant_id   = Auth::guard('applicant')->user()->id;
        
        $order = collect($request->order);
        $orderRaw = '';
        $validCol = 'title created_at updated_at';
        if($order->count() != 0){
            $count = 0;
           foreach($order as $key=>$value):
            if(strpos($validCol,$value['collumn']) !== false && ($value['value'] == 1 || $value['value'] == 2)):
            $orderRaw .= $count == 0?'':',';
            $orderRaw .= $value['collumn'].' '.orderConvert($value['value']);
            $count++;
            endif;
           endforeach;
        }
        $finalOrder = $orderRaw ==''?'created_at DESC':$orderRaw;
        //{collumn:'specialization',comparison:'=',value:''},
          //      {collumn:'city',comparison:'=',value:''},
           //     {collumn:'town',comparison:'=',value:''},
        $specilization = $request->filter[0]['collumn'] =='specialization' &&  is_int($request->filter[0]['value']) && $request->filter[0]['value'] != 0? ['specialization_id','=',$request->filter[0]['value']]:'empty';
        $city = $request->filter[1]['collumn'] =='city' &&  is_int($request->filter[1]['value'])  && $request->filter[1]['value'] != 0? ['city_id','=',$request->filter[1]['value']]:'empty';
        $town = $request->filter[2]['collumn'] =='town' &&  is_int($request->filter[2]['value'])  && $request->filter[2]['value'] !=0? ['id','=',$request->filter[2]['value']]:'empty';
        $tosearch =[];
        //for keyword search
        $search = isset($request->search) && $request->search != ''? $request->search:'empty';
        $posts = Job_post::with(['job_applications'=>function($q){
                                    if(Auth::guard('applicant')->check() === true){
                                        $id = Auth::guard('applicant')->user()->id;
                                        return $q->where('applicant_account_id','=',$id);
                                    }
                                },
                                'company_account'=>function($query){//job applicant
                                    return $query->with(['company_detail'=>function($query){
                                        return $query->with(['company_files'=>function($q){
                                        $q->where('type','=','company_logo');
                                        }]);
                                }]);
                                },
                                'job_specializations'=>function($query){
                                return $query->with(['specialization']);
                                },
                                'town'=>function($query){
                                return $query->with(['city']);
                                }])
                            ->whereHas('job_applications',function($q) use($applicant_id){
                                $q->where('applicant_account_id','=',$applicant_id);
                            })
                            ->where(function($q) use($specilization,$city,$town,$tosearch){
                                if($specilization !== 'empty'){
                                    $q->whereHas('job_specializations',function($q) use($specilization){
                                        if($specilization !== 'empty'){
                                            $q->where([$specilization]);
                                        }
                                    });
                                }
                                $q->whereHas('town',function($q) use($city,$town,$tosearch){
                                    
                                    if($city !== 'empty'){
                                        array_push($tosearch,$city);
                                    }
                                    if($town !== 'empty'){
                                        array_push($tosearch,$town);
                                    }
                                    if($city !== 'empty' || $town !== 'empty'){
                                        $q->where($tosearch);
                                    }
                                });
                                
                            })
                            ->where(function($q) use($search){
                                if($search !== 'empty'){
                                    $title = ['collumn'=>'title','comparison'=>'like','value'=>"%$search%"];
                                    $full_address = ['collumn'=>'full_address','comparison'=>'like','value'=>"%$search%"];
                                    $work_experience = ['collumn'=>'work_experience','comparison'=>'like','value'=>"%$search%"];
                                    $job_description = ['collumn'=>'job_description','comparison'=>'like','value'=>"%$search%"];
                                    $job_requirements = ['collumn'=>'job_requirements','comparison'=>'like','value'=>"%$search%"];
                                    $q->where([$title])->orWhere([$full_address])->orWhere([$work_experience])->orWhere([$job_description])
                                    ->orWhere([$job_requirements]);
                                }
                            })
                            ->orderByRaw($finalOrder)
                            ->paginate(10);

        return $posts;
    }
}
