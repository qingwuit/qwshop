<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Http\Resources\Home\UserResource\UserEditResource;
use App\Http\Resources\Home\UserResource\UserResource;
use App\Services\UserService;
use App\Models\UserCheck;
use App\Services\UploadService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function user_info(){
        $user_service = new UserService();
        $user_info = $user_service->getUserInfo();
        
        // 获取资料完成度
        $suc = 0;
        $all = 0;
        foreach($user_info as $v){
            if(!empty($v)){
                $suc++;
            }
            $all++;
        }
        $user_info['completion'] = intval($suc/$all*100);
        $user_info['user_check'] = UserCheck::where('user_id',$user_info['id'])->exists();
        $user_info = new UserResource($user_info);
        
        return $this->success($user_info);
    }

    public function edit_user(Request $request){
        $user_service = new UserService();
        if($request->isMethod('put')){
            $rs = $user_service->editUser();
            return $rs['status']?$this->success($rs['data'],__('users.edit_success')):$this->error(__('users.edit_error'));
        }
        $user_info = $user_service->getUserInfo();
        return $this->success(new UserEditResource($user_info));
    }

    // 图片上传
    public function avatar_upload(UploadService $upload_service){
        $user_service = new UserService();
        $user_info = $user_service->getUserInfo();
        $rs = $upload_service->avatar($user_info['id']);
        if($rs['status']){
            return $this->success($rs['data'],$rs['msg']);
        }else{
            return $this->error($rs['msg']);
        }
    }
}
