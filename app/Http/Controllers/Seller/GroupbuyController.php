<?php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use App\Models\Groupbuy;
use App\Models\GroupbuyUser;

class GroupbuyController extends BaseController
{
    public function index(Request $req,Groupbuy $groupbuy_model){

        // 条件
        $params = json_decode($req->params,true);
        if($params['times'] != null && count($params['times'])>0){
            $groupbuy_model = $groupbuy_model->where('add_time','>=',strtotime($params['times'][0]))->where('add_time','<=',strtotime($params['times'][1]));
        }

        if(!empty($params['type'])){
            if($params['type'] == 1){
                $groupbuy_model = $groupbuy_model->whereRaw('groupbuy_num=groupbuy_use');
            }else{
                $groupbuy_model = $groupbuy_model->whereRaw('groupbuy_num>groupbuy_use');
            }
            
        }

        $user_info = auth()->user();
        $list = $groupbuy_model->where('seller_id',$user_info['id'])->with(['get_goods'])->orderBy('id','desc')->paginate(20)->toArray();

        foreach($list['data'] as $k=>$v){
            $v['get_goods']['image'] = get_format_image($v['get_goods']['goods_master_image'],200);
            $list['data'][$k] = $v;
        }

        return $this->success_msg('Success',$list);
    }

    // 获取指定订单
    public function get_groupbuy_user(Request $req,GroupbuyUser $groupbuy_user_model){
        $gb_id = $req->gb_id;
        $list = $groupbuy_user_model->where('gb_id',$gb_id)->get();
        return $this->success_msg('Success',$list);
    }
}
