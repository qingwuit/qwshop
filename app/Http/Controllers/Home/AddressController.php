<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Services\AddressService;
use App\Services\UserService;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $address_service = new AddressService;
        $rs = $address_service->getAddresses();
        return $rs['status']?$this->success($rs['data']):$this->error($rs['msg']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $address_service = new AddressService;
        $rs = $address_service->add();
        return $rs['status']?$this->success($rs['data'],$rs['msg']):$this->error($rs['msg']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Address $address_model,$id)
    {
        $user_service = new UserService;
        $user_info = $user_service->getUserInfo();
        $rs = $address_model->where('id',$id)->where('user_id',$user_info['id'])->first();
        $rs['area_id'] = [$rs['province_id'],$rs['city_id'],$rs['region_id']];
        return $this->success($rs);
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
        $address_service = new AddressService;
        $rs = $address_service->edit($id);
        return $rs['status']?$this->success($rs['data'],$rs['msg']):$this->error($rs['msg']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Address $address_model,$id)
    {
        $idArray = array_filter(explode(',',$id),function($item){
            return is_numeric($item);
        });

        // 获取当前用户user_id
        $user_service = new UserService;
        $user_info = $user_service->getUserInfo();

        $address_model->whereIn('id',$idArray)->where('user_id',$user_info['id'])->delete();
        return $this->success([],__('base.success'));
    }

    // 设置默认地址
    public function set_default(Request $request){
        $address_service = new AddressService;
        $rs = $address_service->setDefault($request->id);
        return $rs['status']?$this->success($rs['data'],$rs['msg']):$this->error($rs['msg']);
    }

    // 获取默认地址
    public function get_default(){
        $address_service = new AddressService;
        $rs = $address_service->getDefault();
        return $rs['status']?$this->success($rs['data'],$rs['msg']):$this->error($rs['msg']);
    }
}
