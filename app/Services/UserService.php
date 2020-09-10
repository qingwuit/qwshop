<?php
namespace App\Services;
use App\Http\Resources\Admin\AdminResource\Admin as AdminResource;
use App\Http\Resources\Home\UserResource\UserCheckLogin;
use App\Models\Admin;
use App\Models\SmsLog;
use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService extends BaseService{

    // login
    public function login($username='username',$auth='user'){
        $credentials = request([$username, 'password']);
        if (! $token = auth($auth)->attempt($credentials)) {
            return $this->format_error(__('auth.error'));
        }

        if(!$userInfo = $this->getUserInfo($auth)){
            return $this->format_error(__('auth.user_error'));
        }

        // 登陆成功修改时间和ip
        if($auth=='admin'){
            $admin_model = Admin::find($userInfo['id']);
            $admin_model->login_time = now();
            $admin_model->last_login_time = $userInfo['login_time'];
            $admin_model->ip = request()->getClientIp();
            $admin_model->save();
        }
        if($auth=='user'){
            $user_model = User::find($userInfo['id']);
            $user_model->login_time = now();
            $user_model->last_login_time = $userInfo['login_time'];
            $user_model->ip = request()->getClientIp();
            $user_model->save();
        }

        $data = [
            'token' => $token,
            'user_info'=>$userInfo,
        ];

        return $this->format($data);
    }

    // 注册
    public function register($username='username',$auth='user'){
        $credentials = request([$username, 'password','re_password','code']);
        $user_model = new User();
        if($user_model->where($username,$credentials[$username])->exists()){
            return $this->format_error(__('auth.user_exists'));
        }
        $sms_log_model = new SmsLog();
        if(empty($smsInfo = $sms_log_model->where([
            'ip'    =>  request()->getClientIp(),
            'content'    =>  $credentials['code'],
            'status'    =>  1,
            $username   =>  $credentials[$username],
        ])->first())){
            return $this->format_error(__('auth.sms_error'));
        }

        // 验证码失效 十分钟
        $ct = strtotime($smsInfo->created_at->format('Y-m-d H:i:s'));
        if(($ct+600)<time()){
            return $this->format_error(__('auth.sms_invalid'));
        }

        $randNickName = $credentials[$username].'_'.mt_rand(100,999);
        $user_model->username = $randNickName;
        $user_model->phone = $credentials[$username];
        $user_model->nickname = $randNickName;
        $user_model->ip = request()->getClientIp();
        $user_model->password = Hash::make($credentials['password']);
        $user_model->pay_password = Hash::make($credentials['password']);
        $user_model->save();
        
        $credentials2 = request([$username, 'password']);
        if (! $token = auth($auth)->attempt($credentials2)) {
            return $this->format_error(__('auth.error'));
        }
        
        if(!$userInfo = $this->getUserInfo($auth)){
            return $this->format_error(__('auth.user_error'));
        }

        $data = [
            'token' => $token,
            'user_info'=>$userInfo,
        ];

        return $this->format($data);
    }

    // 找回密码
    public function forgetPassword($username='username',$auth='user'){
        $credentials = request([$username, 'password','re_password','code']);
        $user_model = new User();
        if(!$user_model->where($username,$credentials[$username])->exists()){
            return $this->format_error(__('auth.user_not_exists'));
        }
        $sms_log_model = new SmsLog();
        if(empty($smsInfo = $sms_log_model->where([
            'ip'    =>  request()->getClientIp(),
            'content'    =>  $credentials['code'],
            'status'    =>  1,
            $username   =>  $credentials[$username],
        ])->first())){
            return $this->format_error(__('auth.sms_error'));
        }

        // 验证码失效 十分钟
        $ct = strtotime($smsInfo->created_at->format('Y-m-d H:i:s'));
        if(($ct+600)<time()){
            return $this->format_error(__('auth.sms_invalid'));
        }

        $user_model = $user_model->where($username,$credentials[$username])->first();
        $user_model->ip = request()->getClientIp();
        $user_model->password = Hash::make($credentials['password']);
        $user_model->save();
        
        $credentials2 = request([$username, 'password']);
        if (! $token = auth($auth)->attempt($credentials2)) {
            return $this->format_error(__('auth.error'));
        }
        
        if(!$userInfo = $this->getUserInfo($auth)){
            return $this->format_error(__('auth.user_error'));
        }

        $data = [
            'token' => $token,
            'user_info'=>$userInfo,
        ];

        return $this->format($data);
    }

    // 获取用户信息
    public function getUserInfo($auth='user'){
        try{
            $info = auth($auth)->user();
            if($auth == 'admin'){
                return new AdminResource($info);
            }
            return auth($auth)->user();
        }catch(\Exception $e){
            return false;
        }
    }

    // 检测用户是否登陆
    public function checkLogin($auth='user',$seller = false){

        // 查看是否携带token
        if(!auth($auth)->parser()->setRequest(request())->hasToken()){
            return $this->format_error(__('auth.no_token'));
        }

        try{
            $id = auth($auth)->payload()->get('sub');
            if(!auth($auth)->byId($id)){ // 判断是否失效
                return $this->format_error(__('auth.no_token'));
            }
        }catch(\Exception $e){
            return $this->format_error(__('auth.error_token'));
        }

        if($seller){
            $stores_model = new Store();
            $store_info = $stores_model->select('id','store_name','store_logo')->where('user_id',auth($auth)->id())->first();
            return $this->format($store_info);
        }

        return $this->format(new UserCheckLogin($this->getUserInfo($auth)));
        
    }
    
}
