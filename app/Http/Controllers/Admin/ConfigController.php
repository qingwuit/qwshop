<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Services\ConfigService;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConfigController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ConfigService $config_service)
    {
        $list = $config_service->getFormatConfig();
        return $this->success($list);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Config $config_model)
    {
        $data = $request->all();
        try{
            DB::beginTransaction();
            foreach($data as $k=>$v){
                if($v == null){
                    continue;
                }
                $config_model->where('name',$k)->update(['val'=>$v]);
            }
            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
            return $this->error($e->getMessage());
        }
        
        return $this->success([],__('base.success'));
    }

    // Logo图上传
    public function config_logo(UploadService $upload_service){
        $rs = $upload_service->config_logo();
        if($rs['status']){
            return $this->success($rs['data'],$rs['msg']);
        }else{
            return $this->error($rs['msg']);
        }
    }

    // Icon图上传
    public function config_icon(UploadService $upload_service){
        $rs = $upload_service->config_icon();
        if($rs['status']){
            return $this->success($rs['data'],$rs['msg']);
        }else{
            return $this->error($rs['msg']);
        }
    }

}
