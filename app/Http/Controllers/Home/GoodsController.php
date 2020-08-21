<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Services\GoodsService;
use Illuminate\Http\Request;

class GoodsController extends Controller
{
    public function goods_info(GoodsService $goods_service,$id){
        $goods_info = $goods_service->goods_info($id);
        if($goods_info['status']){
            return $this->success($goods_info['data']);
        }
        return $this->error($goods_info['msg']);
        
    }
}
