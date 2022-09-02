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
        return $rs['status'] ? $this->success($rs['data']) : $this->error($rs['msg']);
    }

    // 微信获取用户Openid 根据Code
    public function getOpenId()
    {
        $payment_name = request('payment_name');
        $device = request('device', 'mp');
        $pay = $this->getService('Configs')->getFormatConfig('pay');
        $payCfg = $pay[$payment_name . $device];
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $payCfg[(in_array($device, ['wap', 'mp', 'mini']) ? 'mp' . '_' : '') . 'app_id'] . '&secret=' . $payCfg['secret_key'] . '&code=' . request('code') . '&grant_type=authorization_code';
        $resp = $this->getService('Tool')->httpRequest($url);
        $resp = json_decode($resp['data'], true);
        return $this->success($resp);
    }

    // 微信获取用户分享参数 根据Code
    public function getWechatJsapi()
    {
        $url = request('url', null);
        $fileName = 'jsapi_ticket.txt';
        $payment_name = request('payment_name', 'wechat');
        $device = request('device', 'mp');
        $pay = $this->getService('Configs')->getFormatConfig('pay');
        $payCfg = $pay[$payment_name . $device];
        if (!file_exists(storage_path($fileName)) || filemtime(storage_path($fileName)) < time() - 6000) {
            $urls = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . $payCfg[(in_array($device, ['wap', 'mp', 'mini']) ? 'mp' . '_' : '') . 'app_id'] . '&secret=' . $payCfg['secret_key'] . '&code=' . request('code');
            $resp = $this->getService('Tool')->httpRequest($urls);
            $resp = json_decode($resp['data'], true);
            $urls = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=' . $resp['access_token'] . '&type=jsapi';
            $resp = $this->getService('Tool')->httpRequest($urls);
            $resp = json_decode($resp['data'], true);
            $myfile = fopen(storage_path($fileName), 'w');
            //要写入文件的内容
            fwrite($myfile, $resp['ticket']);
            //关闭文件
            fclose($myfile);
        }
        $ticket = file_get_contents(storage_path($fileName));
        $nowTime = time();
        $data = [
            'noncestr'  =>  'Wm3WZYTPz0wzccnW',
            'jsapi_ticket'  =>  $ticket,
            'timestamp'  =>  $nowTime,
            'url'  =>  $url,
        ];
        ksort($data);
        $dataStr = $this->getService('Tool')->ToUrlParams($data);
        $sign = sha1($dataStr);
        $signPackage = array(
            'appId' => $payCfg[(in_array($device, ['wap', 'mp', 'mini']) ? 'mp' . '_' : '') . 'app_id'],
            'nonceStr' => 'Wm3WZYTPz0wzccnW',
            'timestamp' => $nowTime,
            'signature' => $sign,
            'str'   => $dataStr
        );
        return $this->success($signPackage);
    }
}
