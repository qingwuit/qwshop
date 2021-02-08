<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Http\Resources\Home\ConfigResource\ConfigResource;
use App\Services\ConfigService;
use App\Services\GoodsBrandService;
use App\Services\GoodsClassService;
use Illuminate\Http\Request;

class CommonController extends Controller
{
    public function common(GoodsClassService $goods_class_service,GoodsBrandService $goods_brand_service,ConfigService $config_service){
        $data['classes'] = $goods_class_service->getGoodsClasses()['data']; // 商品分类
        $data['brands'] = $goods_brand_service->getBrands()['data']; // 商品分类

        // 可以使用 Resource 控制达到脱敏
        $data['common'] = ConfigResource::make($config_service->getFormatConfig())->resolve();
        return $this->success($data);
    }
}
