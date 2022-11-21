<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    protected $modelName = 'User';

    // 添加
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            // 判断是否存在相同得账号
            if ($this->getService($this->modelName, true)->where('username', $request->username)->exists()) return $this->formatError(__('tip.userNameExist'));
            $this->getService($this->modelName, true)
                ->create([
                    'username' => $request->username ?? '',
                    'phone' => $request->phone ?? '',
                    'inviter_id' => $request->inviter_id ?? 0,
                    'password' => Hash::make(isset($request->password) && !empty($request->password) ? $request->password : '123456'),
                    'pay_password' => Hash::make(isset($request->pay_password) && !empty($request->pay_password) ? $request->pay_password : '123456'),
                    'nickname' => $request->nickname ?? 'Mysterious',
                    'avatar' => $request->avatar ?? '',
                ]);
            DB::commit();
            return $this->success([], __('tip.success'));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    // 显示
    public function show($id)
    {
        $rs = $this->getService($this->modelName, true)->find($id);
        unset($rs['password'], $rs['pay_password']);
        return $this->success($rs, __('tip.success'));
    }

    // 修改
    public function update(Request $request, $id)
    {
        try {
            // 判断是否存在相同得账号
            if ($this->getService($this->modelName, true)->where('username', $request->username)->exists()) return $this->formatError(__('tip.userNameExist'));
            $model = $this->getService($this->modelName, true)->find($id);
            $model->username = $request->username;
            if (!empty($request->password)) {
                $model->password = Hash::make($request->password);
            }
            if (!empty($request->pay_password)) {
                $model->pay_password = Hash::make($request->pay_password);
            }
            $model->nickname = $request->nickname ?? '';
            $model->phone = $request->phone ?? '';
            $model->avatar = $request->avatar ?? '';
            $model->inviter_id = $request->inviter_id ?? 0;
            $model->save();
            DB::commit();
            return $this->success([], __('tip.success'));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    // 用户资金处理
    public function money(Request $request)
    {
        if (empty($request->id)) return $this->success();
        return $this->handle($this->getService('MoneyLog')->edit([
            'money' => $request->money ?? 1,
            'is_type' => $request->is_type ?? 0,
            'user_id' => $request->id ?? 0,
            'is_belong' => $request->is_belong ?? 0,
            'name' => __('tip.systemHandleMoney'),
        ]));
    }
}
