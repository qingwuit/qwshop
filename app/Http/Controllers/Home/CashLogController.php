<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CashLog;
use App\Models\Config;
use App\Models\UserCkeck;
use App\Models\Users;

class CashLogController extends BaseController
{
    // 获取提现列表
    public function get_cash_log(Request $req,CashLog $cash_log_model){
        $user_info = auth()->user();
        $list = $cash_log_model->where('user_id',$user_info['id'])->orderBy('id','desc')->paginate(20)->toArray();
        return $this->success_msg('ok',$list);
    }

    // 添加提现记录
    public function add_cash(Request $req,CashLog $cash_log_model,UserCkeck $user_check_model,Config $config_model,Users $user_model){

        $config_info = $config_model->get_format_config();

        $user_info = auth()->user();
        $check_info = $user_check_model->where('user_id',$user_info['id'])->first(); // 是否用户认证

        if(empty($check_info)){
            return $this->error_msg('请先实名制!');
        }

        if(!$req->isMethod('post')){
            $data = $check_info;
            $data['cash_rate'] = $config_info['cash_rate'];
            $data['money'] = $user_info['money'];
            return $this->success_msg('ok',$data);
        }

        $money = $req->money;

        if($money>$user_info['money']){
            return $this->error_msg('余额不足');
        }

        if($money<100){
            return $this->error_msg('金额不能小于100');
        }
        
        $rate_money = round($config_info['cash_rate']/100*$money,2);

        $cash_data = [
            'user_id'           =>  $user_info['id'],
            'nickname'          =>  $user_info['nickname'],
            'real_name'         =>  $check_info['real_name'],
            'bank'              =>  $check_info['bank_name'],
            'card_no'           =>  $check_info['bank_card'],
            'money'             =>  $money,
            'rate'              =>  $config_info['cash_rate'],
            'rate_money'        =>  $rate_money,
            'status'            =>  0,
        ];
        $rs = $cash_log_model->addCash($cash_data);

        $user_model->money_change('余额提现','money',-$money,$user_info,'','-');

        return $this->success_msg('ok',$rs);
    }
}
