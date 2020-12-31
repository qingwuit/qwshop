<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Resources\Seller\DistributionResource\DistributionResource;
use App\Models\Distribution;
use App\Services\DistributionService ;
use Illuminate\Http\Request;

class DistributionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(DistributionService $dis_service)
    {
        $rs = $dis_service->getList();
        return $rs['status']?$this->success($rs['data']):$this->error();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Distribution $dis_model)
    {
        $dis_model->goods_id = intval($request->goods_id);
        $dis_model->store_id = $this->get_store(true);
        $dis_model->lev_1 = floatval($request->lev_1);
        $dis_model->lev_2 = floatval($request->lev_2);
        $dis_model->lev_3 = floatval($request->lev_3);
        $dis_model->save();
        return $this->success([],__('base.success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Distribution $dis_model,$id)
    {
        $info = $dis_model->with(['goods'=>function($q){
            $q->select('id','goods_name','goods_master_image');
        }])->where('id',$id)->first();
        return $this->success(new DistributionResource($info),__('base.success'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Distribution $dis_model, $id)
    {
        $dis_model = $dis_model->where('store_id',$this->get_store(true))->where('id',$id)->first();
        $dis_model->goods_id = intval($request->goods_id);
        $dis_model->lev_1 = floatval($request->lev_1);
        $dis_model->lev_2 = floatval($request->lev_2);
        $dis_model->lev_3 = floatval($request->lev_3);
        $dis_model->save();
        return $this->success([],__('base.success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Distribution $dis_model,$id)
    {
        $idArray = array_filter(explode(',',$id),function($item){
            return is_numeric($item);
        });
        $dis_model->where('store_id',$this->get_store(true))->whereIn('id',$idArray)->delete();;
        return $this->success([],__('base.success'));
    }

    // 获取分销商品
    public function get_distribution_goods(){
        $dis_service = new DistributionService();
        $list = $dis_service->getDistributionGoods()['data'];
        return $this->success($list);
    }
}
