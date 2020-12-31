<?php

namespace App\Http\Controllers\Seller;

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
        $rs = $dis_service->getLogList('seller');
        return $rs['status']?$this->success($rs['data']):$this->error();
    }
 
}
