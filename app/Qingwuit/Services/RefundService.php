<?php
namespace App\Qingwuit\Services;

class RefundService extends BaseService
{
    // 添加售后
    public function add()
    {
        $userId = $this->getUserId('users');
        $refund_model = $this->getService('Refund', true);
        $order_id = request()->order_id??0;
        $refund_type = request()->refund_type??0;
        $refund_remark = request()->refund_remark??'';
        $images = request()->images??'';

        $order_info = $this->getService('Order', true)->where('user_id', $userId)->where('id', $order_id)->first();

        if (!$order_info) {
            return $this->formatError(__('tip.error'));
        }
        if ($order_info->pay_time->lt(now()->subDays(7))) {
            return $this->formatError('over 7 days');
        }

        if($order_info->is_settlement == 1){
            return $this->formatError('The order has settlement .');
        }
        
        if ($refund_model->where('user_id', $userId)->where('refund_verify', 0)->where('order_id', $order_id)->exists()) {
            return $this->formatError('refund handled');
        }

        // 如果在待发货时 就申请售后，应该直接退款，这里后面再处理
        $refund_model->user_id = $userId;
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
        return $this->format($rs, __('tip.success'));
    }

    // 修改
    public function edit($id, $auth='user')
    {
        $refund_model = $this->getService('Refund', true);
        if ($auth == 'seller') {
            $store_id = $this->getService('Store')->getStoreId()['data'];
            $refund_model = $refund_model->where('store_id', $store_id);
        }

        if ($auth== 'user') {
            $userId = $this->getUserId('users');
            $refund_model = $refund_model->where('user_id', $userId);
        }
        $refund_info = $refund_model->where('order_id', $id)->first();
        
        if (!$refund_info) {
            return $this->formatError(__('tip.error'));
        }

        if (isset(request()->refund_verify) && $auth =='seller') {
            $refund_verify = request()->refund_verify;
            if ($refund_info->refund_verify!=0) {
                return $this->formatError(__('tip.error').'- rep');
            }

            // 拒绝还是同意
            if ($refund_verify == 1) {
                // 如果是退款
                if ($refund_info->refund_type == 0) {
                    // 直接进行用户资金操作
                    $order_info = $this->getService('Order', true)->where('id', $id)->first();
                    $this->getService('MoneyLog')->edit([
                        'name'=>'售后退款',
                        'money'=>$order_info->total_price,
                        'user_id'=>$refund_info->user_id,
                        'info'=>$order_info['order_no'],
                    ]);

                    // 修改订单状态
                    $order_info = $this->getService('Order', true)->where('id', $id)->where('store_id', $store_id)->first();
                    $order_info->order_status = 6;
                    $order_info->refund_status = 2;
                    $order_info->save();
                    $refund_info->refund_step = 3;
                }
                $refund_info->refund_verify = $refund_verify;
                $rs = $refund_info->save();
            } else {
                $refund_info->refund_verify = 2;
                $refund_info->refuse_remark = request()->refuse_remark??'暂无任何原因';
                // 修改订单状态
                $order_model = $this->getService('Order', true);
                $order_info = $order_model->where('id', $id)->where('store_id', $store_id)->first();
                $order_info->order_status = 6;
                $order_info->refund_status = 2;
                $order_info->save();

                $rs = $refund_info->save();
            }
            return $this->format($rs, __('tip.success'));
        }

        if (isset(request()->delivery_no) && !empty(request()->delivery_no) && $auth == 'user' && $refund_info->refund_verify==1) {
            $refund_info->delivery_no = request()->delivery_no??'';
            $refund_info->delivery_code = request()->delivery_code??'';
            $refund_info->refund_step = 1;
            if ($refund_info->re_delivery_no != '') {
                $refund_info->refund_step = 2;
            }
        }

        if (isset(request()->re_delivery_no) && !empty(request()->re_delivery_no) && $auth == 'seller' && $refund_info->refund_step<=1) {
            $refund_info->re_delivery_no = request()->re_delivery_no??'';
            $refund_info->re_delivery_code = request()->re_delivery_code??'';
            if ($refund_info->refund_step == 1) {
                $refund_info->refund_step = 2;
            }
        }

        if (isset(request()->refund_step) && !empty(request()->refund_step) && $auth == 'user' && $refund_info->refund_verify==1 && $refund_info->refund_step==2) {
            $refund_info->refund_step = 3;

            // 修改订单状态
            $order_model = $this->getService('Order', true);
            $order_info = $order_model->where('id', $id)->where('user_id', $refund_info->user_id)->first();
            $order_info->order_status = 6;
            $order_info->refund_status = 2;
            $order_info->save();
        }

        $rs = $refund_info->save();
        return $this->format($rs, __('tip.success'));
    }

    // 获取售后信息
    public function getRefundByOrderId($order_id, $auth='user')
    {
        $refund_model = $this->getService('Refund', true);

        if ($auth == 'user') {
            $userId = $this->getUserId('users');
            $refund_model = $refund_model->where('user_id', $userId);
        }
        if ($auth == 'seller') {
            $store_id = $this->getService('Store')->getStoreId()['data'];
            $refund_model = $refund_model->where('store_id', $store_id);
        }
        $refund_info = $refund_model->where('order_id', $order_id)->first();
        if (!$refund_info) {
            return $this->formatError(__('tip.error'));
        }
        return $this->format($refund_info);
    }
}
