<?php
namespace App\Services;

use App\Http\Resources\Home\UserCheckResource\UserCheckResource;
use App\Models\UserCheck;

class UserCheckService extends BaseService{
    
    public function getUserCheck($auth="user"){
        $uc_model = new UserCheck();
        $user_service = new UserService;
        $user_info = $user_service->getUserInfo();
        $check_info = $uc_model->where('user_id',$user_info['id'])->first();
        if(empty($check_info)){
            return $this->format_error(__('users.user_check_error'));
        }

        // 判断是否是用户获取还是后台获取
        if($auth == 'user'){
            return $this->format(new UserCheckResource($check_info));
        }
        return $this->format($check_info,__('base.success'));
    }

    public function editUserCheck(){
        $uc_model = new UserCheck();
        $user_service = new UserService;
        $user_info = $user_service->getUserInfo();
        $uc_info = $uc_model->where('user_id',$user_info['id'])->first();
        if(!empty($uc_info)){
            $uc_model = $uc_info;
        }else{
            if(
                !isset(request()->name) || empty(request()->name) || !isset(request()->name) || empty(request()->name) ||
                !isset(request()->bank_no) || empty(request()->bank_no) || !isset(request()->card_no) || empty(request()->card_no) ||
                !isset(request()->card_t) || empty(request()->card_t) || !isset(request()->card_b) || empty(request()->card_b) ||
                !isset(request()->bank_name) || empty(request()->bank_name)
            ){
                return $this->format_error(__('base.error'));
            }
        }
        $uc_model->user_id = $user_info['id'];
        if(isset(request()->name) && !empty(request()->name)){
            $uc_model->name = request()->name;
        }
        if(isset(request()->bank_no) && !empty(request()->bank_no)){
            $uc_model->bank_no = request()->bank_no;
        }
        if(isset(request()->card_no) && !empty(request()->card_no)){
            $uc_model->card_no = request()->card_no;
        }
        if(isset(request()->card_t) && !empty(request()->card_t)){
            $uc_model->card_t = request()->card_t;
        }
        if(isset(request()->card_b) && !empty(request()->card_b)){
            $uc_model->card_b = request()->card_b;
        }
        if(isset(request()->bank_name) && !empty(request()->bank_name)){
            $uc_model->bank_name = request()->bank_name;
        }
        $uc_model->save();
        return $this->format([],__('base.success'));
        
    }
}
