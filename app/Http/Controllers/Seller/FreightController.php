<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Resources\Seller\FreightResource\FreightCollection;
use App\Models\Freight;
use App\Services\FreightService;
use Illuminate\Http\Request;

class FreightController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Freight $freight_model)
    {
        $store_id = $this->get_store(true);
        $list = $freight_model->where('store_id',$store_id)->orderBy('is_type','asc')->orderBy('id','desc')->get();
        return $this->success(new FreightCollection($list));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FreightService $freight_service)
    {
        $rs = $freight_service->edit();
        return $rs['status']?$this->success($rs['data'],$rs['msg']):$this->error($rs['msg']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function show($id)
    // {
    //     //
    // }

    // /**
    //  * Update the specified resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function update(Request $request, $id)
    // {
    //     //
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Freight $freight_model, $id)
    {
        $idArray = array_filter(explode(',',$id),function($item){
            return is_numeric($item);
        });

        $freight_model->destroy($idArray);
        return $this->success([],__('base.success'));
    }
}
