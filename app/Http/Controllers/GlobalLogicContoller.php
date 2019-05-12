<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Town;
use App\City;
use App\Specialization;
use App\Job_post;
use App\Company_detail;
use App\Company_account;
use Illuminate\Support\Facades\Auth;
use Mockery\Undefined;

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
        $posts = Job_post::with(['company_account'=>function($query){
                                return $query->with(['company_detail'=>function($query){
                                    return $query->with(['company_files'=>function($q){
                                        $q->where('type','=','company_logo');
                                    }]);
                                }]);
                            },'job_specializations'=>function($query){
                                return $query->with(['specialization']);
                            },'town'=>function($query){
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
}
