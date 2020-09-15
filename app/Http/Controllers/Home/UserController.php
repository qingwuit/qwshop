<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Http\Resources\Home\UserResource\UserResource;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function user_info(){
        $user_service = new UserService();
        $user_info = $user_service->getUserInfo();
        
        // 获取资料完成度
        $suc = 0;
        foreach($user_info as $v){
            if(!empty($v)){
                $suc++;
            }
        }
        $user_info = new UserResource($user_info);
        $user_info['completion'] = intval($suc/count($user_info)*100);
        return $this->success($user_info);
    }

    public function edit_user(){
        $user_service = new UserService();
        $rs = $user_service->editUser();
        return $rs['status']?$this->success([],__('users.edit_success')):$this->error(__('users.edit_error'));
    }
}
