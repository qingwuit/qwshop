<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GoodsClass;
use Illuminate\Http\Request;
use App\Services\GoodsClassService;
use App\Services\UploadService;

class GoodsClassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(GoodsClassService $goods_class_service)
    {
        $list = $goods_class_service->getGoodsClasses()['data'];
        return $this->success($list);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,GoodsClass $goods_class_model)
    {
        $goods_class_model->pid = $request->pid??0;
        $goods_class_model->name = $request->name;
        $goods_class_model->thumb = $request->thumb??'';
        $goods_class_model->is_sort = $request->is_sort??0;
        $goods_class_model->save();
        $this->clear_cache(); // 修改则清空缓存
        return $this->success([],__('base.success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(GoodsClass $goods_class_model,$id)
    {
        $info = $goods_class_model->find($id);
        return $this->success($info);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,GoodsClass $goods_class_model, $id)
    {
        $goods_class_model = $goods_class_model->find($id);
        $goods_class_model->pid = $request->pid??0;
        $goods_class_model->name = $request->name;
        $goods_class_model->thumb = $request->thumb??'';
        $goods_class_model->is_sort = $request->is_sort??0;
        $goods_class_model->save();
        $this->clear_cache(); // 修改则清空缓存
        return $this->success([],__('base.success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(GoodsClass $goods_class_model,$id)
    {
        $idArray = array_filter(explode(',',$id),function($item){
            return is_numeric($item);
        });
        if($goods_class_model->whereIn('pid',$idArray)->exists()){
            return $this->error(__('admins.goods_class_delete'));
        }
        $goods_class_model->destroy($idArray);
        $this->clear_cache(); // 修改则清空缓存
        return $this->success([],__('base.success'));
    }

    // 栏目缩略图上传
    public function goods_class_upload(UploadService $upload_service){
        $rs = $upload_service->goods_class();
        if($rs['status']){
            return $this->success($rs['data'],$rs['msg']);
        }else{
            return $this->error($rs['msg']);
        }
    }

    public function clear_cache(){
        $goods_class_service = new GoodsClassService();
        $goods_class_service->clearCache();
        return $this->success([],__('base.success'));
    }
}
