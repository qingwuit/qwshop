<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Http\Resources\Home\IntegralOrderResource\IntegralOrderCollection;
use App\Http\Resources\Home\IntegralOrderResource\IntegralOrderResource;
use App\Models\IntegralGoods;
use App\Models\IntegralGoodsClass;
use App\Services\AdvService;
use App\Services\IntegralGoodsService;
use App\Services\IntegralOrderService;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;

class IntegralController extends Controller
{
    use HelperTrait;

    // 获取订单列表
    public function get_orders(){
        $order_service = new IntegralOrderService();
        $list = $order_service->getOrders()['data'];
        return $this->success(new IntegralOrderCollection($list));
    }

    // 获取订单详情
    public function get_order_info($id){
        $order_service = new IntegralOrderService();
        $rs = $order_service->getOrderInfoById($id,'user');
        return $rs['status']?$this->success(new IntegralOrderResource($rs['data']),$rs['msg']):$this->error($rs['msg']);
    }

    public function index(IntegralGoods $ig_model,IntegralGoodsClass $igc_model,AdvService $adv_service){
        $data['recommend'] = $ig_model->where('goods_status',1)->where('is_recommend',1)->take(4)->get();
        $data['list'] = $igc_model->with(['integral_goods'=>function($q){
            $q->where('goods_status',1)->take(4);
        }])->get();
        $data['banner'] = $adv_service->getAdvList('PC_积分商城幻灯片')['data'];
        return $this->success($data);
    }

    public function show(IntegralGoods $ig_model,$id){
        $info = $ig_model->where('goods_status',1)->where('id',$id)->first();
        $info['goods_images'] = explode(',',$info['goods_images']);
        $info['goods_images_thumb_150'] = $this->thumb_array($info['goods_images'],150);
        $info['goods_images_thumb_400'] = $this->thumb_array($info['goods_images'],400);
        return $this->success($info);
    }

    // 搜索
    public function search(IntegralGoodsService $igs){
        $rs = $igs->search();
        return $rs['status']?$this->success($rs['data']):$this->error($rs['msg']);
    }

    // 获取积分分类
    public function get_integral_class(){
        return $this->success(IntegralGoodsClass::get());
    }

    // 支付积分订单
    public function pay(IntegralOrderService $ios){
        $rs = $ios->createOrder();
        return $rs['status']?$this->success($rs['data']):$this->error($rs['msg']);
    }
}
