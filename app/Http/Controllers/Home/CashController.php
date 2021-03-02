<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Services\CashService;
use Illuminate\Http\Request;

class CashController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CashService $cash_service)
    {
        $list = $cash_service->getCash()['data'];
        return $this->success($list);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CashService $cash_service)
    {
        $rs = $cash_service->add();
        return $rs['status']?$this->success($rs['data'],$rs['msg']):$this->error($rs['msg']);
    }

    
}
