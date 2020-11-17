<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Services\MoneyLogService;
use Illuminate\Http\Request;

class MoneyLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(MoneyLogService $ml_service)
    {
        $rs = $ml_service->getMoneyLog('seller');
        return $rs['status']?$this->success($rs['data']):$this->error($rs['msg']);
    }

    
}
