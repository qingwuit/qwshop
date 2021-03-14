<?php

namespace App\Http\Controllers\PayCallBack;

use App\Http\Controllers\Controller;
use App\Services\PayMentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    // 回调处理
    public function payment($payment_name){
        $payment_service = new PayMentService();
        $rs = $payment_service->payment($payment_name);
        if($rs['status']){
            return $rs['data'];
        }else{
            Log::channel('qwlog')->debug($payment_name.'-'.$rs['msg']);
        }
    }
}
