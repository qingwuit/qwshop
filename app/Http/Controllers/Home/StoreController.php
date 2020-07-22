<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Services\StoreService;
use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    // 根据用户ID获取店铺信息
    public function store_verify(StoreService $store_service){
        $info = $store_service->store_verify();
        if($info['status']){
            return $this->success($info['data']);
        }else{
            return $this->error($info['msg']);
        }
    }

    // 商家入驻接口\
    public function store_join(Request $request,Store $store_model,StoreService $store_service){
        if(!$request->isMethod('post')){
            $info = $store_service->get_auth_store_info();
            if(!$info['status']){
                return $this->error($info['msg']);
            }
            return $this->success($info['data']);
        }
        
        $store_verify = $store_service->store_verify();
        
        // 店铺是否存在
        if(!$store_verify['status']){
            $create_info = $store_service->create_store();
            return $create_info['status']?$this->success($create_info['data']):$this->error($create_info['msg']);
        }

        // 填写入驻资料
        if($store_verify['data']['store_verify'] == 1){
            $edit_info = $store_service->edit_store($store_verify['data']['id']);
            $store_verify_status = $store_service->edit_store_status($store_verify['data']['id'],['store_verify'=>2]);
            return ($edit_info['status'] && $store_verify_status['status'])?$this->success($edit_info['data']):$this->error($edit_info['msg']);
        }

        return $this->success([],__('base.success'));

    }
}
