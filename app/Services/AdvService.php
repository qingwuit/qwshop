<?php
namespace App\Services;

use App\Http\Resources\Home\AdvResource\AdvCollection;
use App\Models\Adv;
use App\Models\AdvPosition;

class AdvService extends BaseService{
    
    // 获取指定名称广告
    public function get_adv_list($name=''){
        $adv_position_model = new AdvPosition();
        $postionInfo = $adv_position_model->where('ap_name',$name)->first();
        $adv_model = new Adv();
        $list = $adv_model->where('ap_id',$postionInfo['id'])->whereDate('start_time','<',date('Y-m-d H:i:s'))->whereDate('end_time','>',date('Y-m-d H:i:s'))->get();
        return $this->format(new AdvCollection($list));
    }
}
