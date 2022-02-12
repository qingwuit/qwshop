<?php

namespace App\Http\Controllers\Admin\Exts;

use App\Http\Controllers\Controller;

class OrderSettlementsController extends Controller
{
    protected $modelName = 'OrderSettlement';

    // 手动结算订单
    public function handle_sett()
    {
        return $this->handle($this->getService('OrderSettlement')->add(false));
    }
}
