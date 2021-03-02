<?php
namespace App\Services;

use App\Http\Resources\Home\CashResource\CashCollection;
use App\Http\Resources\Seller\CashResource\CashCollection as SellerCashCollection;
use App\Http\Resources\Admin\CashResource\CashCollection as AdminCashCollection;
use App\Models\Cash;
use App\Models\UserCheck;

class CashService extends BaseService{


    // 获取提现列表
    public function getCash($auth='user'){
        $cash_model = new Cash();
        if($auth == 'user'){
            $cash_model = $cash_model->where('user_id',auth()->id())->where('store_id',0);
        }elseif($auth == 'seller'){
            $store_id = $this->get_store(true);
            $cash_model = $cash_model->where('user_id',0)->where('store_id','>',$store_id);
        }
        $list = $cash_model->orderBy('id','desc')->paginate(request()->per_page??30);

        if($auth == 'user'){
            return $this->format(new CashCollection($list));
        }
        if($auth == 'seller'){
            return $this->format(new SellerCashCollection($list));
        }
        return $this->format(new AdminCashCollection($list));
    }

    // money
    public function add($auth = 'user'){
        $cash_model = new Cash();
        $money = abs(request()->money)+0;

        // 金额不能小于100
        if($money<100){
            return $this->format_error(__('users.cash_money_gt100'));
        }
        
        $ml_service = new MoneyLogService();

        if($auth == 'user'){
            $user_service = new UserService;
            $user_info = $user_service->getUserInfo();
            $uc_model = new UserCheck();

            if($money>$user_info['money']){
                return $this->format_error(__('users.cash_money_insufficient'));
            }

            $uc_info = $uc_model->where('user_id',$user_info['id'])->first();
            if(!$uc_info){
                return $this->format_error(__('base.error'));
            }
            $cash_model->user_id = $user_info['id'];
            $cash_model->money = $money;
            $cash_model->name = $uc_info->name;
            $cash_model->card_no = $uc_info->card_no;
            $cash_model->bank_name = $uc_info->bank_name;

            // 资金冻结处理
            
            $ml_service->editMoney(__('users.money_log_cash'),$user_info['id'],-$money);
            $ml_service->editMoney(__('users.money_log_cash'),$user_info['id'],$money,1);
        }

        // 店铺提现
        if($auth=='seller'){
            $store_info = $this->get_store(false,'id,store_money');
            $cash_model->store_id = $store_info['id'];
            $cash_model->money = $money;
            $cash_model->name = request()->name;
            $cash_model->card_no = request()->card_no;
            $cash_model->bank_name = request()->bank_name;

            if($money>$store_info['store_money']){
                return $this->format_error(__('users.cash_money_insufficient'));
            }

            // 资金冻结处理
            $ml_service->editSellerMoney(__('users.money_log_cash'),$store_info['id'],-$money);
            $ml_service->editSellerMoney(__('users.money_log_cash'),$store_info['id'],$money,1);
        }

        $rs = $cash_model->save();
        return $this->format($rs,__('users.cash_success'));
    }

    // 修改提现到账状态
    public function edit($id){
        $cash_model = new Cash();
        $cash_status = request()->cash_status;
        $refuse_info = request()->refuse_info??'';
        
        $cash_info = $cash_model->where('id',$id)->first();
        if(!$cash_info){
            return $this->format_error(__('base.error'));
        }
        $cash_info->cash_status = $cash_status;
        $cash_info->refuse_info = $refuse_info;
        $rs = $cash_info->save();

        $ml_service = new MoneyLogService();

        if($cash_info->user_id>0){
            $rs = $ml_service->editMoney(__('users.money_log_cash'),$cash_info['user_id'],-$cash_info['money'],1);
        }else{
            $rs = $ml_service->editSellerMoney(__('users.money_log_cash'),$cash_info['store_id'],-$cash_info['money'],1);
        }

        // // 余额日志处理
        // if($auth == 'user'){
        //     $ml_service->editMoney(__('users.money_log_cash'),$cash_info['user_id'],-$cash_info['money'],1);
        // }

        // // 余额日志处理
        // if($auth == 'seller'){
        //     $ml_service->editSellerMoney(__('users.money_log_cash'),$cash_info['store_id'],-$cash_info['money'],1);
        // }

        return $this->format($rs);
    }
}
