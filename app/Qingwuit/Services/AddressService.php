<?php
namespace App\Qingwuit\Services;

class AddressService extends BaseService
{
    public $modelName = 'Address';
    public $auth = 'users';
    public $belongName = 'user_id';

    public function add()
    {
        $area = $this->getService('Area', true)->select('name')->whereIn('id', request('area'))->get();
        $areaInfo = '';
        if (!empty($area)) {
            foreach ($area as $v) {
                $areaInfo .= $v['name'].' ';
            }
        }
        $areaInfo = rtrim($areaInfo, ' ');
        $userId = $this->getUserId('users');
        $data = [
            'user_id'  =>  $userId,
            'receive_name'  =>  request()->receive_name??'',
            'receive_tel'  =>  request()->receive_tel??'',
            'province_id'  =>  request()->area[0]??0,
            'city_id'  =>  request()->area[1]??0,
            'region_id'  =>  request()->area[2]??0,
            'address'  =>  request()->address??'',
            'area_info' =>  $areaInfo,
            'is_default'  =>  request()->is_default?1:0,
        ];
        try {
            if ($data['is_default']) {
                $this->getService($this->modelName, true)->where('user_id', $userId)->update(['is_default'=>0]);
            }
            $rs = $this->getService($this->modelName, true)->create($data);
            return $this->format($rs);
        } catch (\Exception $e) {
            return $this->formatError($e->getMessage());
        }
    }

    public function edit($id)
    {
        $area = $this->getService('Area', true)->select('name')->whereIn('id', request()->area)->get();
        $areaInfo = '';
        if (!empty($area)) {
            foreach ($area as $v) {
                $areaInfo .= $v['name'].' ';
            }
        }
        $areaInfo = rtrim($areaInfo, ' ');
        $userId = $this->getUserId('users');
        $data = [
            'receive_name'  =>  request()->receive_name??'',
            'receive_tel'  =>  request()->receive_tel??'',
            'province_id'  =>  request()->area[0]??0,
            'city_id'  =>  request()->area[1]??0,
            'region_id'  =>  request()->area[2]??0,
            'address'  =>  request()->address??'',
            'area_info' =>  $areaInfo,
            'is_default'  =>  request()->is_default?1:0,
        ];
        try {
            if ($data['is_default']) {
                $this->getService($this->modelName, true)->where('user_id', $userId)->update(['is_default'=>0]);
            }
            $rs = $this->getService($this->modelName, true)->where('id', $id)->where($this->belongName, $userId)->update($data);
            return $this->format($rs);
        } catch (\Exception $e) {
            return $this->formatError($e->getMessage());
        }
    }

    public function setDefault($id)
    {
        $userId = $this->getUserId('users');
        try {
            $this->getService($this->modelName, true)->where($this->belongName, $userId)->update(['is_default'=>0]);
            $rs = $this->getService($this->modelName, true)->where('id', $id)->where($this->belongName, $userId)->update(['is_default'=>1]);
            return $this->format($rs);
        } catch (\Exception $e) {
            return $this->formatError($e->getMessage());
        }
    }

    // 获取默认地址
    public function getDefault()
    {
        // 获取当前用户user_id
        $userId = $this->getUserId('users');
        $address = $this->getService('Address', true)->where('user_id', $userId)->where('is_default', 1)->first();

        if (empty($address)) {
            $address =  $this->getService('Address', true)->where('user_id', $userId['id'])->first();
        }

        return $this->format($address, __('tip.success'));
    }
}
