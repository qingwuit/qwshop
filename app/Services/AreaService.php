<?php
namespace App\Services;

use App\Models\Area;
use App\Traits\HelperTrait;
use Illuminate\Support\Facades\Cache;

class AreaService extends BaseService{
    use HelperTrait;
    public $cache_name = 'areas_cache';
    public function getAreas(){
        $area_model = new Area();
        $cache_name = $this->cache_name;
        $list = [];
        if(!Cache::has($cache_name)){
            $area_list = $area_model->select('id','pid','name','code','deep')->orderBy('id','asc')->get()->toArray();
            $list = $this->getAreaChildren($area_list);
            Cache::set($cache_name,$list);
        }else{
            $list = Cache::get($cache_name);
        }
        return $this->format($list);
    }

    // 清空缓存
    public function clearCache(){
        $rs = Cache::forget($this->cache_name);
        return $this->format($rs);
    }
}
