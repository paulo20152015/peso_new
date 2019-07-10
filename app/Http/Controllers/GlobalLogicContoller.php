<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Town;
use App\City;
use App\Specialization;
use App\Job_post;
use App\Company_detail;
use App\Company_account;
use App\Company_file;
use Illuminate\Support\Facades\Auth;
use Mockery\Undefined;
use App\Applicant_account;
use App\Personal_data;
use App\Applicant_file;
use App\Applicant_specialization;
use App\Applicant_rating;
use App\Employment_track;
use App\Company_rating;
class GlobalLogicContoller extends Controller
{
    //for request with no specification
    public function returnCities(Request $request){
        $city = City::all();
        return $city;
    }
    public function returnTowns(Request $request){
        $town = Town::Where([['city_id','=',$request->id]])->get();
        return $town;
    }
    public function returnSpecializations(Request $request){
        $Specializations = Specialization::all();
        return $Specializations;
    }
    ///global/get-posts
    public function getPosts(Request $request){
        $data = [['is_archive','=',0]];
        if(isset($request->archive_stat) && $request->archive_stat == 'yes'){
            $data = [['is_archive','=',1]];
        }
        //if company 
        if(Auth::guard('company')->check() === true){
            $id = Auth::guard('company')->user()->id;
            array_push($data,['company_account_id','=',$id]);
        }
        //if company 
        if(Auth::guard('admin')->check() === true){
            $id = session('adminView_company_id');
            array_push($data,['company_account_id','=',$id]);
        }
        if(Auth::guard('applicant')->check() === true){
            if(isset($request->company_post) && $request->company_post == 'yes'){
                $id = session('applicantView_company_id');
                array_push($data,['company_account_id','=',$id]);
            }
        }
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
                            ->where($data)
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
    public function returnResume(){
        //download resume
        if(Auth::guard('applicant')->check()){
            $id = Auth::guard('applicant')->user()->id;
        }else{
            $id = 0;
        }
        $data ='';
        $Applicant_file = Applicant_file::where([
            ['type','=','resume'],
            ['applicant_account_id','=',$id]
            ])
            ->get();
        if($Applicant_file->count() == 1){
            $resume = Applicant_file::where([
                ['type','=','resume'],
                ['applicant_account_id','=',$id]
                ])
            ->firstOrFail();    
            $data = $resume->url_name;
        }else{
            $data = 'no data';
        }
        return $data;
    }
    public function applicantViewData(){
        //check authority
        if(Auth::guard('admin')->check()){
            $id = Auth::guard('admin')->user()->id;
            $authority = 'admin';
            $applicant_id = session('adminView_applicant_id');
        }elseif(Auth::guard('company')->check()){
            $id = Auth::guard('company')->user()->id;
            $authority = 'company';
            $applicant_id = session('companyView_applicant_id');
        }else{
            $id = 0;
            $authority = 'none';
            $applicant_id = 0;
        }
        $applicant_account = Applicant_account::findOrFail($applicant_id);
        $personal_data = $applicant_account->personal_data;
        $resume = $applicant_account->applicant_files->where('type','=','resume');
        $profile_pic = $applicant_account->applicant_files->where('type','=','profile_pic');
        $specialization = Applicant_specialization::with(['specialization'])->where([['applicant_account_id','=',$applicant_id]])->get();
        $rating = Applicant_rating::with(['company_account'=>function($q){
            $q->with(['company_detail'=>function($q){
                $q->with(['company_files'=>function($q){
                    $q->where('type','=','company_logo');
                }]);
            }]);
        }])->where([['applicant_account_id','=',$applicant_id]])->get();
        $employment_track = Employment_track::with(['Company_detail'=>function($q){
            $q->with(['company_files'=>function($q){
                $q->where('type','=','company_logo');
            }]);
        }])->where([['applicant_account_id','=',$applicant_id]])->orderByRaw('created_at DESC')->get();
        
        return [
            'account_data'=>$applicant_account,
            'personal_data'=>$personal_data,
            'specialization'=>$specialization,
            'resume'=>$resume,
            'profile_pic'=>$profile_pic,
            'ratings'=>$rating,
            'employment_track'=>$employment_track,
            'authority'=>$authority
        ];
    }
    public function returnCompanyDetails(){
        //check authority
        if(Auth::guard('admin')->check()){
            $id = Auth::guard('admin')->user()->id;
            $authority = 'admin';
            $company_id = session('adminView_company_id');
        }elseif(Auth::guard('company')->check()){
            $id = Auth::guard('company')->user()->id;
            $authority = 'company';
            $company_id = 0;
        }elseif(Auth::guard('applicant')->check()){
            $id = Auth::guard('applicant')->user()->id;
            $authority = 'applicant';
            $company_id = session('applicantView_company_id');
        }
        else{
            $id = 0;
            $authority = 'none';
            $company_id = 0;
        }
        $company_account = Company_account::findOrFail($company_id);
        $detail_id = $company_account->company_detail->id;
        $company_detail = Company_detail::findOrFail($detail_id);
        $logo = Company_file::where([['type','=','company_logo'],['company_detail_id','=',$detail_id ]])->get();
        $cover = Company_file::where([['type','=','company_cover'],['company_detail_id','=',$detail_id ]])->get();
        $ratings = Company_rating::with(['applicant_account'])
        ->where([['company_account_id','=',$company_id]])
        ->get();
        return [
            'detail'=>$company_account->company_detail,
            'address'=>$company_detail->company_address,
            'logo'=>$logo,
            'cover'=>$cover,
            'authority'=>$authority,
            'ratings'=>$ratings
        ];
    }
    public function employment_record_company(Request $request){
        //if has search and by status
        $employment_status = $request->employment_status;
        $search = $request->search;
        $company_id = session('adminView_company_id');
        $company = Company_account::findOrFail($company_id)->company_detail;
        
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
}
