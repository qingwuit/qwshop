<?php
namespace App\Services;

use App\Http\Resources\Admin\MoneyLogResource\MoneyLogCollection as AdminMoneyLogCollection;
use App\Http\Resources\Seller\MoneyLogResource\MoneyLogCollection as SellerMoneyLogCollection;
use App\Http\Resources\Home\MoneyLogResource\MoneyLogCollection;
use App\Models\MoneyLog;
use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class MoneyLogService extends BaseService{

    /**
     * 修改用户金额 增加日志 function
     *
     * @param [type] $user_id 用户ID
     * @param [type] $money 金额
     * @param [type] $name 名称  订单支付|后台操作
     * @param integer $type 类型 0 金额 1冻结资金 2积分
     * @return void
     * @Description
     * @author hg <www.qingwuit.com>
     */
    public function editMoney($name,$user_id,$money,$type=0,$info=''){
        try{
            DB::beginTransaction();
            $user_model = new User();
            $ml_model = new MoneyLog();
            $ml_model->user_id = $user_id;
            $ml_model->money = $money;
            $ml_model->is_type = $type;
            $ml_model->name = $name;
            $ml_model->info = $info;
            $ml_model->save();
            $user_model = $user_model->find($user_id);
            switch($type){
                case 0:
                    $user_model->money += $money;
                break;
                case 1:
                    $user_model->frozen_money += $money;
                break;
                case 2:
                    $user_model->integral += $money;
                break;
            }
            $user_model->save();
            DB::commit();
            return $this->format([]);
        }catch(\Exception $e){
            DB::rollBack();
            return $this->format_error(__('users.money_log_error'));
        }
    }

    /**
     * 修改商家金额 增加日志 function
     *
     * @param [type] $user_id 用户ID
     * @param [type] $money 金额
     * @param [type] $name 名称  订单支付|后台操作
     * @param integer $type 类型 0 金额 1冻结资金 2积分
     * @return void
     * @Description
     * @author hg <www.qingwuit.com>
     */
    public function editSellerMoney($name,$store_id,$money,$type=0,$info=''){
        try{
            DB::beginTransaction();
            $store_model = new Store();
            $ml_model = new MoneyLog();
            $ml_model->user_id = $store_id;
            $ml_model->money = $money;
            $ml_model->is_type = $type;
            $ml_model->is_seller = 1;
            $ml_model->name = $name;
            $ml_model->info = $info;
            $ml_model->save();
            $store_model = $store_model->find($store_id);
            switch($type){
                case 0:
                    $store_model->store_money += $money;
                break;
                case 1:
                    $store_model->store_frozen_money += $money;
                break;
            }
            $store_model->save();
            DB::commit();
            return $this->format([]);
        }catch(\Exception $e){
            DB::rollBack();
            dd($e->getMessage());
            return $this->format_error(__('users.money_log_error'));
        }
    }

    // 获取日志列表
    public function getMoneyLog($auth="user"){
        $money_log_model = new MoneyLog();
        if($auth == 'user'){
            $user_service = new UserService;
            $user_info = $user_service->getUserInfo();
            $money_log_model = $money_log_model->where('user_id',$user_info['id'])->where('is_seller',0);
        }
        if($auth=='seller'){
            $money_log_model = $money_log_model->where('user_id',$this->get_store(true))->where('is_seller',1);
        }
        if(isset(request()->user_id)){
            $money_log_model = $money_log_model->where('user_id',request()->user_id);
        }

        if(isset(request()->is_seller)){
            $money_log_model = $money_log_model->where('is_seller',request()->is_seller);
        }

        // if(isset(request()->phone) && request()->is_seller==1){
        //     $money_log_model = $money_log_model->join('store')->where('phone',request()->phone);
        // }

        // if(isset(request()->phone) && request()->is_seller==0){
        //     $money_log_model = $money_log_model->where('phone',request()->phone);
        // }
        if(isset(request()->is_type)){
            $money_log_model = $money_log_model->where('is_type',request()->is_type??0);
        }
        $list = $money_log_model->orderBy('id','desc')->paginate(request()->per_page??30);

        if($auth == 'user'){
            return $this->format(new MoneyLogCollection($list));
        }
        if($auth == 'seller'){
            return $this->format(new SellerMoneyLogCollection($list));
        }
        return $this->format(new AdminMoneyLogCollection($list));
        
    }
}
