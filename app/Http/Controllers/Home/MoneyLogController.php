<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MoneyLog;

class MoneyLogController extends BaseController
{
    public function get_money_log(Request $req,MoneyLog $money_log_model){
        $type = $req->type??0;  // 区分是余额  冻结余额  平台积分
        $user_info = auth()->user();
        $money_log_model = $money_log_model->where('user_id',$user_info['id']);

        if($type == 0){
            $money_log_model = $money_log_model->where('money','<>','0');
        }
        if($type == 1){
            $money_log_model = $money_log_model->where('frozen_money','<>','0');
        }
        if($type == 2){
            $money_log_model = $money_log_model->where('integral','<>','0');
        }
        $list = $money_log_model->orderBy('id','desc')->paginate(20);
        return $this->success_msg('ok',$list);
    }
}
