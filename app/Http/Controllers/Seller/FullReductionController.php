<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Resources\Seller\FullReductionResource\FullReductionCollection;
use App\Http\Resources\Seller\FullReductionResource\FullReductionResource;
use App\Models\FullReduction;
use Illuminate\Http\Request;

class FullReductionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,FullReduction $fr_model)
    {
        $list = $fr_model->where('store_id',$this->get_store(true))->orderBy('id','desc')->paginate($request->per_page??30);
        return $this->success(new FullReductionCollection($list));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,FullReduction $fr_model)
    {
        $fr_model->store_id = $this->get_store(true);
        $fr_model->name = $request->name??'coupon';
        $fr_model->money = intval($request->money);
        $fr_model->use_money = intval($request->use_money);
        $fr_model->start_time = empty($request->times)?now():$request->times[0];
        $fr_model->end_time = empty($request->times)?now():$request->times[1];

        if($fr_model->money>=$fr_model->use_money){
            return $this->error(__('markets.full_reduction_money_error'));
        }

        $fr_model->save();
        return $this->success([],__('base.success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(FullReduction $fr_model,$id)
    {
        $info = $fr_model->where('store_id',$this->get_store(true))->find($id);
        return $this->success(new FullReductionResource($info));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,FullReduction $fr_model, $id)
    {
        $fr_model = $fr_model->where('store_id',$this->get_store(true))->find($id);
        $fr_model->name = $request->name??'coupon';
        $fr_model->money = intval($request->money);
        $fr_model->use_money = intval($request->use_money);
        $fr_model->start_time = empty($request->times)?now():$request->times[0];
        $fr_model->end_time = empty($request->times)?now():$request->times[1];

        if($fr_model->money>=$fr_model->use_money){
            return $this->error(__('markets.full_reduction_money_error'));
        }

        $fr_model->save();
        return $this->success([],__('base.success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(FullReduction $fr_model,$id)
    {
        $idArray = array_filter(explode(',',$id),function($item){
            return is_numeric($item);
        });
        $fr_model->where('store_id',$this->get_store(true))->whereIn('id',$idArray)->delete();
        return $this->success([],__('base.success'));
    }
}
