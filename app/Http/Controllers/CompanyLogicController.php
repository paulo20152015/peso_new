<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Company_account;
use App\Company_detail;
use App\Company_file;
use App\Company_address;
use App\Job_post;
use App\Specialization;
use App\Job_specialization;
use App\Job_application;
use App\Applicant_account;
use App\Employment_track;
use App\Admin;
use App\Applicant_rating;
use App\Company_rating;
use App\Reset_code;
class CompanyLogicController extends Controller
{
    //
    public function login(Request $request){
        $validated = $request->validate([
            'username'=>'required',
            'password'=>'required'
        ]);
        $remember = $request->remember;
        $company = Company_account::where([['username','=',$validated['username']]])->get();
        $count = $company->count();
        if($count >= 1){
            if (Auth::guard('company')->attempt(['username'=>$request->username,'password'=>$request->password,'is_archive'=>0],$remember)) {
                // Authentication passed...
                $id = Auth::guard('company')->user()->id;
                $compa = Company_account::findOrFail($id);
                $compa->is_log = 1;
                $compa->save();
                $company_detail = Company_detail::where([['company_account_id','=',$id]])->firstOrFail();
                $company_detail->last_log = now();
                $company_detail->save();
                return ['success'=>1,'redirect'=>'/company'];
            }else{
                return ['success'=>2,'redirect'=>''];
            }
        }else{
            return ['success'=>3,'redirect'=>''];
        }  
    }
    public function logout(){
        if( Auth::guard('company')->check()){
            $id = Auth::guard('company')->user()->id;
            $compa = Company_account::findOrFail($id);
            $compa->is_log = 0;
            $compa->save();
            Auth::guard('company')->logout();
        }else{
            Auth::guard('company')->logout();
        }
        return redirect('/');
    }
    //this is for generating code
    public function resetCode(Request $request){
        $username = $request->username;
        $number = $request->number;
        $company = Company_account::where([['username','=',$username]])->get();
        if($company->count() > 0){
            //account does exist
            $company = Company_account::where([['username','=',$username]])->firstOrFail();
            if($number == $company->number){
                //"number is equal"
                //generate the code
                $code = generateRandomString(5);//
                //if has existing reset code mass update to invalid
                $Reset_codes = Reset_code::where([['ref_id','=',$company->id],['user_type','=','company']])->get();
                if($Reset_codes->count() > 0){
                Reset_code::where([
                    ['ref_id','=',$company->id],
                    ['user_type','=','company']
                    ])
                ->update([
                    'status'=>'invalid'
                ]);//mass update to invalid
                }
                //create reset code entry
                $Reset_code = new Reset_code();
                $Reset_code->ref_id = $company->id;
                $Reset_code->user_type = 'company';
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
        $company = Company_account::where([['username','=',$username]])->get();
        if($company->count() > 0){
            //account does exist
            $company = Company_account::where([['username','=',$username]])->firstOrFail();
            if($number == $company->number){
                //"number is equal"
                //check for code
                $Reset_codes = Reset_code::where([
                    ['ref_id','=',$company->id],
                    ['user_type','=','company'],
                    ['status','=','valid'],
                    ['code','=',$code]
                    ])
                    ->get();
                if($Reset_codes->count() > 0){
                    //generate the new password
                    $newpass = generateRandomString(10);//
                    $company->password = Hash::make($newpass);
                    $company->save();
                    //send sms
                    $message = "$username your new password is $newpass";
                    conver_sms(sms($number,$message));
                    Reset_code::where([
                        ['ref_id','=',$company->id],
                        ['user_type','=','company']
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
    public function getCompanyData(){
        $id = Auth::guard('company')->user()->id;
        $data = Company_account::findOrFail($id);
        return $data;
    }
    //getting all data in company settings '/company/all-data'
    public function getAllcompanyData(){
        $id = Auth::guard('company')->user()->id;
        $company_detail     = Company_detail::where([['company_account_id','=',$id]])->firstOrFail();
        $company_address    = Company_address::where([['company_detail_id','=',$company_detail->id]])->firstOrFail();
        $company_logo       = Company_file::where([['company_detail_id','=',$company_detail->id],['type','=','company_logo']])->get();
        $company_cover      = Company_file::where([['company_detail_id','=',$company_detail->id],['type','=','company_cover']])->get();
        $logo = 'na';
        $cover = 'na';
        if($company_logo->count() >=1){
            $company_logo       = Company_file::where([['company_detail_id','=',$company_detail->id],['type','=','company_logo']])->firstOrFail();
            $logo = $company_logo->name;
        }
        if($company_cover->count() >=1 ){
            $company_cover      = Company_file::where([['company_detail_id','=',$company_detail->id],['type','=','company_cover']])->firstOrFail();
            $cover = $company_cover->name;
        }
        $data = ['detail'=>$company_detail,'address'=>$company_address,'cover'=>$cover,'logo'=>$logo];
        return $data;
    }
    public function updateNumber(Request $request){
        $validated = $request->validate(['number'=>'required|digits:11']);
        $id = Auth::guard('company')->user()->id;
        $admin = Company_account::findOrFail($id);
        $admin->number = $request->number;
        if($admin->save()){
            return "Number is save successfully";
        }else{
            return "Failed to save new number";
        }
    }
    public function updatePass(Request $request){
        $id = Auth::guard('company')->user()->id;
        $admin = Company_account::findOrFail($id);
        if(Hash::check($request->pass,$admin->password)){
            $request->validate([
                'password'=>'required|min:8|max:30|confirmed'
                ]);
            $admin->password = Hash::make($request->password);
            if($admin->save()){
                return "New password is save successfully";
            }else{
                return "Failed to save new password";
            }
        }else{
            return "Current password is incorrect";
        }
    }
    public function uploadlogo(Request $request){
        //company_logo && company_cover
        //to modify
        $request->validate([
            'filetoupload'=>'required|image|max:2000'
        ]);
        if($request->type == 'company_logo' || $request->type == 'company_cover'){
            $id = Auth::guard('company')->user()->id;
            $company_detail = Company_detail::where([['company_account_id','=',$id]])->firstOrFail();
            $company_file = Company_file::where([
                ['type','=',$request->type],
                ['company_detail_id','=',$company_detail->id]
                ])
            ->get();
            if($company_file->count() <=0){
                //create
                $storage =  $request->filetoupload->store('public/company_image');
                $store =  Storage::url($storage);
                $logo = new Company_file();
                $logo->name = $store;
                $logo->type = $request->type;
                $logo->location = $storage;
                $logo->company_detail_id = $company_detail->id;
                if($logo->save()){
                    return $message = 'uploaded successfully';
                }else{
                    return $message = 'Failed to upload';
                }
            }else{
                //update
                $company_file = Company_file::where([
                    ['type','=',$request->type],
                    ['company_detail_id','=',$company_detail->id]
                    ])
                ->firstOrFail();
                Storage::delete($company_file->location);
                $storage =  $request->filetoupload->store('public/company_image');//do something with the file
                $store =  Storage::url($storage);//when displaying
                $company_file->name = $store;
                $company_file->type = $request->type;
                $company_file->location = $storage;
                $company_file->company_detail_id = $company_detail->id;
                if($company_file->save()){
                    return $message = 'Logo has been uploaded successfully';
                }else{
                    return $message = 'Failed to upload';
                }
            }

        }else{
            
        }
    }
    public function saveProfile(Request $request){
        $validated = $request->validate([
            'name'=>'required|max:40',
            'email'=>'required|email|min:8|max:40',
            'number'=>'nullable|digits:11',
            'landline'=>'nullable|min:2|max:50',
            'website'=>'nullable|url|max:150',
            'overview'=>'nullable|min:5',
            'size'=>'nullable|min:2|max:40',
            'city'=>'nullable|min:2|max:50',
            'town'=>'nullable|min:2|max:50',
            'baranggay'=>'nullable|min:2|max:50',
            'street'=>'nullable|min:2|max:50',
        ]);
        $id = Auth::guard('company')->user()->id;
        $company_detail     = Company_detail::where([['company_account_id','=',$id]])->firstOrFail();
        $company_detail->name = $validated['name'];
        $company_detail->email = $validated['email'];
        $company_detail->contact_number = $validated['number'];
        $company_detail->company_size = $validated['size'];
        $company_detail->land_line = $validated['landline'];
        $company_detail->overview = $validated['overview'];
        $company_detail->website = $validated['website'];
        $company_detail->save();
        $company_address    = Company_address::where([['company_detail_id','=',$company_detail->id]])->firstOrFail();
        $company_address->city = ucfirst($validated['city']);
        $company_address->town = ucfirst($validated['town']);
        $company_address->barangay = ucfirst($validated['baranggay']);
        $company_address->street = $validated['street'];
        $company_address->save();
        return $request;
    }
    public function archivePost(Job_post $id){
        $result = ['result'=>'failed'];
        if($id->update([
            'is_archive'=>$id->is_archive == 1?0:1
        ])){
            $result = ['result'=>'success'];
        }
        return $result;
    }
    ///company/post_create
    public function createPost(Request $request){
        $id = Auth::guard('company')->user()->id;
        $validated = $request->validate([
            'title'=>'required|min:10|max:120',
            'address'=>'required|min:10|max:120',
            'experience'=>'required|min:4|max:50',
            'town'=>'required|min:1|numeric',
            'job_description'=>'required|min:5',
            'job_requirements'=>'required|min:5'
        ]);
        $job_post = Job_post::create([
            'title'=>$validated['title'],
            'full_address'=>$validated['address'],
            'work_experience'=>$validated['experience'],
            'town_id'=>$validated['town'],
            'job_description'=>$validated['job_description'],
            'job_requirements'=>$validated['job_requirements'],
            'company_account_id'=>$id,
        ]);
        return $job_post;
    }
    public function updatePost(Request $request){
        $post_id = session('post_id');
        $post = Job_post::findOrFail($post_id);
        
        $validated = $request->validate([
            'title'=>'required|min:10|max:120',
            'address'=>'required|min:10|max:120',
            'experience'=>'required|min:4|max:50',
            'town'=>'nullable|min:1|numeric',
            'job_description'=>'required|min:5',
            'job_requirements'=>'required|min:5'
        ]);
        //bug
        $town = !($validated['town'] > 0) || $validated['town'] == 0?$post->town_id:$validated['town'];
        $post->update([
            'title'=>$validated['title'],
            'full_address'=>$validated['address'],
            'work_experience'=>$validated['experience'],
            'town_id'=>$town,
            'job_description'=>$validated['job_description'],
            'job_requirements'=>$validated['job_requirements'],
        ]);
        return $post;
    }
    public function getSinglePost(){
       $post_id = session('post_id');
       $post = Job_post::with(['job_specializations','town'=>function($q){
          $q->with('city'); 
       }])->findOrFail($post_id);
       return $post;
    }
    public function getSpecializationDoesnt(){
        $post_id = session('post_id');
        $specialization = Specialization::whereDoesntHave('job_specializations',function($q) use($post_id){
            $q->where('job_post_id','=',$post_id);
        })->get();
        return $specialization;
    }
    public function getSpecializationHas(){
        $post_id = session('post_id');
        $specialization = Job_specialization::with(['specialization'])->where('job_post_id','=',$post_id)->get();
        return $specialization;
    }
    public function desOrCreate(Request $request){
        $post_id = session('post_id');
        $toadd =  collect($request->checkNot);
        $todelete = collect($request->check);
        if($request->action =='added'){
            $result = $toadd->each(function($item,$key) use($post_id){
                Job_specialization::create([
                    'specialization_id'=>$item,
                    'job_post_id'=>$post_id
                ]);
                return $item;
            });
        }elseif($request->action =='removed'){
            
            $result = Job_specialization::destroy($todelete);
        }
        return $result;
    }
    public function job_post_applicants(Request $request){
        $applicant_status = $request->application_status;
        $post_id = session('post_id');
        $post = Job_post::findOrFail($post_id);
        $archive = $post->is_archive;
        $applicants = Job_application::with(['applicant_account'=>function($q){
                    $q->with([
                        'personal_data',
                        'applicant_files',
                        'applicant_specializations'=>function($q){$q->with(['specialization']);}
                        ]);
                    }])
                    ->where([['job_post_id','=',$post_id],['status','=',$applicant_status]])
                    ->whereDoesntHave('applicant_account',function($q){
                        $q->whereHas('employment_tracks',function($q){
                            $q->where('status','=','ongoing');
                        });
                    })
                    ->orderByRaw('created_at DESC')->paginate(10);
        return ['archive'=>$archive,'applicants'=>$applicants];
    }

    public function ApproveApplicant(Request $request){
        //check if it has ongoing job
        //
        $applicant = Applicant_account::findOrFail($request->id);
        $applicant_id = $applicant->id;
        $applicantionId = $request->applicantion;
        $post_id = session('post_id');
        $job_post = Job_post::findOrFail($post_id);
        $company = $job_post->company_account->company_detail->name;
        $Job_application = Job_application::where([
                            ['id','=',$applicantionId],
                            ['job_post_id','=',$post_id],
                            ['applicant_account_id','=',$applicant_id]
                            ])->firstOrFail();
        $employment = Employment_track::where([['applicant_account_id','=',$applicant_id],['status','=','ongoing']]);
        if($employment->count() > 0){
            $message = "this applicant is currently employed!";
            $sms = '';
        }else{
            //send sms message to applicant
            $Job_application->is_approved = 3;//approved
            $Job_application->status = 'approved';//approved
            $Job_application->save();
            $message = "Applicant has been approved!";
            $smsmes = "$company company has approved your application, please wait for the company to provide more details";
            $sms = conver_sms_result(sms($applicant->personal_data->contact_num,$smsmes));
        }
        return ['message'=>$message,'sms'=>$sms];
    }
    public function NotApproved(Request $request){
        //check if it has ongoing job
        //
        $applicant = Applicant_account::findOrFail($request->id);
        $applicant_id = $applicant->id;
        $applicantionId = $request->applicantion;
        $post_id = session('post_id');
        $job_post = Job_post::findOrFail($post_id);
        $company = $job_post->company_account->company_detail->name;
        $Job_application = Job_application::where([
                            ['id','=',$applicantionId],
                            ['job_post_id','=',$post_id],
                            ['applicant_account_id','=',$applicant_id]
                            ])->firstOrFail();
        $employment = Employment_track::where([['applicant_account_id','=',$applicant_id],['status','=','ongoing']]);
        if($employment->count() > 0){
            $message = "this applicant is currently employed!";
            $sms = '';
        }else{
            //send sms message to applicant
            $Job_application->is_approved = 2;//approved
            $Job_application->status = 'not qualified';//approved
            $Job_application->save();
            $message = "Applicant has been approved!";
            $smsmes = "$company company has declared your application as not qualified";
            $sms = conver_sms_result(sms($applicant->personal_data->contact_num,$smsmes));
        }
        return ['message'=>$message,'sms'=>$sms];
    }
    public function pending(Request $request){
        //check if it has ongoing job
        //
        $applicant = Applicant_account::findOrFail($request->id);
        $applicant_id = $applicant->id;
        $applicantionId = $request->applicantion;
        $post_id = session('post_id');
        $job_post = Job_post::findOrFail($post_id);
        $company = $job_post->company_account->company_detail->name;
        $Job_application = Job_application::where([
                            ['id','=',$applicantionId],
                            ['job_post_id','=',$post_id],
                            ['applicant_account_id','=',$applicant_id]
                            ])->firstOrFail();
        $employment = Employment_track::where([['applicant_account_id','=',$applicant_id],['status','=','ongoing']]);
        if($employment->count() > 0){
            $message = "this applicant is currently employed!";
            $sms = '';
        }else{
            //send sms message to applicant
            $Job_application->is_approved = 1;//approved
            $Job_application->status = 'pending';//approved
            $Job_application->save();
            $message = "Applicant is now back to pending!";
            $smsmes = "$company company has reconsidered your application";
            $sms = conver_sms_result(sms($applicant->personal_data->contact_num,$smsmes));
        }
        return ['message'=>$message,'sms'=>$sms];
    }
    //for applicant hiring
    public function hireApplicant(Applicant_account $id,Request $request){
        $personal_data = $id->personal_data;
        $validated = $request->validate([
            'position'=>'min:2|max:60|required',
            'start_date'=>'date'
        ]);
        //check if applicant is employed
        $employment = Employment_track::where([['applicant_account_id','=',$id->id],['status','=','ongoing']]);
        if($employment->count() > 0){
            $message = "this applicant is currently employed!";
            $sms = '';
        }else{
            //create employment
            $company_id      = Auth::guard('company')->user()->id;
            $company = Company_account::findOrFail($company_id);
            $start = $validated['start_date'];
            $position = $validated['position'];
            $employ  = Employment_track::create([
                'company_detail_id'=>$company_id,
                'applicant_account_id'=>$id->id,
                'start_date'=>$start,
                'position'=>$validated['position'],
                'status'=>'ongoing'
            ]);
            $Job_application = Job_application::where([['applicant_account_id','=',$id->id],['status','=','pending']])->delete();
            $message = "created!";
            
            //SEND SMS TO PESO AND APPLICANT
            $company_name = $company->company_detail->name;
            $applicant_name = $personal_data->fname." ".$personal_data->lname;
            // send to applicant
            $smsmes = "$company_name company hired you as $position to start on $start";
            $sms = conver_sms_result(sms($personal_data->contact_num,$smsmes));   
            //send to active admins 
            $admin = Admin::where([['is_archive','=',0]])->get();
            foreach ($admin as $key => $value) {
                $number =  $value['number'];
                $smsmes = "$company_name company hired $applicant_name as $position to start on $start";
                $smsadmin = conver_sms_result(sms($number,$smsmes)); 
                $sms.=$smsadmin;  
            }
        }   
        return ['message'=>$message,'sms'=>$sms];
    }

    public function returnEmployees(Request $request){
        //if has search and by status
        $employment_status = $request->employment_status;
        $search = $request->search;
        $company_id      = Auth::guard('company')->user()->id;
        $company = Company_account::findOrFail($company_id)->company_detail;
        /*
        $employees = Employment_track::where([['company_detail_id','=',$company->id],['status','=',$employment_status]])->with(['applicant_account'=>function($q){
            $q->with(['personal_data']);
        }])->get();
        */
        $employees = Employment_track::where([['company_detail_id','=',$company->id],['status','=',$employment_status]])
        ->where(function($q) use($search){
            if($search != ''){
                $q->whereHas('applicant_account',function($q) use($search){
                    $q->whereHas('personal_data',function($q) use($search){
                        $q->where('lname','like',"%$search%")->orderByRaw('lname ASC
                        ');
                    });
                });
            }
        })
        ->join('personal_datas','personal_datas.applicant_account_id','=','employment_tracks.applicant_account_id')
        ->select('employment_tracks.*','personal_datas.fname','personal_datas.mname','personal_datas.lname','personal_datas.contact_num')
        ->orderByRaw('personal_datas.lname ASC')
        ->get();
        return $employees;
    }
    //for deleting employment if its still ongoing
    public function deleteEmployment(Employment_track $id){
        if($id->status == 'ongoing'){
         $id->delete();
        }
        return "action has been granted";
    }
    public function endEmployment(Employment_track $id,Request $request){
        //trigger sms and auto apply applicant
       $startDate = $id->start_date;
       $endDate = $request->endDate;
       $request->validate([
          'endDate'=>"required|date|after:$startDate"
        ]);
       $id->end_date = $endDate;
       $id->status = 'ended';
       $save = $id->save();
       $employee_id = $id->applicant_account_id;
       $company_id = $id->company_detail_id;
       $employee = Applicant_account::findOrFail($employee_id);
       $company = Company_detail::findOrFail($company_id);
       $collect = $employee->applicant_specializations;
                $message = "Your job to $company->name company has ended, the system already applied you to jobs Appropriate to your specializations";
                $res = conver_sms_result(sms($employee->personal_data->contact_num,$message));
                $result = ['result'=>'success','sms_status'=>$res];
                foreach ($collect as $value) {
                    $specialization_id = $value['specialization_id'];
                    $job_posts = Job_post::where([['is_archive','=',0]])->whereHas('job_specializations',function($q) use($specialization_id){
                        $q->where([['specialization_id','=',$specialization_id]]);
                    })->get();
                    foreach($job_posts as $value){
                        $job_post_id = $value['id'];
                        $jobapplicantions = Job_application::where([['job_post_id','=',$job_post_id],['applicant_account_id','=',$employee_id]])->get();
                        if($jobapplicantions->count() == 0){
                            Job_application::create([
                                'job_post_id'=>$value['id'],
                                'applicant_account_id'=>$employee_id,
                                'status'=>'pending',
                                'is_approved'=>1
                            ]);
                        }
                    }
                }
    }
    public function rateEmployee($id,Request $request){
        //rate applicant
        $applicant_id   = $id;
        $company_id     = Auth::guard('company')->user()->id;
        $request->validate([
            'rate'=>'required|integer|min:1|max:10',
            'comment'=>'required|min:5|max:150'
        ]);
        $rate =     Applicant_rating::create([
            'message'=>$request->comment,
            'rate'=>$request->rate,
            'applicant_account_id'=> $applicant_id,
            'company_account_id'=>$company_id
        ]);
        return "rated";
    }
    public function getApplicantRatingsByCompany($id){
        $applicant_id   = $id;
        $company_id     = Auth::guard('company')->user()->id;
        $applicantRatingsBycompany = Applicant_rating::where([['applicant_account_id','=',$applicant_id],['company_account_id','=',$company_id]])
        ->get();
        return $applicantRatingsBycompany;
    }
    public function updateApplicantRating(Applicant_rating $id,Request $request){
        $request->validate([
            'rate'=>'required|integer|min:1|max:10',
            'comment'=>'required|min:5|max:150'
        ]);
        $id->rate = $request->rate;
        $id->message = $request->comment;
        $id->save();
    }
    //returning all of company ratings
    public function getCompanyRatings(){
        $company_id     = Auth::guard('company')->user()->id;
        $ratings = Company_rating::with(['applicant_account'])
        ->where([['company_account_id','=',$company_id]])
        ->get();
        return $ratings;
    }
}
