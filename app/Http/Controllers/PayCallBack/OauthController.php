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
        if (!in_array($oauth_name, ['weixinmini', 'weixinminiphone'])) {
            $info = $this->getService('Configs')->getFormatConfig('oauth')[$oauth_name];
            $config = new \SocialiteProviders\Manager\Config($info['client_id'], $info['key'], $info['return_url'], []);
            $user = Socialite::driver($oauth_name)->setConfig($config)->stateless()->user(); // 无认证状态#
        } else {
            if ($oauth_name == 'weixinminiphone') {
                $wechatDecode = $this->getWechatPhone(); // 小程序手机登录
                if (!$wechatDecode['status']) return $this->error("wechat mini encode error.");
                $user = $wechatDecode['data'];
            } else {
                $user = request()->all();
            }
        }
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
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=";
        if ($device == 'mini') $url = "https://api.weixin.qq.com/sns/jscode2session?appid=";
        $appName = '';
        if (in_array($device, ['wap', 'mp'])) $appName = 'mp' . '_';
        if (in_array($device, ['mini'])) $appName = 'mini' . '_';
        $url = $url . $payCfg[$appName . 'app_id'] . '&secret=' . $payCfg['secret_key'] . '&' . (in_array($device, ['mini']) ? 'js_code' : 'code') . '=' . request('code') . '&grant_type=authorization_code';
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
            $accessTokenData = $this->getWechatAccessToken();
            $urls = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=' . $accessTokenData['data']['access_token'] . '&type=jsapi';
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

    // 获取微信小程序手机号插件
    public function getWechatPhone()
    {
        $accessTokenData = $this->getWechatAccessToken();
        if (!$accessTokenData['status']) return $this->formatError("GET AccessToken error.");

        // 验证获取openid
        $userProfile = $this->getOpenId();
        if (empty($userProfile['data']['openid']) || empty($userProfile['data']["session_key"])) {
            return $this->formatError('jscode 解析失败');
        }

        //解析电话
        $aes_key        = base64_decode($userProfile['data']["session_key"]);
        $aes_iv         = base64_decode(request('iv', ''));
        $aes_cipher     = base64_decode(request('encrypted_data', ''));
        $decrypted_data = openssl_decrypt($aes_cipher, "AES-128-CBC", $aes_key, 1, $aes_iv);
        $decode_data    = json_decode($decrypted_data, true);
        $phone          = $decode_data["purePhoneNumber"];
        $miniOpenid     = $userProfile['data']["openid"];
        $unionid        = $userProfile['data']["unionid"] ?? '';

        if (!$phone || !$miniOpenid) return $this->formatError("用户信息解析失败");
        return $this->format(['openid' => $miniOpenid, 'phone' => $phone, 'unionid' => $unionid, 'all' => $userProfile]);


        // 直接获取获取手机号码 根据CODE
        // $urls = "https://api.weixin.qq.com/wxa/business/getuserphonenumber?access_token=" . $accessTokenData['data']['access_token'] . '&code=' . request('code', '');
        // if (!empty(request('openid'))) $urls .= '&openid=' . request('openid');
        // $resp = $this->getService('Tool')->httpRequest($urls);
        // $resp = json_decode($resp['data'], true);
        // if ($resp['errcode'] != 0) return $this->formatError($resp['errmsg']);
        // // 手机号 phone_info.purePhoneNumber
        // return $this->format($resp);
    }

    // 获取access_token
    public function getWechatAccessToken()
    {
        $payment_name = request('payment_name', 'wechat');
        $device = request('device', 'mp');
        $pay = $this->getService('Configs')->getFormatConfig('pay');
        $payCfg = $pay[$payment_name . $device];
        $fileName = "access_token.txt";
        $appName = '';
        if (in_array($device, ['wap', 'mp'])) $appName = 'mp' . '_';
        if (in_array($device, ['mini'])) $appName = 'mini' . '_';
        if (!file_exists(storage_path($fileName)) || filemtime(storage_path($fileName)) < time() - 6000) {
            $urls = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . $payCfg[$appName . 'app_id'] . '&secret=' . $payCfg['secret_key'] . '&code=' . request('code');
            $resp = $this->getService('Tool')->httpRequest($urls);
            $resp = json_decode($resp['data'], true);
            $myfile = fopen(storage_path($fileName), 'w');
            //要写入文件的内容
            fwrite($myfile, $resp['access_token']);
            //关闭文件
            fclose($myfile);
        }
        $access_token = file_get_contents(storage_path($fileName));
        return $this->format($access_token);
    }
}
