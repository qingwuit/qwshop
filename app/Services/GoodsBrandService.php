<?php
namespace App\Services;

use App\Models\GoodsBrand;

class GoodsBrandService extends BaseService{
    
    // 获取所有品牌
    public function getBrands(){
        $goods_brand_model = new GoodsBrand();
        $data = $goods_brand_model->select('id','name','thumb')->orderBy('id')->orderBy('is_sort')->get();
        return $this->format($data);
    }
    
}
