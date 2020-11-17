<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IntegralGoodsClass;
use Illuminate\Http\Request;

class IntegralGoodsClassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,IntegralGoodsClass $igc_model)
    {
        if(!empty($request->name)){
            $igc_model = $igc_model->where('name','like','%'.$request->name.'%');
        }
        $list = $igc_model->orderBy('id','desc')->paginate($request->per_page??30);
        return $this->success($list);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,IntegralGoodsClass $igc_model)
    {
        $igc_model->name = $request->name;
        $igc_model->save();
        return $this->success([],__('base.success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(IntegralGoodsClass $igc_model,$id)
    {
        $info = $igc_model->find($id);
        return $this->success($info);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,IntegralGoodsClass $igc_model, $id)
    {
        $igc_model = $igc_model->find($id);
        $igc_model->name = $request->name;
        $igc_model->save();
        return $this->success([],__('base.success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(IntegralGoodsClass $igc_model,$id)
    {
        $idArray = array_filter(explode(',',$id),function($item){
            return is_numeric($item);
        });
        $igc_model->destroy($idArray);
        return $this->success([],__('base.success'));
    }
}
