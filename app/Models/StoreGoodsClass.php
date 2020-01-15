<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Goods;

class StoreGoodsClass extends Model
{
    protected $table = "store_goods_class"; //指定表
    protected $primaryKey = "id"; //指定id字段
    public $timestamps = false;

    protected $params = ['goods_status'=>1,'goods_verify'=>1];  // 上架   和   审核通过

    /**
     * getGoodsListByStoreGoodsClass function
     *
     * @param array $id // 选择自定义的东西
     * @param integer $page // 每页多少条
     * @param boolean $fy  // 是否翻页
     * @return void
     * @Description
     * @author hg <www.qingwuit.com>
     */
    public function getGoodsListByStoreGoodsClass($id = [],$page=12,$fy=false){
        if(empty($id)){
            return [];
        }
        $store_goods_class_list = [];
        $goods_model = new Goods();
        if($fy){
            if($id[0] == 0){
                $goods_list = $goods_model->where($this->params)->with('spec_once')->paginate($page)->toArray();
            }else{
                $goods_list = $goods_model->where($this->params)->where('store_goods_class',$id[0])->with('spec_once')->paginate($page)->toArray();
            }
            
            // 是否有规格，有规格则取规格价格
            foreach($goods_list['data'] as $k=>$v){
                if(!empty($v['spec_once'])){
                    $v['goods_price'] = $v['spec_once']['goods_price'];
                    $v['goods_market_price'] = $v['spec_once']['goods_market_price'];
                }
                $v['image'] = get_format_image($v['goods_master_image'],200);
                $goods_list['data'][$k] = $v;

            }
            $store_goods_class_list = $goods_list;
        }else{
            foreach($id as $v){
                $goods_list = $goods_model->where($this->params)->where('store_goods_class',$v)->take($page)->with('spec_once')->get()->toArray();
                // 是否有规格，有规格则取规格价格
                foreach($goods_list as $k=>$v){
                    if(!empty($v['spec_once'])){
                        $v['goods_price'] = $v['spec_once']['goods_price'];
                        $v['goods_market_price'] = $v['spec_once']['goods_market_price'];
                    }
                    $v['image'] = get_format_image($v['goods_master_image'],200);
                    $goods_list[$k] = $v;
                }
                $store_goods_class_list[] = $goods_list;
            }
        }
        return $store_goods_class_list;
    }
}
