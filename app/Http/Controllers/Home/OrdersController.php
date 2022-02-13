<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    // 下单前的数据处理
    public function before()
    {
        return $this->handle($this->getService('Order')->createOrderBefore());
    }

    // 创建订单
    public function create()
    {
        return $this->handle($this->getService('Order')->createOrder());
    }

    // 下单后数据处理
    public function after()
    {
        return $this->handle($this->getService('Order')->createOrderAfter());
    }

    // 支付订单
    public function pay()
    {
        return $this->handle($this->getService('Order')->payOrder());
    }

    // 验证支付状态
    public function check()
    {
        $check = $this->getService('Payment')->check(request('order_id'));
        if ($check['status']) {
            return $this->success($check['data']);
        }
        return $this->success(['code'=>'fail','msg'=>$check['msg']]);
    }

    // 订单列表
    public function orders()
    {
        return $this->handle($this->getService('Order')->getOrders());
    }

    // 获取订单详情
    public function show($id)
    {
        return $this->handle($this->getService('Order')->getOrderInfoById($id));
    }

    // 修改状态
    public function edit($id)
    {
        return $this->handle($this->getService('Order')->editOrderStatus($id, request()->order_status??1));
    }

    // 物流查询
    public function express(Request $request)
    {
        // 根据订单Id查询
        $order = $this->getService('Order',true)->where(['user_id'=>$this->getUserId('users'),'id'=>$request->id])->first();
        if(!$order) return $this->error(__('tip.order.empty'));
        return $this->handle($this->getService('KuaiBao')->getExpressInfo($order->delivery_no, $order->delivery_code, $order->receive_tel));
    }
}
