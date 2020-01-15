<?php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use App\Models\Area;

class AreaController extends BaseController
{
    // 获取省市区信息
    public function get_area_list(Area $area_model){
        $rs = $area_model->get_area_list();
        return $this->success_msg('Success',$rs);
    }
}
