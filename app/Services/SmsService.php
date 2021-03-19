<?php
namespace App\Services;

use App\Models\SmsLog;
use App\Models\User;
use Overtrue\EasySms\EasySms;

class SmsService extends BaseService{
    private $configs = [
        // HTTP 请求的超时时间（秒）
        'timeout' => 5.0,

        // 默认发送配置
        'default' => [
            // 网关调用策略，默认：顺序调用
            'strategy' => \Overtrue\EasySms\Strategies\OrderStrategy::class,

            // 默认可用的发送网关
            'gateways' => [
                'aliyun',
            ],
        ],
        // 可用的网关配置
        'gateways' => [
            'errorlog' => [
                'file' => '/tmp/easy-sms.log',
            ],
            'aliyun' => [
                'access_key_id' => '',
                'access_key_secret' => '',
                'sign_name' => '',
            ],
            //...
        ],
    ];

    /**
     * send_sms function
     *
     * @param Config $config_model
     * @param [String] $phone  手机号码
     * @param [String] $name  模版对应英文名
     * @return void
     * @Description 发送短信
     * @author hg <www.qingwuit.com>
     */
    public function sendSms($phone,$name){
    	
    	// 检测手机号码
    	if(!$this->check_phone($phone)){
    		return $this->format_error(__('sms.phone_error'));
    	}
    	
        $config_service = new ConfigService();
        $alisms = $config_service->getSmsConfig($name);
        $this->configs['gateways']['aliyun']['access_key_id'] = $alisms['key'];
        $this->configs['gateways']['aliyun']['access_key_secret'] = $alisms['secret'];
        $this->configs['gateways']['aliyun']['sign_name'] = $alisms['sign_name'];

        $easySms = new EasySms($this->configs);

        $sendBefore = $this->sendBefore($phone,$name);
        if(!$sendBefore['status']){
            return $this->format_error($sendBefore['msg']);
        }

        $rand = mt_rand(1000,9999);

        

        try{
            // 插入日志
            $sms_log_model = new SmsLog();
            $saveData = [
                'phone'     =>  $phone,
                'content'      =>  $rand,
                'status'    =>  1,  
                'name'      =>  $name,
                'ip'        =>  request()->getClientIp(),
            ];
            $sms_log_model = $sms_log_model->create($saveData);

            $rs = $easySms->send($phone, [
                'content'  => '',
                'template' => $alisms['code'],
                'data' => ['code'=>$rand],
            ]);
        } catch (\Overtrue\EasySms\Exceptions\NoGatewayAvailableException $e) {
        	$error_msg = $e->getException('aliyun')->getMessage();
            $sms_log_model->error_msg = $error_msg;
            $sms_log_model->status = 0;
            $sms_log_model->save();
            return $this->format_error(__('sms.send_error'));
        }
        

        if(isset($rs) && $rs['aliyun']['status'] == 'success' && $rs['aliyun']['result']['Code'] == 'OK'){
            return $this->format([],__('sms.send_success'));
        }else{
            $sms_log_model->error_msg = json_encode($rs);
            $sms_log_model->status = 0;
            $sms_log_model->save();
            return $this->format_error(__('sms.send_error'));
        }
        
    }

    // 验证短信是否正确
    public function checkSms($phone,$code){
        $failureTime = 600; // 失效时间
        $sms_log_model = new SmsLog();
        if(empty($smsInfo = $sms_log_model->where([
            'ip'    =>  request()->getClientIp(),
            'content'    =>  $code,
            'status'    =>  1,
            'phone'   =>  $phone,
        ])->first())){
            return $this->format_error(__('auth.sms_error'));
        }

        // 验证码失效 十分钟
        $ct = strtotime($smsInfo->created_at->format('Y-m-d H:i:s'));
        if(($ct+$failureTime)<time()){
            return $this->format_error(__('auth.sms_invalid'));
        }

        return $this->format();
    }

    // 短信判断是否能发送
    public function sendBefore($phone,$name){
        // IP 加时间验证
        $sms_log_model = new SmsLog();
        $smsInfo = $sms_log_model->where('ip',request()->getClientIp())->where('phone',$phone)->where('name',$name)->orderBy('id','desc')->first();
        // 验证码失效 
        if(!empty($smsInfo)){
            $ct = strtotime($smsInfo->created_at->format('Y-m-d H:i:s'));
            if(($ct+20)<time()){
                return $this->format_error(__('sms.re_send'));
            }
        }
        

        // 创建注册的时候判断是否存在账号
        if($name == 'register'){
            $user_model = new User();
            if($user_model->where('phone',$phone)->exists()){
                return $this->format_error(__('auth.user_exists'));
            }
        }


        // 忘记密码 和 修改资料的时候判断是否存在
        if($name == 'forget_password' || $name == 'edit_user'){
            $user_model = new User();
            if(!$user_model->where('phone',$phone)->exists()){
                return $this->format_error(__('auth.user_not_exists'));
            }
        }

        return $this->format([]);
    }

    // 验证是否手机号码正确
    public function check_phone($phone){
    	$g = "/^1[3456789]\d{9}$/";
    	if(preg_match($g, $phone)){
    		return true;
    	}
    	return false;
    }
}
