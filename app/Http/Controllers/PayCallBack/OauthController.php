<?php

namespace App\Http\Controllers\PayCallBack;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class OauthController extends Controller
{
    /**
     * 将用户重定向到 第三方 授权页面
     *
     * @return \Illuminate\Http\Response
     */
    public function oauth($payment_name)
    {
        $info = $this->getService('Configs')->getFormatConfig('oauth')[$payment_name];
        $config = new \SocialiteProviders\Manager\Config($info['client_id'], $info['key'], $info['return_url'], []);
        return Socialite::driver($payment_name)->setConfig($config)->redirect();
    }

    /**
     * 从 第三方 获取用户信息 传code到此页面即可，post get都行 这里返回登录Token
     *
     * @return \Illuminate\Http\Response
     */
    public function oauthCallback($oauth_name)
    {
        $info = $this->getService('Configs')->getFormatConfig('oauth')[$oauth_name];
        $config = new \SocialiteProviders\Manager\Config($info['client_id'], $info['key'], $info['return_url'], []);
        $user = Socialite::driver($oauth_name)->setConfig($config)->stateless()->user(); // 无认证状态#
        $rs = $this->getService('Auth')->oauthLogin($user, $oauth_name);
        return $rs['status']?$this->success($rs['data']):$this->error($rs['msg']);
    }
}
