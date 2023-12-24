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
        $provider = request('provider', 'users'); // 用户类型 User Admin
        $grantType = request('grant_type', 'password'); // 登录方式
        $clientType = request('client_type', 'password_client'); // 客户端类型默认密码类型得会过期
        $username = request('username', '');
        $password = request('password', '');
        $type = request('type', 'username'); // 登录方式 phone email
        $code = request('code'); // 手机登录验证码
        // $phone = request()->phone;

        if ($type == 'phone') {
            $smsCheck = $this->getService('Sms')->checkSms($username, $code);
            if (!$smsCheck['status']) {
                return $this->formatError($smsCheck['msg']);
            }
        }

        // 总后台要做滑块验证
        if ($provider == 'admins') {
            $capCheck = $this->getService('Captcha')->checkCap();
            if (!$capCheck['status']) {
                return $this->formatError($capCheck['msg']);
            }
        }

        // 外部API请求登录
        $where = ['password_client' => 1, 'personal_access_client' => 0, 'provider' => $provider];
        // 个人客户端无限时间
        if ($clientType == 'personal_access_client') {
            $where['password_client'] = 0;
            $where['personal_access_client'] = 1;
        }
        if (!$apiLogin) {
            // 内部登录获取token
            $username = $loginData['username'];
            $password = $loginData['password'];
            $provider = $loginData['provider'] ?? $provider;
            $grantType = $loginData['grantType'] ?? $grantType;
            $clientType = $loginData['clientType'] ?? $clientType;
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
            'password'      =>  $password,
            'username'      =>  $username,
            'scope'         =>  '',
        ];
        $respData[$type] = $username;

        try {
            // 如果因为负载均衡反向代理导致url() 链接https变成http 参考 https://learnku.com/articles/67283
            $resp = Http::asForm()->withOptions(['verify' => false])->post(url('/oauth/token'), $respData);
        } catch (\Exception $e) {
            if (empty($e->getCode())) $resp = Http::asForm()->post('nginx/oauth/token', $respData);
            if (!empty($e->getCode())) return $this->formatError($e->getMessage(), ['code' => $e->getCode(), 'file' => $e->getFile(), 'line' => $e->getLine()]);
        }


        if ($resp->getStatusCode() == 200) {

            // 登录成功修改登录时间
            $authModel = $this->getService(rtrim(ucfirst($provider), 's'), true);
            if ($provider !== 'admins') $authModel = $authModel->orWhere('phone', $username);
            $authModel = $authModel->orWhere('username', $username)->first();
            $authModel->last_login_time = !empty($authModel->login_time) ? $authModel->login_time : now();
            $authModel->login_time = now();
            $authModel->save();

            return $this->format($resp->json());
        }
        if (isset($resp->json()['error']) && $resp->json()['error'] == 'invalid_grant') return $this->formatError(__('tip.userErr'));
        return $this->formatError($resp->json()['message'], $respData);
    }


    // 注册服务
    public function register()
    {
        $username = request('username');
        $password = request('password');
        $code = request('code'); // 短信验证码
        $type = request('type') ?? 'username'; // 注册方式 phone email

        $provider = request('provider') ?? 'users'; // 用户类型 users | admins
        $modelName = ucwords(rtrim($provider, 's'));

        if ($provider == 'admins') {
            return;
        }

        $model = $this->getService($modelName, true);

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
            'phone'     =>  $type == 'phone' ? $username : '',
            'email'     =>  $type == 'email' ? $username : '',
            'password'  =>  Hash::make($password),
            'pay_password'  =>  Hash::make('123456'),
            'belong_id' =>  0,
        ];

        if (!empty(request('inviter_id'))) {
            $regData['inviter_id'] = request('inviter_id');
        }

        // 如果使用队列来处理高并发问题 下面推送到队列
        if (env('APP_REDIS')) {
            $this->getJob('Register', ['model_name' => $modelName, 'register_type' => $type, 'reg_data' => $regData])->onQueue('register');
            return $this->format([], 'Queue executing.');
        } else {
            if (!$model->create($regData)) return $this->formatError(__('tips.error'));
        }

        return $this->login(false, ['username' => $username, 'password' => $password, 'provider' => $provider]);
    }

    // 第三方登录 $oauth 第三方返回账号信息 $oauthName 第三方插件名称
    public function oauthLogin($oauth = null, $oauthName = 'weixinweb')
    {
        // throw new \Exception(__('tip.oauthThr'));
        try {
            DB::beginTransaction();
            $modelName = request()->model_name ?? 'User';
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
                    if(empty($oauth['nickname'])) $oauth['nickname'] = 'mini_' . mt_rand(1000, 9999);
                    break;
                case 'weixinminiphone': // 微信小程序手机号
                    if(empty($oauth['nickname'])) $oauth['nickname'] = 'mini_p_' . mt_rand(1000, 9999);
                    if (isset($oauth['phone']) && !empty($oauth['phone'])) {
                        // 查询是否存在该手机的账户
                        $user = $this->getService($modelName, true)->where('username', $oauth['phone'])->first();
                        if (!empty($user)) {
                            $data = [
                                'access_token'      =>  $user->createToken('token')->accessToken,
                                'expires_in'        =>  0,
                                'refresh_token'     =>  '',
                                'token_type'        =>  'Bearer',
                                'openid'            =>  $oauth['openid'] ?? '',
                            ];
                            DB::commit();
                            return $this->format($data);
                        }
                    }
                    break;
            }
            if (substr($oauthName, 0, 6) == 'weixin') {
                $uwInfo = $this->getService('Oauth', true);
                if (!empty($oauth['unionid'])) {
                    $uwInfo = $uwInfo->where('unionid', $oauth['unionid'])->first();
                } else {
                    $uwInfo = $uwInfo->where('openid', $oauth['openid'])->first();
                }
                if (!$uwInfo) {
                    $randName = 'wx' . date('Ymd') . mt_rand(100, 999);
                    $userData = [
                        'nickname'      =>  $isArr ? $oauth['nickname'] : $oauth->nickname,
                        'username'      =>  $oauth['phone'] ?? $randName,
                        'phone'         =>  '',
                        'email'         =>  '',
                        'password'      =>  Hash::make('123456'),
                        'pay_password'  =>  Hash::make('123456'),
                        'inviter_id'    =>  request('inviter_id', 0),
                        'belong_id'     =>  0,
                        'created_at'    =>  now(),
                        'updated_at'    =>  now(),
                    ];
                    $userId = $this->getService($modelName, true)->insertGetId($userData);
                    $oauthData = [
                        'openid'        =>  $isArr ? $oauth['openid'] : $oauth->openid,
                        'model_name'    =>  $modelName,
                        'oauth_name'    =>  $oauthName,
                        'nickname'      =>  $isArr ? ($oauth['nickname'] ?? '') : $oauth->nickname,
                        'belong_id'     =>  $userId,
                        'unionid'       =>  $isArr ? ($oauth['unionid'] ?? '') : $oauth->unionid,
                        'headimgurl'    =>  ($isArr ? ($oauth['avatar'] ?? '') : $oauth->avatar) ?? '',
                        'created_at'    =>  now(),
                        'updated_at'    =>  now(),
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
            if(!empty($oauth['openid'])) $data['openid'] = $oauth['openid'];
            DB::commit();
            return $this->format($data);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->formatError($e->getMessage());
        }
    }

    // 找回密码服务
    public function forgetPassword()
    {
        $username = request('username');
        $password = request('password');
        $code = request('code'); // 短信验证码
        $type = request('type') ?? 'username'; // 注册方式 phone email

        $provider = request('provider') ?? 'users'; // 用户类型 users | admins
        $modelName = ucwords(rtrim($provider, 's'));

        if ($provider == 'admins') {
            return;
        }

        $model = $this->getService($modelName, true)->where($type, $username)->first();

        // 判断是否存在相同得账号和电话
        if (!$model->where($type, $username)->exists()) {
            // 该账号已经存在
            return $this->formatError(__('tip.userThr'));
        }

        if ($type == 'phone') {
            $smsCheck = $this->getService('Sms')->checkSms($username, $code, 'forget_password');
            if (!$smsCheck['status']) {
                return $this->formatError($smsCheck['msg']);
            }

            $model->password = Hash::make($password);
            $model->save();
        }

        return $this->login(false, ['username' => $username, 'password' => $password, 'provider' => $provider]);
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
