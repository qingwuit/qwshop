<?php

namespace App\Http\Controllers\PayCallBack;

use App\Http\Controllers\Controller;
use App\Services\ConfigService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use SocialiteProviders\WeixinWeb\Provider;

class OauthController extends Controller
{
    /**
     * 将用户重定向到 第三方 授权页面
     *
     * @return \Illuminate\Http\Response
     */
    public function oauth($payment_name){
        $config_service = new ConfigService();
        $info = $config_service->getFormatConfig('oauth')[$payment_name];
        $config = new \SocialiteProviders\Manager\Config($info['client_id'], $info['client_secret'], $info['redirect'], []);
        return Socialite::driver($payment_name)->setConfig($config)->redirect();
    }


    /**
     * 从 第三方 获取用户信息 传code到此页面即可，post get都行 这里返回登录Token
     *
     * @return \Illuminate\Http\Response
     */
    public function oauthCallback($oauth_name)
    {
        $config_service = new ConfigService();
        $info = $config_service->getFormatConfig('oauth')[$oauth_name];
        $config = new \SocialiteProviders\Manager\Config($info['client_id'], $info['client_secret'], $info['redirect'], []);
        $user = Socialite::driver($oauth_name)->setConfig($config)->stateless()->user(); // 无认证状态#
        $user_service = new UserService();
        $rs = $user_service->oauthLogin($user,$oauth_name);
        return $rs['status']?$this->success($rs['data']):$this->error($rs['msg']);

        // $user->token;
    }

}
