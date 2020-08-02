<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Services\ConfigService;
use App\Services\GoodsClassService;
use Illuminate\Http\Request;

class CommonController extends Controller
{
    public function common(GoodsClassService $goods_class_service,ConfigService $config_service){
        $data['classes'] = $goods_class_service->get_goods_classes()['data']; // 商品分类
        $data['common'] = $config_service->get_format_config();
        return $this->success($data);
    }
}
