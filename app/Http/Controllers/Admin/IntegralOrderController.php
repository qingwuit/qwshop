<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\IntegralOrder;
use App\Tools\Helper;

class IntegralOrderController extends BaseController
{
    public function index(Request $req,IntegralOrder $integral_order_model){

        // 条件
        $params = json_decode($req->params,true);
        if($params['times'] != null && count($params['times'])>0){
            $integral_order_model = $integral_order_model->where('add_time','>=',strtotime($params['times'][0]))->where('add_time','<=',strtotime($params['times'][1]));
        }
        if(!empty($params['type'])){
            $integral_order_model = $integral_order_model->where(get_integral_order_params($params['type']));
        }
        if(!empty($params['order_no'])){
            $integral_order_model = $integral_order_model->where('order_no',$params['order_no']);
        }

        $list = $integral_order_model->orderBy('id','desc')->paginate(20)->toArray();
        $list['data'] = get_integral_order_status($list['data']);
        return $this->success_msg('Success',$list);
    }

    public function info(Request $req,IntegralOrder $integral_order_model){
        $id = $req->id;
        $helper_model = new Helper();
        $info = $integral_order_model->where('id',$id)->with('integral_order_goods')->first();
        $info = get_integral_order_status_one($info);
        // var_dump($info['delivery_no']);
        if(!empty($info['delivery_no'])){
            $info['delivery_list'] = json_decode($helper_model->get_freight_info($info['delivery_no']),true);
        }
        return $this->success_msg('Success',$info);
    }


    // 发货物流填写
    public function send_delivery(Request $req,IntegralOrder $integral_order_model){
        $delivery_no = $req->delivery_no??'';
        $order_id = $req->order_id??0;

        $delivery_data = [
            'delivery_no'       =>  $delivery_no,
            'delivery_status'   =>  1,
        ];
        $rs = $integral_order_model->where(['id'=>$order_id])->update($delivery_data);
        return $this->success_msg('ok',$rs);
    }
}
