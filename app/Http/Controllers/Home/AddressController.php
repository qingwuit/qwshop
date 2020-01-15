<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Area;

class AddressController extends BaseController
{
    public function index(Request $req,Address $address_model){
        $user_info = auth()->user();
        $list = $address_model->where('user_id',$user_info['id'])->get();
        return $this->success_msg('ok',$list);
    }

    public function add(Request $req,Address $address_model,Area $area_model){
        $user_info = auth()->user();
        $area_info = $area_model->whereIn('id',$req->area_info)->get();
        $data = [
            'user_id'       =>  $user_info['id'],
            'province_id'   =>  $req->area_info[0],
            'city_id'       =>  $req->area_info[1],
            'region_id'     =>  $req->area_info[2],
            'lat'           =>  '',
            'lng'           =>  '',
            'area_info'     =>  $area_info[0]['name'].' '.$area_info[1]['name'].' '.$area_info[2]['name'],
            'address'       =>  $req->address,
            'receive_tel'   =>  $req->receive_tel,
            'receive_name'  =>  $req->receive_name,
            'is_default'    =>  empty($req->is_default)?0:1,
        ];
        $rs = $address_model->insert($data);
        return $this->success_msg('ok',$rs);

    }

    public function edit(Request $req,Address $address_model,Area $area_model,$id){
        $user_info = auth()->user();
        if(!$req->isMethod('post')){
            $info = $address_model->where(['id'=>$id,'user_id'=>$user_info['id']])->first();
            $info['area_info'] = [$info['province_id'],$info['city_id'],$info['region_id']];
            return $this->success_msg('ok',$info);
        }
        $area_info = $area_model->whereIn('id',$req->area_info)->get();
        $data = [
            'user_id'       =>  $user_info['id'],
            'province_id'   =>  $req->area_info[0],
            'city_id'       =>  $req->area_info[1],
            'region_id'     =>  $req->area_info[2],
            'lat'           =>  '',
            'lng'           =>  '',
            'area_info'     =>  $area_info[0]['name'].' '.$area_info[1]['name'].' '.$area_info[2]['name'],
            'address'       =>  $req->address,
            'receive_tel'   =>  $req->receive_tel,
            'receive_name'  =>  $req->receive_name,
            'is_default'    =>  empty($req->is_default)?0:1,
        ];
        $rs = $address_model->where('id',$id)->update($data);
        return $this->success_msg('ok',$rs);

    }

    // 设置默认地址
    public function set_default(Request $req,Address $address_model){
        $id = $req->id;
        $user_info = auth()->user();
        $address_model->where('user_id',$user_info['id'])->update(['is_default'=>0]);
        $rs = $address_model->where(['id'=>$id,'user_id'=>$user_info['id']])->update(['is_default'=>1]);
        return $this->success_msg('ok',$rs);
    }

    public function del(Request $req,Address $address_model){
        $id = $req->id;
		$ids = explode(',',$id);
		$address_model->destroy($ids);
    	return $this->success_msg();
    }
}
