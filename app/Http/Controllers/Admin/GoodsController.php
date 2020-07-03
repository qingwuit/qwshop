<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Goods;
use App\Http\Resources\Admin\GoodsResource\GoodsTabAdminCollection;

class GoodsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,Goods $goods_model)
    {
        // 条件筛选
        $goods_params = ['goods_verify'=>1,'goods_status'=>1]; // 商品后台审核 还有上架
        if(!empty($request->goods_name)){
            $goods_model = $goods_model->where('name','like','%'.$request->goods_name.'%');
        }
        if(!empty($request->brand_id)){
            $goods_model = $goods_model->where('brand_id',$request->brand_id);
        }
        if(!empty($request->class_id)){
            $goods_model = $goods_model->where('class_id',$request->class_id);
        }
        if(!empty($request->goods_no)){
            $goods_model = $goods_model->where('goods_no',$request->goods_no);
        }
        if(isset($request->goods_status)){
            $goods_params['goods_status'] = $request->goods_status;
        }
        if(isset($request->goods_verify)){
            $goods_params['goods_verify'] = $request->goods_verify;
        }
        
        $list = $goods_model->where($goods_params)
                            ->with(['goods_class','goods_brand'])
                            ->orderBy('id','desc')
                            ->paginate($request->per_page??30);
        return $this->success(new GoodsTabAdminCollection($list));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
