<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Tools\Helper;

class OrderController extends BaseController
{
    public function index(Request $req,Order $order_model){

        // 条件
        $params = json_decode($req->params,true);
        if($params['times'] != null && count($params['times'])>0){
            $order_model = $order_model->where('add_time','>=',strtotime($params['times'][0]))->where('add_time','<=',strtotime($params['times'][1]));
        }
        if(!empty($params['type'])){
            $order_model = $order_model->where(get_order_params($params['type']));
        }
        if(!empty($params['order_no'])){
            $order_model = $order_model->where('order_no',$params['order_no']);
        }

        $list = $order_model->orderBy('id','desc')->paginate(20)->toArray();
        $list['data'] = get_order_status($list['data']);
        return $this->success_msg('Success',$list);
    }

    public function info(Request $req,Order $order_model){
        $id = $req->id;
        $helper_model = new Helper();
        $info = $order_model->where('id',$id)->with('order_goods')->first();
        $info = get_order_status_one($info);
        // var_dump($info['delivery_no']);
        if(!empty($info['delivery_no'])){
            $info['delivery_list'] = json_decode($helper_model->get_freight_info($info['delivery_no']),true);
        }
        return $this->success_msg('Success',$info);
    }

}
