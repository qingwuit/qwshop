<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\UploadService;
use Illuminate\Http\Request;
use App\Services\UserService;

class LoginController extends Controller
{
    public function login(UserService $user_service){
        $info = $user_service->login('username','admin');
        return $info['status']?$this->success($info['data']):$this->error($info['msg']);
    }

    // 检测是否登陆
    public function check_login(UserService $user_service){
        $info = $user_service->check_login('admin');
        return $info['status']?$this->success($info['data']):$this->error($info['msg']);
    }

    // 退出账号
    public function logout(){
        try{
            auth('admin')->logout();
        }catch(\Exception $e){
            return $this->success(__('base.success'));
        }
        return $this->success(__('base.success'));
    }

    // 测试接口
    public function test(){
        $upload = new UploadService;
        $file = $upload->goods();
        // $file = $upload->avatar(); // ,['filename'=>time()]
        // $file = $upload->uploadFile(); // ,['filename'=>time()]
        return $this->success($file);
    }
}
