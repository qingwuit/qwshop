<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

// use App\Services\AuthService;

class AuthController extends Controller
{
    // 登录
    public function login()
    {
        $rs = $this->getService('Auth')->login();
        return $this->handle($rs);
    }

    // 注册
    public function register()
    {
        $rs = $this->getService('Auth')->register();
        return $this->handle($rs);
    }

    // 退出账号
    public function logout()
    {
        return $this->success($this->getService('Auth')->logout());
    }

    // 获取用户信息
    public function info(Request $request)
    {
        $info = $this->getUser($request->provider);
        if ($info['status']) {
            if (isset($info['data']['password'])) {
                unset($info['data']['password']);
            }
            if (isset($info['data']['pay_password'])) {
                unset($info['data']['pay_password']);
            }
        }
        $pro = lcfirst(str_replace('api/', '', $request->route()->action['prefix']));
        if ($request->provider != 'users') {
            $defaultUrl = $this->getService($pro.'Menu', true)->whereRaw('apis!=""')->orderBy('is_sort', 'asc')->first();
            if ($defaultUrl) {
                $info['data']['defaultUrl'] = $defaultUrl->apis??'/';
            }
        }
        return $this->handle($info);
    }

    // 账号编辑
    public function edit(Request $request)
    {
        $data = $request->except('provider');
        $id = $this->getUserId($request->provider);
        $pro = lcfirst(str_replace('api/', '', $request->route()->action['prefix']));
        if ($pro != 'Admin') {
            $pro = 'User';
        }
        if (!isset($data['password']) || empty($data['password'])) {
            unset($data['password']);
        }
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        if (!isset($data['pay_password']) || empty($data['pay_password'])) {
            unset($data['pay_password']);
        }
        if (isset($data['pay_password'])) {
            $data['pay_password'] = Hash::make($data['pay_password']);
        }

        // 修改手机号码
        if (isset($data['phone']) && !empty($data['phone']) && $pro == 'User') {
            if ($this->getService($pro, true)->whereNotIn('id', [$id])->where('phone', $data['phone'])->exists()) {
                return $this->error(__('tip.phoneExist'));
            }
            $sms = $this->getService('Sms')->checkSms($data['phone'], $data['code']);
            if (!$sms['status']) {
                return $this->error($sms['msg']);
            } else {
                unset($data['code']);
            }
        }
        
        $rs = $this->getService($pro, true)->where('id', $id)->update($data);
        return $this->success($rs);
    }
}
