<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\IntegralOrderResource\IntegralOrderCollection;
use App\Http\Resources\Admin\IntegralOrderResource\IntegralOrderResource;
use App\Models\IntegralOrder;
use App\Services\IntegralOrderService;
use Illuminate\Http\Request;

class IntegralOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(IntegralOrderService $order_service)
    {
        $rs = $order_service->getOrders('admin');
        return $rs['status']?$this->success(new IntegralOrderCollection($rs['data'])):$this->error($rs['msg']);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(IntegralOrderService $order_service,$id)
    {
        $rs = $order_service->getOrderInfoById($id,'seller');
        return $rs['status']?$this->success(new IntegralOrderResource($rs['data'])):$this->error($rs['msg']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,IntegralOrder $order_model, $id)
    {
        $order_model = $order_model->where(['id'=>$id])->first();
        $order_model->delivery_code = $request->delivery_code??'yd';
        $order_model->delivery_no = $request->delivery_no??'123456';

        // 判断是否需要修改订单状态
        if($order_model->order_status==2){
            $order_model->order_status = 6;
        }
        $order_model->save();
        return $this->success([],__('base.success'));
    }
}
