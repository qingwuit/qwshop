<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Services\SmsService;
use Illuminate\Http\Request;
use App\Services\UserService;

class LoginController extends Controller
{
    public function login(UserService $user_service){
        $info = $user_service->login('phone');
        return $info['status']?$this->success($info['data']):$this->error($info['msg']);
    }

    // 第三方登录
    public function auth_login(){
        $user_service = new UserService();
        $info = $user_service->oauthLogin(request()->all(),request()->oauth_name);
        return $info['status']?$this->success($info['data']):$this->error($info['msg']);
    }

    // 检测是否登陆
    public function check_login(UserService $user_service){
        $info = $user_service->checkLogin('user');
        return $info['status']?$this->success($info['data']):$this->error($info['msg']);
    }

    // 注册
    public function register(){
        $user_service = new UserService();
        $rs = $user_service->register('phone');
        return $rs['status']?$this->success($rs['data'],$rs['msg']):$this->error($rs['msg']);
    }

    // 找回密码
    public function forget_password(){
        $user_service = new UserService();
        $rs = $user_service->forgetPassword('phone');
        return $rs['status']?$this->success($rs['data'],$rs['msg']):$this->error($rs['msg']);
    }

    // 发送短信
    public function send_sms(Request $request){
        $sms_service = new SmsService();
        $rs = $sms_service->sendSms($request->phone,$request->name);
        return $rs['status']?$this->success($rs['data'],$rs['msg']):$this->error($rs['msg']);
    }

    // 退出账号
    public function logout(){
        try{
            auth('user')->logout();
        }catch(\Exception $e){
            return $this->success([],__('base.success'));
        }
        return $this->success([],__('base.success'));
    }
}
