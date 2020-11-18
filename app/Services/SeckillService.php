<?php
namespace App\Services;

use App\Http\Resources\Home\GoodsResource\SeckillGoodsIndexCollection;
use App\Models\Goods;
use App\Models\Seckill;

class SeckillService extends BaseService{
    // 根据商品ID 获取秒杀 详情
    public function getSeckillInfoByGoodsId($goods_id){
        $seckill_model = new Seckill();
        $info = $seckill_model->select('discount','start_time','end_time')->where('goods_id',$goods_id)->where('start_time','<=',now())->where('end_time','>=',now())->first();

        if(!$info){
            return $this->format_error('seckill empty');
        }

        return $this->format($info);
    }

    // 获取首页秒杀产品
    public function getIndexSeckillAndGoods($num=4){
        $goods_model = new Goods();
        $list = $goods_model->where(['goods_status'=>1,'goods_verify'=>1])
                        ->with(['goods_sku'=>function($q){
                            return $q->select('goods_id','goods_price','goods_stock','goods_market_price')->orderBy('goods_price','asc');
                        }])
                        ->whereHas('seckill',function($q){
                            $q->where('start_time',now()->format('Y-m-d H').':00');
                        })
                        ->take($num)->get();
        return $this->format(new SeckillGoodsIndexCollection($list));
    }
}
