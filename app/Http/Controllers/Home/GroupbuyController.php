<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AdvPosition;

class GroupbuyController extends NotokenController
{
    public function get_groupbuy_banner(AdvPosition $adv_position_model){
        $data['banner'] = $adv_position_model->get_adv_list('PC_团购中心幻灯片'); // 获取幻灯片
        return $this->success_msg('ok',$data);
    }
}
