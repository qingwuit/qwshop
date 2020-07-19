<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Services\StoreService;
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
}
