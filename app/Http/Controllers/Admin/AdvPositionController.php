<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\AdvPositionResource\AdvPositionCollection;
use App\Models\AdvPosition;
use Illuminate\Http\Request;

class AdvPositionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,AdvPosition $advm_model)
    {
        if(!empty($request->ap_name)){
            $advm_model = $advm_model->where('ap_name','like','%'.$request->ap_name.'%');
        }
        $list = $advm_model->orderBy('id','desc')->paginate($request->per_page??30);
        return $this->success(new AdvPositionCollection($list));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,AdvPosition $advm_model)
    {
        $advm_model->ap_name = $request->ap_name??'';
        $advm_model->ap_width = $request->ap_width??'';
        $advm_model->ap_height = $request->ap_height??'';
        $advm_model->save();
        return $this->success([],__('base.success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(AdvPosition $advm_model,$id)
    {
        $info = $advm_model->find($id);
        return $this->success($info);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,AdvPosition $advm_model, $id)
    {
        $advm_model = $advm_model->find($id);
        $advm_model->ap_name = $request->ap_name??'';
        $advm_model->ap_width = $request->ap_width??'';
        $advm_model->ap_height = $request->ap_height??'';
        $advm_model->save();
        return $this->success([],__('base.success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(AdvPosition $advm_model,$id)
    {
        $idArray = array_filter(explode(',',$id),function($item){
            return is_numeric($item);
        });
        $advm_model->destroy($idArray);
        return $this->success([],__('base.success'));
    }
}
