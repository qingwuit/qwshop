<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\OrderSettlementResource\OrderSettlementCollection;
use App\Http\Resources\Admin\OrderSettlementResource\OrderSettlementInfoCollection;
use App\Models\OrderSettlement;
use App\Services\OrderSettlementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderSettlementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,OrderSettlement $os_model)
    {
        $list = $os_model->select(DB::raw("*,sum(total_price) as total,sum(settlement_price) as settlement"))->groupBy('settlement_no')->orderBy('id','desc')->paginate($request->per_page??30);
        return $this->success(new OrderSettlementCollection($list));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrderSettlementService $os_service)
    {
        // 手动处理
        $rs = $os_service->add(false);
        return $rs['status']?$this->success($rs['data'],__('base.success')):$this->error($rs['msg']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,OrderSettlement $os_model,$id)
    {
        $list = $os_model->where('settlement_no',$id)->with('order:id,order_no')->orderBy('id','desc')->paginate($request->per_page??30);
        return $this->success(new OrderSettlementInfoCollection($list));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
