<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Resources\Seller\SeckillResource\SeckillCollection;
use App\Http\Resources\Seller\SeckillResource\SeckillGoodsCollection;
use App\Http\Resources\Seller\SeckillResource\SeckillResource;
use App\Models\Goods;
use App\Models\Seckill;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SeckillController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Seckill $seckill_model)
    {
        $store_id = $this->get_store(true);
        $list = $seckill_model->with(['goods'=>function($q){
            $q->select('id','goods_name','goods_master_image');
        }])->where('store_id',$store_id)->orderBy('id','desc')->paginate(request()->per_page??30);
        return $this->format(new SeckillCollection($list));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Seckill $seckill_model)
    {
        $seckill_model->goods_id = intval($request->goods_id);
        $seckill_model->store_id = $this->get_store(true);
        $seckill_model->discount = floatval($request->discount);
        $seckill_model->start_time = empty($request->start_time)?now():$request->start_time;
        $seckill_model->end_time = Carbon::parse($seckill_model->start_time)->addHours(1);

        $seckill_model->save();
        return $this->success([],__('base.success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Seckill $seckill_model,$id)
    {
        $info = $seckill_model->where('store_id',$this->get_store(true))->find($id);
        return $this->success(new SeckillResource($info));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Seckill $seckill_model,$id)
    {
        $seckill_model = $seckill_model->where('store_id',$this->get_store(true))->find($id);
        $seckill_model->goods_id = intval($request->goods_id);
        $seckill_model->discount = floatval($request->discount);
        $seckill_model->start_time = empty($request->start_time)?now():$request->start_time;
        $seckill_model->end_time = Carbon::parse($seckill_model->start_time)->addHours(1);

        $seckill_model->save();
        return $this->success([],__('base.success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Seckill $seckill_model,$id)
    {
        $idArray = array_filter(explode(',',$id),function($item){
            return is_numeric($item);
        });
        $seckill_model->where('store_id',$this->get_store(true))->whereIn('id',$idArray)->delete();
        return $this->success([],__('base.success'));
    }

    // 获取商品
    public function get_seckill_goods(){
        $goods_model = new Goods();
        $store_id = $this->get_store(true);

        if(isset(request()->goods_name) && !empty(request()->goods_name)){
            $goods_model = $goods_model->where('goods_name','like','%'.request()->goods_name.'%');
        }

        $list = $goods_model->with(['seckill'=>function($q){
            $q->select('goods_id');
        }])->where('store_id',$store_id)->paginate(request()->per_page??30);
        return $this->success(new SeckillGoodsCollection($list));
    }
}
