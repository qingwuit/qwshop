<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Services\GoodsClassService;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    // 获取首页信息
    public function index(GoodsClassService $goods_class_service){
        return $this->success($goods_class_service->is_master());
    }
}
