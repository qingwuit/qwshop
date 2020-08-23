<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Services\GoodsService;
use Illuminate\Http\Request;

class GoodsController extends Controller
{
    // 商品详情
    public function goods_info(GoodsService $goods_service,$id){
        $goods_info = $goods_service->getGoodsInfo($id);
        if($goods_info['status']){
            return $this->success($goods_info['data']);
        }
        return $this->error($goods_info['msg']);
        
    }

    // 搜索产品
    public function goods_search(GoodsService $goods_service){
        $info = $goods_service->goodsSearch();
        if(!$info['status']){
            return $this->error($info['msg']);
        }
        return $this->success($info['data']);
    }
}
