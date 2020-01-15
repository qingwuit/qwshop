<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Area;
use App\Models\Store;
use App\Models\StoreClass;


class StoreJoinController extends BaseController
{
    public function join(Request $req,Area $area_model,Store $store_model,StoreClass $store_class_model){
        $user_info = auth()->user();
        $store_info = $store_model->where('user_id',$user_info['id'])->first();
        if($req->isMethod('get')){
            if(!empty($store_info)){
                $store_info = $store_info->toArray();
                $store_info['area_info'] = [$store_info['province_id'],$store_info['city_id'],$store_info['region_id']];
                $store_class = $store_class_model->where('store_id',$store_info['id'])->first(); // 申请商品分类
                $class_1 = explode(',',$store_class['class_1']);
                $class_2 = explode(',',$store_class['class_2']);
                $class_3 = explode(',',$store_class['class_3']);
                foreach($class_1 as $k=>$v){
                    $store_info['class_info'][$k] = [];
                    $store_info['class_info'][$k][0] = $v;
                    $store_info['class_info'][$k][1] = $class_2[$k];
                    $store_info['class_info'][$k][2] = $class_3[$k];
                }
                $resp['store_info'] = $store_info;
                return $this->success_msg('ok',$resp);
            }
            return $this->error_msg('非法入驻',[]);
        }

        try{
            // 开启事务
			DB::beginTransaction();
        
            
            $area_list = $area_model->whereIn('id',$req->area_info)->get();
            $data = [
                'province_id'               =>  $req->area_info[0],
                'city_id'                   =>  $req->area_info[1],
                'region_id'                 =>  $req->area_info[2],
                'area_info'                 =>  $area_list[0]['name'].' '.$area_list[1]['name'].' '.($area_list[2]['name']??''),
                'store_mobile'              =>  $req->store_mobile,
                'store_name'                =>  $req->store_name,
                'store_description'         =>  $req->store_description??'',
                'store_company_name'        =>  $req->store_company_name,
                'store_address'             =>  $req->store_address,
                'business_license'          =>  $req->business_license,
                'business_license_no'       =>  $req->business_license_no,
                'legal_person'              =>  $req->legal_person,
                'id_card'                   =>  $req->id_card,
                'id_card_no'                =>  $req->id_card_no,
                'emergency_contact'         =>  $req->emergency_contact,
                'emergency_contact_phone'   =>  $req->emergency_contact_phone,
                'user_id'                   =>  $user_info['id'],
                'nickname'                  =>  $user_info['nickname'],
                'store_verify'              =>  0,  // 申请状态 0 审核中 1 通过 2 失败
                'store_status'              =>  0,
                'add_time'                  =>  time(),
            ];
            

            if(empty($store_info)){
                $store_id = $store_model->insertGetId($data);
            }else{
                $store_id = $store_info['id'];
                $store_model->where('id',$store_id)->update($data);
                $store_class_model->where('store_id',$store_id)->delete();
            }

            // 选择的商品分类
            $class_list = $req->class_info;
            $class_data = [
                'class_1' =>[],
                'class_2' =>[],
                'class_3' =>[],
            ];
            foreach($class_list as $v){
                $class_data['class_1'][] = $v[0];
                $class_data['class_2'][] = $v[1];
                $class_data['class_3'][] = $v[2];
            }
            $rs = $store_class_model->insert(['store_id'=>$store_id,'class_1'=>implode(',',$class_data['class_1']),'class_2'=>implode(',',$class_data['class_2']),'class_3'=>implode(',',$class_data['class_3'])]);

            // 执行无错误则提交
            DB::commit();
            return $this->success_msg('申请入驻成功！',$rs);
        }catch(\Exception $e){
            DB::rollBack();
            return $this->error_msg($e->getMessage());
        }

        
    }

    public function is_store(){
        $user_info = auth()->user();
        $store_model = new Store;
        
        $store_info = $store_model->select('user_id','store_status','store_verify')->where('user_id',$user_info['id'])->first();

        if(empty($store_info)){
            return $this->error_msg('您还没有商家账号');
        }

        if($store_info['store_status'] != 1){
            return $this->error_msg('店铺已经关闭，请联系工作人员');
        }

        if($store_info['store_verify'] != 1){
            return $this->error_msg('店铺正在审核，或审核失败，请联系工作人员');
        }
        return $this->success_msg('ok',$store_info['user_id']);
    }
}
