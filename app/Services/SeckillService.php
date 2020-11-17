<?php
namespace App\Services;

use App\Models\Seckill;

class SeckillService extends BaseService{
    // 根据商品ID 获取满减 详情
    public function getSeckillInfoByGoodsId($goods_id){
        $seckill_model = new Seckill();
        $info = $seckill_model->select('discount','start_time','end_time')->where('goods_id',$goods_id)->where('start_time','<=',now())->where('end_time','>=',now())->first();

        if(!$info){
            return $this->format_error('seckill empty');
        }

        return $this->format($info);
    }
}
