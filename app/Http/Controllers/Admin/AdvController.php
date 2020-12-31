<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\AdvResource\AdvCollection;
use App\Models\Adv;
use App\Services\UploadService;
use Illuminate\Http\Request;

class AdvController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,Adv $adv_model)
    {
        if(!empty($request->name)){
            $adv_model = $adv_model->where('ap_name','like','%'.$request->name.'%');
        }
        if($request->ap_id != ''){
            $adv_model = $adv_model->where('ap_id','like','%'.$request->ap_id.'%');
        }
        $list = $adv_model->orderBy('id','desc')->paginate($request->per_page??30);
        return $this->success(new AdvCollection($list));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Adv $adv_model)
    {
        $adv_model->ap_id = $request->ap_id??0;
        $adv_model->name = $request->name??'';
        $adv_model->url = $request->url??'';
        $adv_model->image_url = $request->image_url??'';
        $adv_model->adv_start = $request->adv_start;
        $adv_model->adv_end = $request->adv_end;
        $adv_model->adv_sort = $request->adv_sort??0;
        $adv_model->adv_type = $request->adv_type??0;
        $adv_model->save();
        return $this->success([],__('base.success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Adv $adv_model,$id)
    {
        $info = $adv_model->find($id);
        return $this->success($info);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Adv $adv_model, $id)
    {
        $adv_model = $adv_model->find($id);
        $adv_model->ap_id = $request->ap_id??0;
        $adv_model->name = $request->name??'';
        $adv_model->url = $request->url??'';
        $adv_model->image_url = $request->image_url??'';
        $adv_model->adv_start = $request->adv_start;
        $adv_model->adv_end = $request->adv_end;
        $adv_model->adv_sort = $request->adv_sort??0;
        $adv_model->adv_type = $request->adv_type??0;
        $adv_model->save();
        return $this->success([],__('base.success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Adv $adv_model,$id)
    {
        $idArray = array_filter(explode(',',$id),function($item){
            return is_numeric($item);
        });
        $adv_model->destroy($idArray);
        return $this->success([],__('base.success'));
    }

    // 广告图上传
    public function adv_upload(UploadService $upload_service){
        $rs = $upload_service->adv();
        if($rs['status']){
            return $this->success($rs['data'],$rs['msg']);
        }else{
            return $this->error($rs['msg']);
        }
    }
}
