<?php
namespace App\Services;

use App\Models\Address;
use App\Models\Area;

class AddressService extends BaseService{

    // 获取地址信息
    public function getAddresses(){
        // 获取当前用户user_id
        $user_service = new UserService;
        $user_info = $user_service->getUserInfo();

        $address_model = new Address();
        $list = $address_model->where('user_id',$user_info['id'])->get();
        return $this->format($list);
    }

    // 添加地址
    public function add(){
        // 获取当前用户user_id
        $user_service = new UserService;
        $user_info = $user_service->getUserInfo();

        $address_model = new Address();

        // 如果is_Default == 1 // 将数据库中默认的地址清理
        if(request()->is_default == 1){
            $address_model->where('user_id',$user_info['id'])->where('is_default',1)->update(['is_default'=>0]);
        }

        $address_model->user_id = $user_info['id'];
        $address_model->receive_name = request()->receive_name??'';
        $address_model->receive_tel = request()->receive_tel??'';
        $address_model->address = request()->address??'';
        $address_model->province_id = request()->province_id;
        $address_model->city_id = request()->city_id;
        $address_model->region_id = request()->region_id;
        $address_model->is_default = request()->is_default??0;

        $area_model = new Area();
        $area_info = $area_model->whereIn('id',[request()->province_id,request()->city_id,request()->region_id])->get();

        $address_model->area_info = $area_info[0]['name'].' '.$area_info[1]['name'].' '.$area_info[2]['name'];
        
        $address_model->save();
        return $this->format([],__('base.success'));
    }

    // 编辑地址
    public function edit($id){
        // 获取当前用户user_id
        $user_service = new UserService;
        $user_info = $user_service->getUserInfo();

        $address_model = new Address();
        $address_info = $address_model->where('user_id',$user_info['id'])->where('id',$id)->first();
        if(empty($address_info)){
            return $this->format_error(__('users.address_error'));
        }
        
        $address_info->receive_name = request()->receive_name??'';
        $address_info->receive_tel = request()->receive_tel??'';
        $address_info->address = request()->address??'';
        $address_info->area_info = request()->area_info??'';
        $address_info->province_id = request()->province_id??'';
        $address_info->city_id = request()->city_id??'';
        $address_info->region_id = request()->region_id??'';
        $address_info->is_default = request()->is_default??'';
        $address_info->save();
        return $this->format([],__('base.success'));
    }

    // 获取默认地址
    public function getDefault(){
        // 获取当前用户user_id
        $user_service = new UserService;
        $user_info = $user_service->getUserInfo();

        $address_model = new Address();
        $address_info = $address_model->where('user_id',$user_info['id'])->where('is_default',1)->first();

        if(empty($address_info)){
            $address_info =  $address_model->where('user_id',$user_info['id'])->first();
        }

        return $this->format($address_info,__('base.success'));
    }

    // 设置默认地址
    public function setDefault($id){
        // 获取当前用户user_id
        $user_service = new UserService;
        $user_info = $user_service->getUserInfo();

        $address_model = new Address();
        $address_info = $address_model->where('user_id',$user_info['id'])->where('id',$id)->first();
        if(empty($address_info)){
            return $this->format_error(__('users.address_error'));
        }

        $address_model->where('user_id',$user_info['id'])->where('is_default',1)->update(['is_default'=>0]);
        $address_info = $address_model->where('id',$id)->first();
        $address_info->is_default = 1;
        $address_info->Save();

        return $this->format([],__('base.success'));
    }

}
