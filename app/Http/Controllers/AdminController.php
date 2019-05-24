<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Admin;
use App\Company_account;
use App\Company_detail;
use App\Company_address;
use App\Company_file;
use App\Applicant_account;
use App\Personal_data;
use App\Applicant_file;
use Validator;
use App\Job_post;
use App\Job_application;

class AdminController extends Controller
{
    
    public function login(Request $request){
        $validated = $request->validate([
            'username'=>'required',
            'password'=>'required'
        ]);
        $remember = $request->remember;
        $company = Admin::where([['username','=',$validated['username']]])->get();
        $count = $company->count();
        if($count >= 1){
            if (Auth::guard('admin')->attempt(['username'=>$request->username,'password'=>$request->password,'is_archive'=>0],$remember)) {
                // Authentication passed...
                return ['success'=>1,'redirect'=>'/admin'];
            }else{
                return ['success'=>2,'redirect'=>''];
            }
        }else{
            return ['success'=>3,'redirect'=>''];
        }  
    }
    public function logout(){
        Auth::guard('admin')->logout();
        return redirect('/');
    }
    //remake it
    public function store(Request $request){
        $this->validate($request, [
            'username' => 'required|min:8|max:30|unique:admins,username',
            'password' => 'required|min:8|max:40',
            'number'=>'nullable|digits:11',
            'level'=>'required|integer|between:1,2'
        ]);
        
        $admin = new Admin();
        $admin->username = $request->username;
        $admin->password = Hash::make($request->password);
        $admin->number = $request->number;
        $admin->level = $request->level;
        if($admin->save()){
            return $request;
        }else{
            $message = "error/s occured";
            return $message;
        }
        
    }
    public function getAdminData(){
        $id = Auth::guard('admin')->user()->id;
        $data = Admin::findOrFail($id);
        return $data;
    }
    public function updateNumber(Request $request){
        $validated = $request->validate(['number'=>'required|digits:11']);
        $id = Auth::guard('admin')->user()->id;
        $admin = Admin::findOrFail($id);
        $admin->number = $request->number;
        if($admin->save()){
            return "Number is save successfully";
        }else{
            return "Failed to save new number";
        }
    }
    public function updatePass(Request $request){
        $id = Auth::guard('admin')->user()->id;
        $admin = Admin::findOrFail($id);
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
    public function update(Admin $id,Request $request){
        $this->validate($request, [
            'number'=>'nullable|digits:11',
            'level'=>'nullable|integer|between:1,2'
        ]);
        if($id->username != $request->username && $request->username != ''){
            $request->validate(['username'=>'required|min:8|max:30|unique:admins,username']);
            $id->username = $request->username;
        }
        if($request->password !== ''){
            $request->validate(['password' => 'nullable|min:8|max:40']);
            $id->password = Hash::make($request->password);
        }
        if($request->level == 1 || $request->level == 2){
            $id->level = $request->level;
        }
        $id->number = $request->number;
        if($id->save()){
            return $request;
        }else{
            $message = "error/s occured";
            return $message;
        }
        
    }
    //sort admin unfiniseh
    public function adminFilter(Request $request){ 
        $sorted = [];
        $orSorted = [];
        $orderRaw = '';
        $order = collect($request->order);
        $validCol = 'created_at username';
        if($request->archivestatus =='archive'){
            $arch = ['collumn'=>'is_archive','comparison'=>'!=','value'=>0];
        }else{
            $arch = ['collumn'=>'is_archive','comparison'=>'!=','value'=>1];
        }
        
        array_push($sorted,$arch);
        array_push($orSorted,$arch);
        //sorting with multiple collumn search
        if($request->search != ''){
            array_push($sorted,['collumn'=>'username','comparison'=>'like','value'=>"%$request->search%"]);
            array_push($orSorted,['collumn'=>'number','comparison'=>'like','value'=>"%$request->search%"]);
        }
        //sorting by category
        if($request->sortLevel != ''){
            array_push($sorted,$request->sortLevel);
            array_push($orSorted,$request->sortLevel);
        }
        //search order
        if($order->count() !=0){
            $count = 0;
           foreach($order as $key=>$value):
            if(strpos($validCol,$value['collumn']) !== false && ($value['value'] == 1 || $value['value'] == 2)):
            $orderRaw .= $count == 0?'':',';
            $orderRaw .= $value['collumn'].' '.orderConvert($value['value']);
            $count++;
            endif;
           endforeach;
        }
        $finalOrder = $orderRaw ==''?'username ASC,created_at DESC':$orderRaw;
        $result = Admin::where($sorted)->orWhere($orSorted)->orderByRaw($finalOrder)->paginate(10); 
        return $result;
    }
    public function archiveAdmin(Admin $id){
        $result = ['result'=>'failed'];
        if($id->update([
            'is_archive'=>$id->is_archive == 1?0:1
        ])){
            $result = ['result'=>'success'];
        }
        return $result;
    }
    public function archiveCompany(Company_account $id){
        $result = ['result'=>'failed'];
        if($id->update([
            'is_archive'=>$id->is_archive == 1?0:1
        ])){
            $result = ['result'=>'success'];
        }
        return $result;
    }
    public function archiveApplicant(Applicant_account $id){
        $result = ['result'=>'failed'];
        if($id->update([
            'is_archive'=>$id->is_archive == 1?0:1
        ])){
            $result = ['result'=>'success'];
        }
        return $result;
    }
    public function approvedReject(Applicant_account $id,Request $request){
        $result = ['result'=>'failed'];
        if($request->todo == 'approved'){
            if($id->update([
                'is_approved'=>1
            ])){
                $applicant_id = $id->id;
                $collect = $id->applicant_specializations;
                $message = "Your request for approval has been approved username: $id->username";
                $res = conver_sms_result(sms($id->personal_data->contact_num,$message));
                $result = ['result'=>'success','sms_status'=>$res];
                foreach ($collect as $value) {
                    $specialization_id = $value['specialization_id'];
                    $job_posts = Job_post::where([['is_archive','=',0]])->whereHas('job_specializations',function($q) use($specialization_id){
                        $q->where([['specialization_id','=',$specialization_id]]);
                    })->get();
                    foreach($job_posts as $value){
                        $job_post_id = $value['id'];
                        $jobapplicantions = Job_application::where([['job_post_id','=',$job_post_id],['applicant_account_id','=',$applicant_id]])->get();
                        if($jobapplicantions->count() == 0){
                            Job_application::create([
                                'job_post_id'=>$value['id'],
                                'applicant_account_id'=>$applicant_id,
                                'status'=>'pending',
                                'is_approved'=>1
                            ]);
                        }
                    }
                }
            }
        }elseif($request->todo == 'reject'){
            if($id->update([
                'is_approved'=>2
            ])){
                $message = "Your request for approval has been rejected username: $id->username";
                $res = conver_sms_result(sms($id->personal_data->contact_num,$message));
                $result = ['result'=>'success','sms_status'=>$res];
            }
        }elseif($request->todo == 'return to pending'){
            if($id->update([
                'is_approved'=>0
            ])){
                $message = "Your request for approval has been returned to pending state username: $id->username";
                $res = conver_sms_result(sms($id->personal_data->contact_num,$message));
                $result = ['result'=>'success','sms_status'=>$res];
            }
        }
        return $result;
    }
    //experimentation
    public function returnCompaniesData(Request $request){
        if($request->archivestatus =='archive'){
            $arch = ['collumn'=>'is_archive','comparison'=>'!=','value'=>0];
        }else{
            $arch = ['collumn'=>'is_archive','comparison'=>'!=','value'=>1];
        }
        $order = collect($request->order);
        $orderRaw = '';
        $validCol = 'created_at name updated_at last_log';
        if($order->count() !=0){
            $count = 0;
           foreach($order as $key=>$value):
            if(strpos($validCol,$value['collumn']) !== false && ($value['value'] == 1 || $value['value'] == 2)):
            $orderRaw .= $count == 0?'':',';
            $orderRaw .= $value['collumn'].' '.orderConvert($value['value']);
            $count++;
            endif;
           endforeach;
        }
        $finalOrder = $orderRaw ==''?'name ASC,created_at DESC':$orderRaw;
        if($request->search != ''){
            $keyword = $request->search;
            $nameSearch = ['collumn'=>'name','comparison'=>'like','value'=>"%$keyword%"];
            $emailSearch = ['collumn'=>'email','comparison'=>'like','value'=>"%$keyword%"];
            $contact_numberSearch = ['collumn'=>'contact_number','comparison'=>'like','value'=>"%$keyword%"]; 
            
            $usernameSearch = ['collumn'=>'username','comparison'=>'like','value'=>"%$keyword%"];

            $citySearch = ['collumn'=>'city','comparison'=>'like','value'=>"%$keyword%"];
            $townSearch = ['collumn'=>'town','comparison'=>'like','value'=>"%$keyword%"];
            $barangaySearch = ['collumn'=>'barangay','comparison'=>'like','value'=>"%$keyword%"];
            $streetSearch = ['collumn'=>'street','comparison'=>'like','value'=>"%$keyword%"];
            $val = Company_detail::with(['company_account','company_address','company_files'])
            ->whereHas('company_account',function($q) use($arch){
                $q->where([$arch]);
            })->where(function($query) use($citySearch,$townSearch,$barangaySearch,$streetSearch,$nameSearch,$emailSearch,$contact_numberSearch,$usernameSearch){
                $query->whereHas('company_account',function($q) use($usernameSearch){
                    $q->where([$usernameSearch]);
                })->orWhereHas('company_address',function($q) use($citySearch,$townSearch,$barangaySearch,$streetSearch){
                    $q->where([$citySearch])->orWhere([$townSearch])->orWhere([$barangaySearch])->orWhere([$streetSearch]);
                })->orWhere([$nameSearch])->orWhere([$emailSearch])->orWhere([$contact_numberSearch]);
            })->orderByRaw($finalOrder)->paginate(10);
            
            return $val;
        }else{
            $val = Company_detail::with(['company_account','company_address','company_files'])
            ->whereHas('company_account',function($q) use($arch){
                $q->where([$arch]);
            })->orderByRaw($finalOrder)->paginate(10);
            return $val;
        }
    }
    public function createCompany(Request $request){
        $validated = $request->validate([
            'username'=>'required|min:8|max:30|unique:company_accounts,username',
            'name'=>'required|max:40',
            'email'=>'required|email|min:8|max:40',
            'number'=>'required|digits:11',
            'password'=>'required|min:8|max:40',
        ]);
        $account = Company_account::create([
            'username'=>$validated['username'],
            'password'=>Hash::make($validated['password']),
            'number'=>$validated['number'],
        ]); 
        $detail = Company_detail::create([
            'name'=>$validated['name'],
            'email'=>$validated['email'],
            'contact_number'=>$validated['number'],
            'company_account_id'=>$account->id,
        ]);
        $add = Company_address::create([
            'company_detail_id'=>$detail->id,
        ]);
        $message = "Your account has been created! username: $request->username password:$request->password";
        $res = conver_sms_result(sms($validated['number'],$message));
        return $res;
    }
    public function getApplicants(Request $request){
        $arch = ['collumn'=>'is_archive','comparison'=>'!=','value'=>1];
        if(isset($request->arch) && $request->arch == 1){
            $arch = ['collumn'=>'is_archive','comparison'=>'=','value'=>1];
        }
        $is_approved = $request->is_approved;
        $order = collect($request->order);
        $orderRaw = '';
        $validCol = 'created_at username last_log';
        if($order->count() !=0){
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

        if($request->search != ''){
            $keyword = $request->search;
            $usernameSearch = ['collumn'=>'username','comparison'=>'like','value'=>"%$keyword%"];
            $fnameSearch = ['collumn'=>'fname','comparison'=>'like','value'=>"%$keyword%"];
            $mnameSearch = ['collumn'=>'mname','comparison'=>'like','value'=>"%$keyword%"];
            $lnameSearch = ['collumn'=>'lname','comparison'=>'like','value'=>"%$keyword%"];
            $emailSearch = ['collumn'=>'email','comparison'=>'like','value'=>"%$keyword%"];
            $val = Applicant_account::with(['applicant_files'=>function($q){
                $q->where('type','=','profile_pic');
            },'personal_data'])
            ->where([$arch,['is_approved','=',$is_approved]])->where(function($query) use($usernameSearch,$fnameSearch,$mnameSearch,$lnameSearch,$emailSearch){
                $query->where([$usernameSearch])->orWhereHas('personal_data',function($q) use($fnameSearch,$mnameSearch,$lnameSearch,$emailSearch){
                    $q->where([$fnameSearch])->orWhere([$mnameSearch])->orWhere([$lnameSearch])->orWhere([$emailSearch]);
                });
            })->orderByRaw($finalOrder)->paginate(10);
            return $val;
        }else{
            $val = Applicant_account::with(['applicant_files'=>function($q){
                $q->where('type','=','profile_pic');
            },'personal_data'])
            ->where([$arch,['is_approved','=',$is_approved]])->orderByRaw($finalOrder)->paginate(10);
            return $val;
        }
    }
}
