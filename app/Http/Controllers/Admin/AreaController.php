<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Services\AreaService;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(AreaService $area_service)
    {
        $list = $area_service->getAreas()['data'];
        return $this->success($list);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Area $area_model)
    {
        $area_model->pid = $request->pid??'';
        $area_model->name = $request->name;
        $area_model->code = $request->code??'';
        $area_model->deep = $request->deep??0;
        $area_model->save();
        $this->clear_cache(); // 修改则清空缓存
        return $this->success([],__('base.success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Area $area_model,$id)
    {
        $info = $area_model->find($id);
        return $this->success($info);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Area $area_model, $id)
    {
        $area_model = $area_model->find($id);
        $area_model->pid = $request->pid??'';
        $area_model->name = $request->name;
        $area_model->code = $request->code??'';
        $area_model->deep = $request->deep??0;
        $area_model->save();
        $this->clear_cache(); // 修改则清空缓存
        return $this->success([],__('base.success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Area $area_model,$id)
    {
        $idArray = array_filter(explode(',',$id),function($item){
            return is_numeric($item);
        });
        $area_model->destroy($idArray);
        $this->clear_cache(); // 修改则清空缓存
        return $this->success([],__('base.success'));
    }

    public function clear_cache(){
        $area_service = new AreaService();
        $area_service->clearCache();
        return $this->success([],__('base.success'));
    }
}
