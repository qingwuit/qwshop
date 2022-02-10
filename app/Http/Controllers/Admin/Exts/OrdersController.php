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
}
