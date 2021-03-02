<?php
namespace App\Services;

use App\Models\Order;
use App\Models\Refund;

class RefundService extends BaseService{

    // 添加售后
    public function add(){
        $user_service = new UserService();
        $user_info = $user_service->getUserInfo();
        $refund_model = new Refund();
        $order_id = request()->order_id??0;
        $refund_type = request()->refund_type??0;
        $refund_remark = request()->refund_remark??'';
        $images = request()->images??'';

        $order_model = new Order();
        $order_info = $order_model->where('user_id',$user_info['id'])->where('id',$order_id)->first();
        if(!$order_info && $order_info->order_status!=6 && $order_info->order_status !=4){
            return $this->format_error(__('base.error'));
        }
        
        if($refund_model->where('user_id',$user_info['id'])->where('order_id',$order_id)->exists()){
            return $this->format_error(__('orders.order_refund_handle'));
        }
        
        $refund_model->user_id = $user_info['id'];
        $refund_model->order_id = $order_id;
        $refund_model->store_id = $order_info->store_id;
        $refund_model->refund_type = $refund_type;
        $refund_model->refund_remark = $refund_remark;
        $refund_model->images = $images;
        $refund_model->save();

        // 修改订单状态
        $order_info->order_status = 5;
        $order_info->refund_status = $refund_type;
        $rs = $order_info->save();
        return $this->format($rs,__('orders.order_refund_success'));
    }

    // 修改
    public function edit($id,$auth='user'){
        $refund_model = new Refund();
        if($auth == 'seller'){
            $store_id = $this->get_store(true);
            $refund_model = $refund_model->where('store_id',$store_id);
        }

        if($auth== 'user'){
            $user_service = new UserService();
            $user_info = $user_service->getUserInfo();
            $refund_model = $refund_model->where('user_id',$user_info['id']);
        }
        $refund_info = $refund_model->where('order_id',$id)->first();
        
        if(!$refund_info){
            return $this->format_error(__('base.error'));
        }

        if(isset(request()->refund_verify) && $auth =='seller'){
            $refund_verify = request()->refund_verify;
            if($refund_info->refund_verify!=0){
                return $this->format_error(__('base.error').'- rep');
            }

            // 拒绝还是同意
            if($refund_verify == 1){
                // 如果是退款
                if($refund_info->refund_type == 0){
                    // 直接进行用户资金操作
                    $order_service = new OrderService();
                    $order_info = $order_service->getOrderInfoById($id,'seller')['data'];
                    $ml_service = new MoneyLogService();
                    $ml_service->editMoney('售后退款',$refund_info->user_id,$order_info['total_price'],0,$order_info['order_no']);

                    // 修改订单状态
                    $order_model = new Order();
                    $order_info = $order_model->where('id',$id)->where('store_id',$this->get_store(true))->first();
                    $order_info->order_status = 6;
                    $order_info->refund_status = 2;
                    $order_info->save();
                    $refund_info->refund_step = 3;
                }
                $refund_info->refund_verify = $refund_verify;
                $rs = $refund_info->save();

            }else{
                $refund_info->refund_verify = 2;
                $refund_info->refuse_remark = request()->refuse_remark??'暂无任何原因';
                // 修改订单状态
                $order_model = new Order();
                $order_info = $order_model->where('id',$id)->where('store_id',$this->get_store(true))->first();
                $order_info->order_status = 6;
                $order_info->refund_status = 2;
                $order_info->save();

                $rs = $refund_info->save();
            }
            return $this->format($rs,__('base.success'));
        }

        if(isset(request()->delivery_no) && !empty(request()->delivery_no) && $auth == 'user' && $refund_info->refund_verify==1){
            $refund_info->delivery_no = request()->delivery_no??'';
            $refund_info->delivery_code = request()->delivery_code??'';
            $refund_info->refund_step = 1;
        }

        if(isset(request()->re_delivery_no) && !empty(request()->re_delivery_no) && $auth == 'seller' && $refund_info->refund_step==1){
            $refund_info->re_delivery_no = request()->re_delivery_no??'';
            $refund_info->re_delivery_code = request()->re_delivery_code??'';
            $refund_info->refund_step = 2;
        }

        if(isset(request()->refund_step) && !empty(request()->refund_step) && $auth == 'user' && $refund_info->refund_verify==1 && $refund_info->refund_step==2){
            $refund_info->refund_step = 3;

            // 修改订单状态
            $order_model = new Order();
            $order_info = $order_model->where('id',$id)->where('user_id',$refund_info->user_id)->first();
            $order_info->order_status = 6;
            $order_info->refund_status = 2;
            $order_info->save();
        }

        $rs = $refund_info->save();
        return $this->format($rs,__('base.success'));

    }

    // 获取售后信息
    public function getRefundByOrderId($order_id,$auth='user'){
        
        $refund_model = new Refund();

        if($auth == 'user'){
            $user_service = new UserService();
            $user_info = $user_service->getUserInfo();
            $refund_model = $refund_model->where('user_id',$user_info['id']);
        }
        if($auth == 'seller'){
            $store_id = $this->get_store(true);
            $refund_model = $refund_model->where('store_id',$store_id);
        }
        $refund_info = $refund_model->where('order_id',$order_id)->first();
        if(!$refund_info){
            return $this->format_error(__('base.error'));
        }
        return $this->format($refund_info);
    }
    
}
