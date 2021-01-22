<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Http\Resources\Home\CouponResource\CouponUserCollection;
use App\Models\CouponLog;
use App\Services\CouponService;
use App\Services\UserService;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    // 获取自己优惠券的列表
    public function index(CouponLog $coupon_log_model,UserService $user_service){
        $user_info = $user_service->getUserInfo();
        $list = $coupon_log_model->with('store')->where('status',0)->where('start_time','<',now())->where('end_time','>',now())->where('user_id',$user_info['id'])->orderBy('id','desc')->paginate(request()->per_page??30);
        return $this->success(new CouponUserCollection($list));
    }

    // 领取优惠券
    public function receive_coupon(Request $request,CouponService $coupon_service){
        $id = $request->id;
        $rs = $coupon_service->receive($id);
        return $rs['status']?$this->success([],__('markets.coupon_receive_success')):$this->error($rs['msg']);
    }
}
