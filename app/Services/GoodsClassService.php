<?php
namespace App\Services;

use App\Models\GoodsClass;
use App\Traits\HelperTrait;
use Illuminate\Support\Facades\Cache;

class GoodsClassService extends BaseService{
    use HelperTrait;
    public $cache_name = 'goods_classes_cache';
    public function get_goods_classes(){
        $goods_class_model = new GoodsClass();
        $cache_name = $this->cache_name;
        $list = [];
        if(!Cache::has($cache_name)){
            $goods_class_list = $goods_class_model->orderBy('id','desc')->get()->toArray();
            $list = $this->getChildren($goods_class_list);
            Cache::set($cache_name,$list);
        }else{
            $list = Cache::get($cache_name);
        }
        return $this->format($list);
    }

    // 清空缓存
    public function clear_cache(){
        $rs = Cache::forget($this->cache_name);
        return $this->format($rs);
    }
}
