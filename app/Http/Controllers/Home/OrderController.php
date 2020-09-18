<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Http\Resources\Home\OrderResource\OrderCollection;
use App\Services\OrderService;
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
}
