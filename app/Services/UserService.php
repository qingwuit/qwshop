<?php
namespace App\Services;
use App\Http\Resources\Admin\AdminResource\Admin as AdminResource;
use App\Http\Resources\Home\UserResource\UserCheckLogin;
use App\Models\Admin;
use App\Models\SmsLog;
use App\Models\Store;
use App\Models\User;
use App\Models\UserWechat;
use Illuminate\Support\Facades\DB;
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

            // 登录送积分
            $config_service = new ConfigService();
            $config_service->giveIntegral('login');
        }

        $data = [
            'token' => $token,
            'user_info'=>$userInfo,
        ];

        return $this->format($data);
    }

    // 第三方登录
    public function oauthLogin($oauth,$oauth_name="weixinweb"){
        $user_model = new User();
        $auth = 'user';

        try{
            DB::beginTransaction();
            // 判断是否存在该ID
            if($oauth_name == 'weixinweb'){
                $isLogin = false; // 登录还是注册
                $uw_model = new UserWechat();
                $uwInfo = $uw_model->where('unionid',$oauth['unionid'])->fisrt();
                
                // 不存在则开始创建
                if(!$uwInfo){
                    $user_model->username = 'wx'.date('Ymd').mt_rand(100,999);
                    $user_model->nickname = $oauth['nickname'];
                    $user_model->ip = request()->getClientIp();
                    $user_model->inviter_id = request()->inviter_id??0;
                    $user_model->password = Hash::make('123456');
                    $user_model->pay_password = Hash::make('123456');
                    $user_model->save();
                    $user_id = $user_model->id;

                    // 插入第三方表
                    $uw_model->create([
                        'openid'        =>  $oauth['id'],
                        'nickname'      =>  $oauth['nickname'],
                        'user_id'       =>  $user_id,
                        'unionid'       =>  $oauth['unionid'],
                        'headimgurl'    =>  $oauth['avatar'],
                    ]);
                }else{
                    $isLogin = true;
                    $user_id = $uw_model['user_id'];
                }
 
            }

            $user_info = User::select('username','password')->where('id',$user_id)->first();

            $user_model->login_time = now();
            $user_model->last_login_time = $user_info['login_time'];
            $user_model->ip = request()->getClientIp();
            $user_model->save();
           
            if (! $token = auth($auth)->login($user_info)) {
                return $this->format_error(__('auth.error'));
            }
            
            if(!$userInfo = $this->getUserInfo($auth)){
                return $this->format_error(__('auth.user_error'));
            }

            // 登录送积分
            $config_service = new ConfigService();
            $config_service->giveIntegral('login');

            $data = [
                'token' => $token,
                'user_info'=>$userInfo,
            ];

            // 提交事务
            DB::commit();
            return $this->format($data);
        }catch(\Exception $e){
            return $this->format_error($e->getMessage());
        }
        
        
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
        $user_model->inviter_id = request()->inviter_id??0;
        $user_model->password = Hash::make($credentials['password']);
        $user_model->pay_password = Hash::make($credentials['password']);
        $user_model->save();
        
        if (! $token = auth($auth)->login($user_model)) {
            return $this->format_error(__('auth.error'));
        }
        
        if(!$userInfo = $this->getUserInfo($auth)){
            return $this->format_error(__('auth.user_error'));
        }

        // 登录送积分
        $config_service = new ConfigService();
        $config_service->giveIntegral('login');

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
        
        // $credentials2 = request([$username, 'password']);
        if (! $token = auth($auth)->login($user_model)) {
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
            if(!$store_info){
                return $this->format_error(__('auth.error_token'));
            }
            return $this->format($store_info);
        }

        return $this->format(new UserCheckLogin($this->getUserInfo($auth)));
        
    }

    /**
     * 修改用户资料 function
     *
     * @param string $auth 前端允许修改和后台允许修改
     * @return void
     * @Description
     * @author hg <www.qingwuit.com>
     */
    public function editUser($auth='user'){
        $user_service = new UserService();
        $user_info = $user_service->getUserInfo();

        $config_service = new ConfigService;
        $sms = $config_service->getFormatConfig('alisms');

        $user_model = User::find($user_info['id']);

        // 昵称
        if(isset(request()->nickname)){
            $user_model->nickname = request()->nickname;
        }
        // 头像
        if(isset(request()->avatar)){
            $user_model->avatar = request()->avatar;
        }
        // 用户名
        if(isset(request()->username)){
            $user_model->username = request()->username;
        }
        // 性别
        if(isset(request()->sex)){
            $user_model->sex = abs(intval(request()->sex));
        }
        // 密码
        if(
            (isset(request()->password) && !empty(request()->password)) ||
            (isset(request()->pay_password) && !empty(request()->pay_password)) ||
            (isset(request()->phone) && !empty(request()->phone))
        ){
            // 判断
            if($auth == 'user'){

                // 如果并没有配置则直接提示未配置，不再做任何操作
                if(empty($sms['key']) || empty($sms['secret'])) {
                    $this->format_error(__('sms.sms_config_not_fond'));
                }

                if(!isset(request()->code) || empty(request()->code)){
                    return $this->format_error(__('users.edit_code_error'));
                }

                $sms_log_model = new SmsLog();
                if(empty($smsInfo = $sms_log_model->where([
                    'ip'    =>  request()->getClientIp(),
                    'content'    =>  request()->code,
                    'status'    =>  1,
                    'phone'   =>  $user_model->phone,
                ])->first())){
                    return $this->format_error(__('auth.sms_error'));
                }

                // 验证码失效 十分钟
                $ct = strtotime($smsInfo->created_at->format('Y-m-d H:i:s'));
                if(($ct+600)<time()){
                    return $this->format_error(__('auth.sms_invalid'));
                }
            }
            
            if(isset(request()->password) || !empty(request()->password)){
                $user_model->password = Hash::make(request()->password);
            }
            if(isset(request()->pay_password) || !empty(request()->pay_password)){
                if(strlen(request()->pay_password)!=6){
                    return $this->format_error(__('users.pay_password_len'));
                }
                $user_model->pay_password = md5($user_model->id.request()->pay_password);
            }
            if(isset(request()->phone) || !empty(request()->phone)){
                $sms_service = new SmsService();
                $phone_check = $sms_service->check_phone(request()->phone);
                if(!$phone_check){
                    return $this->format_error(__('sms.phone_error'));
                }
                $user_model->phone = md5($user_model->id.request()->phone);
            }

        }
        $user_model->save();
        return $this->format([]);
    }
    
}
