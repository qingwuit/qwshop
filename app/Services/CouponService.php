<?php
namespace App\Services;

use App\Http\Resources\Home\CouponResource\CouponCollection;
use App\Models\Coupon;
use App\Models\CouponLog;
use Illuminate\Support\Facades\DB;

class CouponService extends BaseService{
    
    // 用户领取优惠券 POST [id]
    public function receive(){
        $user_service = new UserService();
        $user_info = $user_service->getUserInfo();

        $coupon_id = request()->id; // 获取优惠券ID
        if(empty($coupon_id)){
            return $this->format_error(__('markets.coupon_error'));
        } 

        try{
            DB::beginTransaction();
            $coupon_model = new Coupon();
            $coupon_model = $coupon_model->find($coupon_id);

            // 判断优惠券是否失效
            if(now()->gt($coupon_model->end_time)){
                return $this->format_error(__('markets.coupon_invalid'));
            }

            // 查看发放总数是否发完
            if($coupon_model->stock<=0){
                return $this->format_error(__('markets.coupon_total_error'));
            }

            $coupon_log_model = new CouponLog();

            // 先查看是否存在
            if($coupon_log_model->where('coupon_id',$coupon_id)->exists()){
                return $this->format_error(__('markets.coupon_exists'));
            }
            $coupon_log_model->user_id = $user_info['id'];
            $coupon_log_model->coupon_id = $coupon_id;
            $coupon_log_model->store_id = $coupon_model->store_id;
            $coupon_log_model->money  = $coupon_model->money;
            $coupon_log_model->use_money = $coupon_model->use_money;
            $coupon_log_model->name = $coupon_model->name;
            $coupon_log_model->start_time = $coupon_model->start_time;
            $coupon_log_model->end_time = $coupon_model->end_time;
            $coupon_log_model->save();


            $coupon_model->where('id',$coupon_id)->decrement('stock',1); // 数量减少 

            DB::commit();
            return $this->format(); 
        }catch(\Exception $e){
            DB::rollBack();
            return $this->format_error($e->getMessage());
        }

        

    }

    /**
     * 使用优惠券 function
     *
     * @param integer $coupon_log_id // 领取到的优惠券
     * @param integer $order_id // 使用的订单
     * @return void
     * @Description
     * @author hg <www.qingwuit.com>
     */
    public function use_coupon($coupon_log_id=0,$order_id=0){
        if(empty($coupon_log_id)){
            return $this->format_error('markets.coupon_error');
        } 
        $coupon_log_model = new CouponLog();
        $coupon_log_model = $coupon_log_model->find($coupon_log_id);
        $coupon_log_model->order_id = $order_id;
        $coupon_log_model->status = 1;
        $coupon_log_model->save();
        return $this->format([]);
    }

    // 根据店铺ID 获取优惠券 列表
    public function getCouponByStoreId($store_id){
        $coupon_model = new Coupon();
        $list = $coupon_model->where('store_id',$store_id)->where('stock','>',0)->where('start_time','<',now())->where('end_time','>',now())->get();

        if($list->isEmpty()){
            return $this->format_error('coupon_empty');
        }

        return $this->format(new CouponCollection($list));
    }
}
