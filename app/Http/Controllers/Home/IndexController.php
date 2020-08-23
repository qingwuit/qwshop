<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Services\AdvService;
use App\Services\GoodsClassService;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    // 获取首页信息
    public function index(GoodsClassService $goods_class_service,AdvService $adv_service){
        $data['goods'] = $goods_class_service->is_master()['data']; // 获取商品首页信息
        $data['banner_bottom_adv'] = $adv_service->getAdvList('PC_幻灯片下广告')['data'];
        $data['banner'] = $adv_service->getAdvList('PC_首页幻灯片')['data'];
        return $this->success($data);
    }
}
