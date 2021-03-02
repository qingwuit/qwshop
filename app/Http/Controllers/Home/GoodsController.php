<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Services\AdvService;
use App\Services\CollectiveService;
use App\Services\CouponService;
use App\Services\FullReductionService;
use App\Services\GoodsService;
use App\Services\OrderCommentService;
use App\Services\SeckillService;
use App\Services\StoreService;
use Illuminate\Http\Request;

class GoodsController extends Controller
{
    // 商品详情
    public function goods_info(GoodsService $goods_service,
                                StoreService $store_service,
                                CouponService $coupon_service,
                                FullReductionService $full_reduction_service,
                                SeckillService $seckill_service,
                                CollectiveService $collective_service,$id){
        $goods_info = $goods_service->getGoodsInfo($id);
        if($goods_info['status']){
            $goods_info['data']['store_info'] = $store_service->getStoreInfoAndRate($goods_info['data']['store_id'],'id,store_name,store_company_name,area_info,store_address,after_sale_service')['data'];
            $goods_info['data']['sale_list'] = $goods_service->getSaleSortGoods(['class_id'=>$goods_info['data']['class_id']])['data']; // 销售排名
            $goods_info['data']['coupons'] = $coupon_service->getCouponByStoreId($goods_info['data']['store_id'])['data']; // 优惠券
            $goods_info['data']['full_reductions'] = $full_reduction_service->getFullReductionByStoreId($goods_info['data']['store_id'])['data']; // 满减
            $seckill_info = $seckill_service->getSeckillInfoByGoodsId($id);
            $goods_info['data']['seckills'] = $seckill_info['status']?$seckill_info['data']:false; // 秒杀
            $collective_info = $collective_service->getCollectiveInfoByGoodsId($id);
            $goods_info['data']['collectives'] = $collective_info['status']?$collective_info['data']:false; // 团购
            $goods_info['data']['collective_list'] = $collective_service->getCollectiveLogByGoodsId($id)['data']; // 正在团的
            return $this->success($goods_info['data']);
        }
        return $this->error($goods_info['msg']);
        
    }
    // 评论统计
    public function goods_comment_statistics(OrderCommentService $ocs,$id){
        $data = $ocs->getCommentStatistics($id);
        if($data['status']){
            $data=$ocs->getCommentStatistics($id);
            return $this->success($data['data']);
        }
        return $this->error($data['msg']);
    }

    // 评论列表
    public function goods_comments(OrderCommentService $ocs,$id){
        $data = $ocs->getList($id);
        if($data['status']){
            $data=$ocs->getList($id);
            return $this->success($data['data']);
        }
        return $this->error($data['msg']);
    }

    // 搜索产品
    public function goods_search(GoodsService $goods_service){
        
        $info = $goods_service->goodsSearch();
        if(!$info['status']){
            return $this->error($info['msg']);
        }
        
        return $this->success($info['data']);
    }

    // 获取拼团幻灯片
    public function collection_banner(AdvService $adv_service){
        $data = $adv_service->getAdvList('PC_拼团幻灯片')['data'];
        return $this->success($data);
    }
}
