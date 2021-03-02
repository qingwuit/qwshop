<?php
namespace App\Services;

use App\Http\Resources\Home\GoodsResource\GoodsListCollection;
use App\Models\Goods;
use App\Models\GoodsClass;
use App\Traits\HelperTrait;
use Illuminate\Support\Facades\Cache;

class GoodsClassService extends BaseService{
    use HelperTrait;
    public $cache_name = 'goods_classes_cache';
    public function getGoodsClasses(){
        $goods_class_model = new GoodsClass();
        $cache_name = $this->cache_name;
        $list = [];
        if(!Cache::has($cache_name)){
            $goods_class_list = $goods_class_model->orderBy('is_sort','asc')->get()->toArray();
            $list = $this->getChildren($goods_class_list);
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

    // 获取一级栏目下所有商品信息 is_matser = 1  $goods_num ,获取数量
    public function is_master($goods_num = 6){
        $goods_model = new Goods();
        $goods_class_list = $this->getGoodsClasses()['data'];
        $class_goods = [];
        foreach($goods_class_list as $k=>$v){
            $class_goods[$k]['name'] = $v['name'];
            $class_goods[$k]['id'] = $v['id'];
            $class_goods[$k]['goods'] = [];
            $class_goods[$k]['class_id'] = [];
            foreach($v['children'] as $vo){
                if(isset($vo['children'])){
                    foreach($vo['children'] as $item){
                        $class_goods[$k]['class_id'][] = $item['id'];
                    }
                }
                
            }
        }

        foreach($class_goods as &$v){
            $v['goods'] = new GoodsListCollection($goods_model->whereHas('store',function($q){
                return $q->where(['store_status'=>1,'store_verify'=>3]);
            })->with(['goods_skus'=>function($q){
                return $q->orderBy('goods_price','asc');
            }])->where(['goods_status'=>1,'goods_verify'=>1])->whereIn('class_id',$v['class_id'])->take($goods_num)->get());
            unset($v['class_id']);
        }

        return $this->format($class_goods);
    }

    // 根据商品ID 获取分类信息
    public function getGoodsClassByGoodsId($id){
        $goods_model = new Goods;
        $goods_class_model = new GoodsClass;
        $goods_info = $goods_model->find($id);
        $first_class = $goods_class_model->select('id','pid','name')->where('id',$goods_info['class_id'])->first();
        $sec_class = $goods_class_model->select('id','pid','name')->where('id',$first_class['pid'])->first();
        $tr_class = $goods_class_model->select('id','pid','name')->where('id',$sec_class['pid'])->first();
        $data = [$tr_class,$sec_class,$first_class];
        return $this->format($data);
    }
}
