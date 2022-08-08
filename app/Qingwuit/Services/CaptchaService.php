<?php

namespace App\Qingwuit\Services;

use Illuminate\Support\Facades\Hash;

class CaptchaService extends BaseService
{
    protected $key = 'qwit';
    public function createCap()
    {
        $username = request('username', '');
        $password = request('username', '');
        $step = request('step', 0);
        $ip = request()->getClientIp();
        $sign = Hash::make($username . $password . $step . $this->key);
        $this->getService('Captcha', true)->create([
            'username'  =>  $username,
            'password'  =>  $password,
            'step'      =>  $step,
            'ip'        =>  $ip,
            'sign'      =>  $sign,
        ]);
        return $this->format($sign);
    }

    // 验证
    public function checkCap()
    {
        $username = request('username', '');
        $password = request('username', '');
        $sign = request('sign', '');
        $cap = $this->getService('Captcha', true)->where('username', $username)->where('password', $password)->where('sign', $sign)->first();
        if (!$cap) return $this->formatError(__('tip.oauthThr'));
        return $this->format();
    }
}
