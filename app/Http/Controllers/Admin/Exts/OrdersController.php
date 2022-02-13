<?php

namespace App\Http\Controllers\Admin\Exts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    protected $modelName = 'Order';

    // 获取订单商品格式化列表
    public function all()
    {
        return $this->handle($this->getService('Order')->createOrderAfter());
    }

    // 物流查询
    public function express(Request $request)
    {
        // 根据订单Id查询
        $order = $this->getService('Order',true)->where(['id'=>$request->id])->first();
        if(!$order) return $this->error(__('tip.order.empty'));
        return $this->handle($this->getService('KuaiBao')->getExpressInfo($order->delivery_no, $order->delivery_code, $order->receive_tel));
    }
}
