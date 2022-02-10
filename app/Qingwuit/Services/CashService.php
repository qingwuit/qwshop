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
            if ($money>$info['money']) {
                return $this->formatError(__('tip.cash.moneyNotEnough'));
            }
            $data['user_id'] = $id;
        } else {
            $store = $this->getService('Store')->getStoreInfo()['data'];
            $id = $store['id'];
            if ($money>$store['store_money']) {
                return $this->formatError(__('tip.cash.moneyNotEnough'));
            }
            $data['store_id'] = $id;
        }
        $userCheck = $this->getService('UserCheck', true)->where('user_id', $id)->first();
        if (!$userCheck) {
            return $this->formatError(__('tip.cash.notCheck'));
        }
        if ($money<=0) {
            return $this->formatError(__('tip.cash.moneyZero'));
        }
        
        $data['name'] = $userCheck->name;
        $data['card_no'] = $userCheck->bank_no;
        $data['bank_name'] = $userCheck->bank_name;
        $data['money'] = $money;
        $data['remark'] = request()->remark??'';
        $data['refuse_info'] = '';
        try {
            DB::beginTransaction();
            $this->getService('Cash', true)->create($data);
            $this->getService('MoneyLog')->edit(['money'=>$money,'is_type'=>1,'name'=>'资金提现']);
            $this->getService('MoneyLog')->edit(['money'=>-$money,'is_type'=>0,'name'=>'资金提现']);
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
        $model->cash_status = request()->cash_status??0;
        $model->refuse_info = request()->refuse_info??'';
        $model->save();
        $this->getService('MoneyLog')->edit(['money'=>$model->money,'is_type'=>0,'name'=>'资金提现']);
        $this->getService('MoneyLog')->edit(['money'=>-$model->money,'is_type'=>1,'name'=>'资金提现']);
        return $this->format();
    }
}
