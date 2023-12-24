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

    // 编辑发货信息
    public function edit(Request $request)
    {
        $id = $request->id ?? 0;
        $status = $request->status ?? 3;
        return $this->handle($this->getService('Order')->editOrderStatus($id, $status, 'admin'));
    }

    // 物流查询
    public function express(Request $request)
    {
        // 根据订单Id查询
        $order = $this->getService('Order', true)->where(['id' => $request->id])->first();
        if (!$order) return $this->error(__('tip.order.empty'));
        return $this->handle($this->getService('KuaiBao')->getExpressInfo($order->delivery_no, $order->delivery_code, $order->receive_tel));
    }

    // 修改订单信息
    public function update(Request $request, $id)
    {
        return $this->handle($this->getService('base')->editDat($this->modelName, $id, ['delivery_no', 'delivery_code', 'order_status', 'order_name', 'receive_name', 'receive_tel', 'receive_area', 'receive_address']));
    }

    // 云打印订单
    public function print_waybill($id)
    {
        $rs = $this->getService('KuaiBao')->printWaybill($id);
        return $this->handle($rs);
    }
}
