<?php

namespace App\Qingwuit\Services;

use Illuminate\Support\Facades\DB;

class CashService extends BaseService
{
    public function add($whereName = 'user_id')
    {
        $data = [];
        $money = floatval(abs(request('money')));
        if ($whereName == 'user_id') {
            $info = $this->getUser('users')['data'];
            $id = $info['id'];
            if ($money > $info['money']) {
                return $this->formatError(__('tip.cash.moneyNotEnough'));
            }
            $data['user_id'] = $id;
        } else {
            $store = $this->getService('Store')->getStoreInfo()['data'];
            $id = $store['id'];
            if ($money > $store['store_money']) {
                return $this->formatError(__('tip.cash.moneyNotEnough'));
            }
            $data['store_id'] = $id;
        }
        $userCheck = $this->getService('UserCheck', true)->where('user_id', $id)->first();
        if (!$userCheck) {
            return $this->formatError(__('tip.cash.notCheck'));
        }
        if ($money <= 0) {
            return $this->formatError(__('tip.cash.moneyZero'));
        }

        $storeConfig = $this->getService('Configs')->getFormatConfig('store');
        $cashRate = !isset($storeConfig['cash']) && empty($storeConfig['cash']) ? 0 : round(floatval($storeConfig['cash']) / 100, 2);

        $data['name'] = $userCheck->name;
        $data['card_no'] = $userCheck->bank_no;
        $data['bank_name'] = $userCheck->bank_name;
        $data['commission'] = bcmul($money, $cashRate, 2);
        $data['money'] = bcsub($money, $data['commission'], 2);
        $data['remark'] = request()->remark ?? '';
        $data['refuse_info'] = '';
        try {
            DB::beginTransaction();
            $this->getService('Cash', true)->create($data);
            $this->getService('MoneyLog')->edit(['money' => $money, 'is_type' => 1, 'name' => '资金提现']);
            $this->getService('MoneyLog')->edit(['money' => -$money, 'is_type' => 0, 'name' => '资金提现']);
            DB::commit();
            return $this->format();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->formatError($e->getMessage());
        }
    }

    // 编辑提现状态
    public function edit($id)
    {
        $model = $this->getService('Cash', true)->where('id', $id)->first();
        if (!empty(request()->cash_status ?? 0)) $model->cash_status = abs(intval(request()->cash_status));
        $model->refuse_info = request()->refuse_info ?? '';
        $model->remark = request()->remark ?? '';
        $model->save();

        switch ($model->cash_status) {
            case 1:
                if ($model->user_id > 0) $this->getService('MoneyLog')->edit(['money' => -bcadd($model->money, $model->commission, 2), 'user_id' => $model->user_id, 'is_type' => 1, 'name' => '提现成功']);
                if ($model->store_id > 0) $this->getService('MoneyLog')->edit(['money' => -bcadd($model->money, $model->commission, 2), 'user_id' => $model->store_id, 'is_type' => 1, 'is_belong' => 1, 'name' => '提现成功']);
                break;
            case 2:
                if ($model->user_id > 0){
                    $this->getService('MoneyLog')->edit(['money' => bcadd($model->money, $model->commission, 2), 'user_id' => $model->user_id, 'is_type' => 0, 'name' => '拒绝提现']);
                    $this->getService('MoneyLog')->edit(['money' => -bcadd($model->money, $model->commission, 2), 'user_id' => $model->user_id, 'is_type' => 1, 'name' => '拒绝提现']);
                }
                if ($model->store_id > 0) {
                    $this->getService('MoneyLog')->edit(['money' => bcadd($model->money, $model->commission, 2), 'user_id' => $model->store_id, 'is_type' => 0, 'is_belong' => 1, 'name' => '拒绝提现']);
                    $this->getService('MoneyLog')->edit(['money' => -bcadd($model->money, $model->commission, 2), 'user_id' => $model->store_id, 'is_type' => 1, 'is_belong' => 1, 'name' => '拒绝提现']);
                }
                break;
        }
        return $this->format();
    }
}
