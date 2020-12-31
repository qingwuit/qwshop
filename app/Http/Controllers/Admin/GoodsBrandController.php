<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GoodsBrand;
use App\Services\UploadService;
use Illuminate\Http\Request;
use App\Http\Resources\Admin\GoodsBrandResource\GoodsBrandCollection;

class GoodsBrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,GoodsBrand $goods_brand_model)
    {
        if(!empty($request->name)){
            $goods_brand_model = $goods_brand_model->where('name','like','%'.$request->name.'%');
        }
        $list = $goods_brand_model->orderBy('id','desc')->paginate($request->per_page??30);
        return $this->success(new GoodsBrandCollection($list));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,GoodsBrand $goods_brand_model)
    {
        $goods_brand_model->name = $request->name;
        $goods_brand_model->thumb = $request->thumb??'';
        $goods_brand_model->save();
        return $this->success([],__('base.success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(GoodsBrand $goods_brand_model,$id)
    {
        $info = $goods_brand_model->find($id);
        return $this->success($info);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,GoodsBrand $goods_brand_model, $id)
    {
        $goods_brand_model = $goods_brand_model->find($id);
        $goods_brand_model->name = $request->name;
        $goods_brand_model->thumb = $request->thumb??'';
        $goods_brand_model->save();
        return $this->success([],__('base.success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(GoodsBrand $goods_brand_model,$id)
    {
        $idArray = array_filter(explode(',',$id),function($item){
            return is_numeric($item);
        });
        $goods_brand_model->destroy($idArray);
        return $this->success([],__('base.success'));
    }

    // 品牌缩略图上传
    public function goods_brand_upload(UploadService $upload_service){
        $rs = $upload_service->goods_brand();
        if($rs['status']){
            return $this->success($rs['data'],$rs['msg']);
        }else{
            return $this->error($rs['msg']);
        }
    }
}
