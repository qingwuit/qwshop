<?php

namespace App\Http\Controllers\PayCallBack;

use App\Http\Controllers\Controller;
use App\Services\PayMentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    // 回调处理
    public function payment($payment_name){
        $payment_service = new PayMentService();
        return $payment_service->payment($payment_name);
    }
}
