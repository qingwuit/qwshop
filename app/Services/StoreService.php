<?php
namespace App\Services;
use App\Http\Resources\Home\StoreResource\StoreJoin;
use App\Models\Store;

class StoreService extends BaseService{

    // 入驻时获取店铺状态
    public function store_verify($auth='user'){
        $store_model = new Store();
        $store_info = $store_model->where('user_id',auth($auth)->id())->first();
        if(empty($store_info)){
            return $this->format_error(__('admin.store_not_defined'));
        }
        return $this->format(new StoreJoin($store_info));
        
    }
}
