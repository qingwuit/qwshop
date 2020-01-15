<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Agreement;
use App\Models\Area;
use App\Models\GoodsClass;
use App\Models\AdvPosition;

/**
 * @author hg <www.qingwuit.com>
 * @example location 非权限接口
 */

class ApiController extends NotokenController
{
    // 获取协议信息
    public function get_agreement_info(Request $req,Agreement $agreement_model){
        $ename = $req->ename;
        $rs = $agreement_model->get_agreement_by_ename($ename);
        return $this->success_msg('Success',$rs);
    }

    // 获取省市区信息
    public function get_area_list(Area $area_model){
        $rs = $area_model->get_area_list();
        return $this->success_msg('Success',$rs);
    }

    // 获取商品分类信息
    public function get_goods_class_list(GoodsClass $goods_class_model){
        $rs = $goods_class_model->get_goods_class_list();
        return $this->success_msg('Success',$rs);
    }

    // 获取广告或者幻灯片
    public function get_banner(Request $req,AdvPosition $adv_position_model){
        $data = $adv_position_model->get_adv_list($req->name); // 获取幻灯片
        return $this->success_msg('Success',$data);
    }


}
