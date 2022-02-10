<?php
namespace App\Qingwuit\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class AuthService extends BaseService
{
    

    // 登录服务
    public function login($apiLogin = true, $loginData = [])
    {
        $provider = request('provider')??'users'; // 用户类型 User Admin
        $grantType = request('grant_type')??'password'; // 登录方式
        $clientType = request('client_type')??'password_client'; // 客户端类型默认密码类型得会过期
        $username = request('username');
        $password = request('password');
        // $phone = request()->phone;

        // 外部API请求登录
        $where = ['password_client'=>1,'personal_access_client'=>0,'provider'=>$provider];
        // 个人客户端无限时间
        if ($clientType == 'personal_access_client') {
            $where['password_client'] = 0;
            $where['personal_access_client'] = 1;
        }
        if (!$apiLogin) {
            // 内部登录获取token
            $username = $loginData['username'];
            $password = $loginData['password'];
            $provider = $loginData['provider']??$provider;
            $grantType = $loginData['grantType']??$grantType;
            $clientType = $loginData['clientType']??$clientType;
        }


        $client = DB::table('oauth_clients')
            ->select('id', 'secret', 'user_id', 'redirect')
            ->where($where)
            ->first();

        if (!$client) {
            return $this->formatError(__('tip.oauthThr'));
        }

        $respData = [
            'grant_type'    =>  $grantType,
            'client_id'     =>  $client->id,
            'client_secret' =>  $client->secret,
            'username'      =>  $username,
            'password'      =>  $password,
            'scope'         =>  '',
        ];
        $resp = Http::asForm()->post(url('/oauth/token'), $respData);
        
        if ($resp->getStatusCode() == 200) {
            return $this->format($resp->json());
        }
        return $this->formatError($resp->json()['message'], $respData);
    }


    // 注册服务
    public function register()
    {
        $username = request('username');
        $password = request('password');
        $code = request('code'); // 短信验证码
        $type = request('type')??'username'; // 注册方式 phone email

        $provider = request('provider')??'users'; // 用户类型 users | admins
        $modelName = ucwords(rtrim($provider, 's'));

        if ($provider == 'admins') {
            return;
        }
        
        $model = app()->make('App\Qingwuit\Models\\'.$modelName);
        
        // 判断是否存在相同得账号和电话
        if ($model->where($type, $username)->exists()) {
            // 该账号已经存在
            return $this->formatError(__('tip.userExist'));
        }

        if ($type == 'phone') {
            $smsCheck = $this->getService('Sms')->checkSms($username, $code);
            if (!$smsCheck['status']) {
                return $this->formatError($smsCheck['msg']);
            }
        }
        
        $regData = [
            'nickname'  =>  $username,
            'username'  =>  $username,
            'phone'     =>  $type=='phone'?$username:'',
            'email'     =>  $type=='email'?$username:'',
            'password'  =>  Hash::make($password),
            'pay_password'  =>  Hash::make('123456'),
            'belong_id' =>  0,
        ];

        if (!empty(request('inviter_id'))) {
            $regData['inviter_id'] = request('inviter_id');
        }

        if (!$model->create($regData)) {
            return; // 账号建立失败
        }

        return $this->login(false, ['username'=>$username,'password'=>$password,'provider'=>$provider]);
    }

    // 第三方登录 $oauth 第三方返回账号信息 $oauthName 第三方插件名称
    public function oauthLogin($oauth=null, $oauthName='weixinweb')
    {
        // throw new \Exception(__('tip.oauthThr'));
        try {
            DB::beginTransaction();
            $modelName = request()->model_name??'User';
            $isArr = is_array($oauth);
            $userId = 0;
            $oauthData = [];
            switch ($oauthName) {
                case 'weixinweb': // 微信PC
                break;
                case 'weixin': // 微信H5
                break;
                case 'weixinapp': // 微信app
                break;
                case 'weixinmini': // 微信小程序
                break;
            }
            if (substr($oauthName, 0, 6) == 'weixin') {
                $uwInfo = $this->getService('Oauth', true)->where('unionid', $oauth['unionid'])->first();
                if (!$uwInfo) {
                    $randName = 'wx'.date('Ymd').mt_rand(100, 999);
                    $userData = [
                        'nickname'      =>  $isArr?$oauth['nickname']:$oauth->nickname,
                        'username'      =>  $randName,
                        'phone'         =>  '',
                        'email'         =>  '',
                        'password'      =>  Hash::make('123456'),
                        'pay_password'  =>  Hash::make('123456'),
                        'inviter_id'    =>  request('inviter_id', 0),
                        'belong_id'     =>  0,
                        'created_at'    =>  now(),
                    ];
                    $userId = $this->getService($modelName, true)->insertGetId($userData);
                    $oauthData = [
                        'openid'        =>  $isArr?$oauth['openid']:$oauth->openid,
                        'model_name'    =>  $modelName,
                        'oauth_name'    =>  $oauthName,
                        'nickname'      =>  $isArr?$oauth['nickname']:$oauth->nickname,
                        'belong_id'     =>  $userId,
                        'unionid'       =>  $isArr?$oauth['unionid']:$oauth->unionid,
                        'headimgurl'    =>  ($isArr?$oauth['avatar']:$oauth->avatar)??'',
                        'created_at'    =>  now(),
                    ];
                    $this->getService('Oauth', true)->create($oauthData);
                } else {
                    $userId = $uwInfo['belong_id'];
                }
            }
            $token = $this->getService($modelName, true)->find($userId)->createToken('token')->accessToken;
            $data = [
                'access_token'      =>  $token,
                'expires_in'        =>  0,
                'refresh_token'     =>  '',
                'token_type'        =>  'Bearer',
            ];
            DB::commit();
            return $this->format($data);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->formatError($e->getMessage());
        }
    }

    // 退出账号
    public function logout()
    {
        $provider = request()->provider; // users | admins
        if (auth($provider)->check()) {
            auth($provider)->user()->token()->delete();
        }
        return $this->format([], 'ok');
    }
}
