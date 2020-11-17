<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Resources\Seller\OrderSettlementResource\OrderSettlementCollection;
use App\Http\Resources\Seller\OrderSettlementResource\OrderSettlementInfoCollection;
use App\Models\OrderSettlement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderSettlementController extends Controller
{

    public function index(Request $request,OrderSettlement $os_model)
    {
        $list = $os_model->select(DB::raw("*,sum(total_price) as total,sum(settlement_price) as settlement"))->where('store_id',$this->get_store(true))->groupBy('settlement_no')->orderBy('id','desc')->paginate($request->per_page??30);
        return $this->success(new OrderSettlementCollection($list));
    }


    public function show(Request $request,OrderSettlement $os_model,$id)
    {
        $list = $os_model->where('store_id',$this->get_store(true))->where('settlement_no',$id)->with('order:id,order_no')->orderBy('id','desc')->paginate($request->per_page??30);
        return $this->success(new OrderSettlementInfoCollection($list));
    }

 
}
