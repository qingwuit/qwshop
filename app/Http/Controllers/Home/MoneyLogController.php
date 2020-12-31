<?php

namespace App\Http\Controllers\Home;

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
    public function index()
    {
        $money_log_service = new MoneyLogService();
        $list = $money_log_service->getMoneyLog()['data'];
        return $this->success($list);
    }

}
