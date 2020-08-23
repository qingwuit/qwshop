<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Services\GoodsClassService;
use Illuminate\Http\Request;

class GoodsClassController extends Controller
{
    // 获取商品分类
    public function goods_classes(GoodsClassService $goods_class_service){
        $rs = $goods_class_service->getGoodsClasses();
        return $this->success($rs['data']);
    }
}
