<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Services\RefundService;
use Illuminate\Http\Request;

class RefundController extends Controller
{

    
    public function store(RefundService $refund_service){
        $rs = $refund_service->add();
        return $rs['status']?$this->success($rs['data'],$rs['msg']):$this->error($rs['msg']);
    }
    // 这里的ID 都是OrderId 
    public function show(RefundService $refund_service,$id){
        $rs = $refund_service->getRefundByOrderId($id);
        return $rs['status']?$this->success($rs['data']):$this->error($rs['msg']);
    }
    // 这里的ID 都是OrderId 
    public function update(RefundService $refund_service,$id){
        $rs = $refund_service->edit($id);
        return $rs['status']?$this->success([],$rs['msg']):$this->error($rs['msg']);
    }
}
