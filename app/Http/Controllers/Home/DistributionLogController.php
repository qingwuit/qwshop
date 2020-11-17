<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Services\DistributionService;
use Illuminate\Http\Request;

class DistributionLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(DistributionService $dis_service)
    {
        $rs = $dis_service->getLogList();
        return $rs['status']?$this->success($rs['data']):$this->error();
    }

    // 获取分销下所有用户
    public function user(DistributionService $dis_service){
        $rs = $dis_service->getUser();
        return $rs['status']?$this->success($rs['data']):$this->error();
    }

    
}
