<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    

    // 创建订单
    public function create_order(){
        $order_service = new OrderService;
        $rs = $order_service->createOrder();
        return $rs['status']?$this->format($rs['data']):$this->format_error($rs['msg']);
    }

    // 创建订单前
    public function create_order_before(){
        $order_service = new OrderService;
        $rs = $order_service->createOrderBefore();
        return $rs['status']?$this->format($rs['data']):$this->format_error($rs['msg']);
    }

    // 创建订单后
    public function create_order_after(){
        $order_service = new OrderService;
        $rs = $order_service->createOrderAfter();
        return $rs['status']?$this->format($rs['data']):$this->format_error($rs['msg']);
    }
}
