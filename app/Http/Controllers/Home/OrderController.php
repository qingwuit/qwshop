<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Http\Resources\Home\OrderResource\OrderCollection;
use App\Http\Resources\Home\OrderResource\OrderResource;
use App\Services\OrderService;
use App\Services\PayMentService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    
    // 获取订单列表
    public function get_orders(){
        $order_service = new OrderService();
        $list = $order_service->getOrders()['data'];
        return $this->success(new OrderCollection($list));
    }

    // 创建订单
    public function create_order(){
        $order_service = new OrderService;
        $rs = $order_service->createOrder();
        return $rs['status']?$this->success($rs['data']):$this->error($rs['msg']);
    }

    // 创建订单前
    public function create_order_before(){
        $order_service = new OrderService;
        $rs = $order_service->createOrderBefore();
        return $rs['status']?$this->success($rs['data']):$this->error($rs['msg']);
    }

    // 创建订单后
    public function create_order_after(){
        $order_service = new OrderService;
        $rs = $order_service->createOrderAfter();
        return $rs['status']?$this->success($rs['data']):$this->error($rs['msg']);
    }

    // 支付订单
    public function pay(){
        $order_service = new OrderService();
        $rs = $order_service->payOrder();
        return $rs['status']?$this->success($rs['data']):$this->error($rs['msg']);
    }

    // 微信订单验证是否支付成功
    public function wechat_pay_check(PayMentService $payment_service){
        $rs = $payment_service->wechatPayCheck();
        return $rs['status']?$this->success($rs['data']):$this->error($rs['msg']);
    }

    // 修改订单状态 // 用户只能操作取消订单
    public function edit_order_status(Request $request){
        $order_service = new OrderService();
        $rs = $order_service->editOrderStatus($request->id,$request->order_status,'user');
        return $rs['status']?$this->success($rs['data'],$rs['msg']):$this->error($rs['msg']);
    }

    // 获取订单详情
    public function get_order_info($id){
        $order_service = new OrderService();
        $rs = $order_service->getOrderInfoById($id,'user');
        return $rs['status']?$this->success(new OrderResource($rs['data']),$rs['msg']):$this->error($rs['msg']);
    }
}
