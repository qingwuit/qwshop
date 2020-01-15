<?php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Users;
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
        if(!empty($params['is_groupbuy'])){
            $order_model = $order_model->where('is_groupbuy',$params['is_groupbuy']);
        }

        $user_info = auth()->user();

        $list = $order_model->where('seller_id',$user_info['id'])->orderBy('id','desc')->paginate(20)->toArray();
        $list['data'] = get_order_status($list['data']);
        return $this->success_msg('Success',$list);
    }

    public function info(Request $req,Order $order_model){
        $id = $req->id;
        $user_info = auth()->user();
        $helper_model = new Helper();
        $info = $order_model->where('seller_id',$user_info['id'])->where('id',$id)->with('order_goods')->first();
        $info = get_order_status_one($info);
        // var_dump($info['delivery_no']);
        if(!empty($info['delivery_no'])){
            $info['delivery_list'] = json_decode($helper_model->get_freight_info($info['delivery_no']),true);
        }
        return $this->success_msg('Success',$info);
    }


    // 发货物流填写
    public function send_delivery(Request $req,Order $order_model){
        $delivery_no = $req->delivery_no??'';
        $order_id = $req->order_id??0;
        $user_info = auth()->user();

        $delivery_data = [
            'delivery_no'       =>  $delivery_no,
            'delivery_status'   =>  1,
            'delivery_time'     =>  time(),
        ];
        $rs = $order_model->where(['id'=>$order_id,'seller_id'=>$user_info['id']])->update($delivery_data);
        return $this->success_msg('ok',$rs);
    }

    // 同意申请售后
    public function refund(Request $req,Order $order_model){
        $id = $req->id;
        $user_info = auth()->user();
        $rs = $order_model->where(['id'=>$id,'seller_id'=>$user_info['id']])->update(['refund_step'=>1]);
        return $this->success_msg('成功');
    }

    // 同意退款
    public function refund_money(Request $req,Order $order_model,Users $user_model){
        $id = $req->id;
        $user_info = auth()->user();
        $buyer_info = $user_model->find($req->user_id);

        $order_info = $order_model->find($id);

        if($order_info['refund_step'] == 4){
            return $this->error_msg('该售后已经处理过了');
        }

        $rs = $order_model->where(['id'=>$id,'seller_id'=>$user_info['id']])->update(['refund_step'=>4,'order_status'=>2,'refund_time'=>time()]);
        $user_model->money_change('售后退款','frozen_money',-$order_info['total_price'],$buyer_info,'order_no:'.$req->order_no,'-');
        $user_model->money_change('售后退款','money',$order_info['total_price'],$buyer_info,'order_no:'.$req->order_no,'-');
        return $this->success_msg('成功');
    }
}
