<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Services\AreaService;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    // 获取格式化全国省市区地址
    public function areas(AreaService $area_service){
        $list = $area_service->getAreas()['data'];
        return $this->success($list);
    }
}
