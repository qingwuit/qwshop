<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Services\GoodsService;
use Illuminate\Http\Request;

class SeckillController extends Controller
{
    public function index(GoodsService $goods_service){
        $rs = $goods_service->getHomeSeckillGoods();
        return $this->success($rs['data']);
    }
}
