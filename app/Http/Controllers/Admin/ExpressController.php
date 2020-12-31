<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Express;
use Illuminate\Http\Request;

class ExpressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,Express $express_model)
    {
        $list = $express_model->orderBy('id','asc')->paginate($request->per_page??30);
        return $this->success($list);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Express $express_model)
    {
        $express_model->name = $request->name??'';;
        $express_model->code = $request->code??'';;
        $express_model->save();
        return $this->success(__('base.success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Express $express_model,$id)
    {
        $info = $express_model->find($id);
        return $this->success($info);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Express $express_model, $id)
    {
        $express_model = $express_model->find($id);
        $express_model->name = $request->name??'';
        $express_model->code = $request->code??'';
        $express_model->save();
        return $this->success(__('base.success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Express $express_model,$id)
    {
        $idArray = array_filter(explode(',',$id),function($item){
            return is_numeric($item);
        });
        $express_model->destroy($idArray);
        return $this->success(__('base.success'));
    }
}
