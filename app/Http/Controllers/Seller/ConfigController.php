<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Resources\Seller\StoreResource\StoreConfigResource;
use App\Models\Store;
use App\Services\UploadService;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
    public function show(){
        $store_info = $this->get_store();
        return $this->success(new StoreConfigResource($store_info));
    }

    public function update(Request $request){
        $store_model = Store::find($this->get_store(true));

        // 店铺基本信息
        if($request->edit_type == 'base'){
            if(isset($request->store_name)){
                $store_model->store_name = $request->store_name;
            }
            if(isset($request->store_logo)){
                $store_model->store_logo = $request->store_logo;
            }
            if(isset($request->store_description)){
                $store_model->store_description = $request->store_description;
            }
            if(isset($request->store_mobile)){
                $store_model->store_mobile = $request->store_mobile;
            }
            if(isset($request->after_sale_service)){
                $store_model->after_sale_service = $request->after_sale_service;
            }
        }

        // 店铺门面
        if($request->edit_type == 'face'){
            if(isset($request->store_face_image)){
                $store_model->store_face_image = $request->store_face_image;
            }
        }

        // 地图设置
        if($request->edit_type == 'map'){
            if(isset($request->province_id)){
                $store_model->province_id = $request->province_id;
            }
            if(isset($request->city_id)){
                $store_model->city_id = $request->city_id;
            }
            if(isset($request->region_id)){
                $store_model->region_id = $request->region_id;
            }
            if(isset($request->store_address)){
                $store_model->store_address = $request->store_address;
            }
            if(isset($request->area_info)){
                $store_model->area_info = $request->area_info;
            }
            if(isset($request->store_lat)){
                $store_model->store_lat = $request->store_lat;
            }
            if(isset($request->store_lng)){
                $store_model->store_lng = $request->store_lng;
            }
        }

        // pc幻灯片
        if($request->edit_type == 'pc_slide'){
            if(isset($request->store_slide)){
                $store_model->store_slide = empty($request->store_slide)?'':implode(',',$request->store_slide);
            }
        }

        // 手机幻灯片
        if($request->edit_type == 'mobile_slide'){
            if(isset($request->store_mobile_slide)){
                $store_model->store_mobile_slide = empty($request->store_mobile_slide)?'':implode(',',$request->store_mobile_slide);
            }
        }

        $store_model->save();
        return $this->success([],__('base.success'));
    }

    // 图片上传
    public function config_upload(Request $request){
        $name = $request->name;
        $upload_service = new UploadService;
        $store_info = $this->get_store(false,'user_id');
        switch($name){
            case 'store_logo':
                $rs = $upload_service->store_logo($store_info['user_id']);
            break;
            default:
                $rs = $upload_service->store_slide($store_info['user_id']);
            break;
        }

        if($rs['status']){
            return $this->success($rs['data'],$rs['msg']);
        }else{
            return $this->error($rs['msg']);
        }
    }
}
