<?php
namespace App\Qingwuit\Services;

class UserCheckService extends BaseService
{
    public function check()
    {
        $userId = $this->getUserId('users');
        $info = $this->getService('UserCheck', true)->where('user_id', $userId)->first();
        return $this->format($info);
    }

    public function edit()
    {
        $data = request()->only(['name','card_no','card_t','card_b','bank_no','bank_name']);
        $userId = $this->getUserId('users');
        $model = $this->getService('UserCheck', true);
        $info = $model->where('user_id', $userId)->exists();
        $data['user_id'] = $userId;
        if (!$info) {
            return $this->format($model->create($data));
        }
        return $this->format($model->where('user_id', $userId)->update($data));
    }
}
