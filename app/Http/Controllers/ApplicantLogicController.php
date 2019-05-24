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
}
