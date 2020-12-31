<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Services\RefundService;
use Illuminate\Http\Request;

class RefundController extends Controller
{
    // 这里的ID 都是OrderId 
    public function show(RefundService $refund_service,$id){
        $rs = $refund_service->getRefundByOrderId($id,'seller');
        return $rs['status']?$this->success($rs['data']):$this->error($rs['msg']);
    }
    // 这里的ID 都是OrderId 
    public function update(RefundService $refund_service,$id){
        $rs = $refund_service->edit($id,'seller');
        return $rs['status']?$this->success([],$rs['msg']):$this->error($rs['msg']);
    }
}
