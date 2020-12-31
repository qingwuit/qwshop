<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Services\UploadService;
use App\Services\UserCheckService;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserCheckController extends Controller
{
    // 获取用户认证
    public function user_check(){
        $uc_service = new UserCheckService;
        $rs = $uc_service->getUserCheck();
        return $rs['status']?$this->success($rs['data']):$this->error($rs['msg']);
    }

    // 修改用户认证
    public function edit_user_check(){
        $uc_service = new UserCheckService;
        $rs = $uc_service->editUserCheck();
        return $rs['status']?$this->success($rs['data'],$rs['msg']):$this->error($rs['msg']);
    }

    // 图片上传
    public function user_check_upload(Request $request,UploadService $upload_service,UserService $user_service){
        $user_info = $user_service->getUserInfo();
        $name = $request->name??'';
        $rs = $upload_service->user_check($user_info['id']);
        if($rs['status']){
            return $this->success(['url'=>$rs['data'],'name'=>$name],$rs['msg']);
        }else{
            return $this->error($rs['msg']);
        }
    }
}
