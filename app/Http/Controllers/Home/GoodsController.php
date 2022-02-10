<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GoodsController extends Controller
{
    public function goods()
    {
        return $this->handle($this->getService('Goods')->all());
    }

    public function show($id)
    {
        $goods_info = $this->getService('Goods')->getGoodsInfo($id);
        if ($goods_info['status']) {
            $goods_info['data']['store_info'] = $this->getService('Store')->getStoreInfoAndRate($goods_info['data']['store_id'])['data'];
            $goods_info['data']['rate'] = $goods_info['data']['store_info']['rate'];
            $goods_info['data']['comment_statistics'] = $this->getService('OrderComment')->commentStatistics($goods_info['data']['id'])['data'];
            $goods_info['data']['sale_list'] = $this->getService('Goods')->sortGoods($goods_info['data']['store_id'], 'store_id', 6, ['class_id'=>$goods_info['data']['class_id']])['data']; // 销售排名
            $goods_info['data']['coupons'] = $this->getService('Coupon')->getCouponByStoreId($goods_info['data']['store_id'])['data']; // 优惠券
            // $goods_info['data']['full_reductions'] = $full_reduction_service->getFullReductionByStoreId($goods_info['data']['store_id'])['data']; // 满减
            $seckill_info = $this->getService('Seckill')->seckillInfoByGoodsId($id);
            $goods_info['data']['seckills'] = $seckill_info['status']?$seckill_info['data']:false; // 秒杀
            $collective_info = $this->getService('Collective')->getCollectiveInfoByGoodsId($id);
            $goods_info['data']['collectives'] = $collective_info['status']?$collective_info['data']:false; // 团购
            $goods_info['data']['collective_list'] = $this->getService('Collective')->getCollectiveLogByGoodsId($id)['data']; // 正在团的
        }
        return $this->handle($goods_info);
    }

    public function goods_comments($id)
    {
        $id = intval($id);
        return $this->handle($this->getService('OrderComment')->commentList($id));
    }
}
