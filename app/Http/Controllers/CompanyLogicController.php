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
}
