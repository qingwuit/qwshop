<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\StoreResource\StoreCollection;
use App\Http\Resources\Admin\StoreResource\StoreResource;
use App\Models\Store;
use App\Services\StoreService;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,Store $store_model)
    {
        $store_verify = 3;
        // 店铺名称
        if(empty($request->store_name)){
            $store_model->where('store_name','like','%'.$request->store_name.'%');
        }
        // 申请公司名称
        if(empty($request->store_company_name)){
            $store_model->where('store_company_name','like','%'.$request->store_company_name.'%');
        }
        // 审核状态
        if(isset($request->store_verify)){
            $store_verify = $request->store_verify;
        }
        $list = new StoreCollection($store_model->where('store_verify',$store_verify)->orderBy('id','desc')->paginate($request->per_page??30));
        return $this->success($list);
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Store $store_model,$id)
    {
        $info = $store_model->find($id);
        return $this->success(new StoreResource($info));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,StoreService $store_service, $id)
    {
        $store_verify_status = $store_service->editStoreStatus($id,['store_verify'=>$request->store_verify,'store_status'=>$request->store_status,'store_refuse_info'=>$request->store_refuse_info]);
        return $store_verify_status['status']?$this->success($store_verify_status['data'],__('stores.store_success')):$this->error($store_verify_status['msg']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Store $store_model,$id)
    {
        $idArray = array_filter(explode(',',$id),function($item){
            return is_numeric($item);
        });
        $store_model->destroy($idArray);
        return $this->success([],__('base.success'));
    }
}
