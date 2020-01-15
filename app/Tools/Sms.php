<?php
namespace App\Tools;

use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Models\SmsLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use Overtrue\EasySms\EasySms;


// 短信方法
class Sms extends Controller{
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
     * @param [String] $code  模版
     * @param [Array] $data  内容 $data['code'] 例如的验证码
     * @param [String] $type  类型
     * @return void
     * @Description 发送短信
     * @author hg <www.qingwuit.com>
     */
    public function send_sms($phone,$code,$data,$type='1'){
        $config_model = new Config();
        $sms_log_model = new SmsLog();
        $config_list = $config_model->get();
        $config_format = get_format_config($config_list);
        $alisms = json_decode($config_format['alisms'],true);
        $this->configs['gateways']['aliyun']['access_key_id'] = $alisms['key'];
        $this->configs['gateways']['aliyun']['access_key_secret'] = $alisms['secret'];
        $this->configs['gateways']['aliyun']['sign_name'] = $alisms['sign_name'];

        $easySms = new EasySms($this->configs);
        $rs = $easySms->send($phone, [
            'content'  => '',
            'template' => $code,
            'data' => $data,
        ]);

        $saveData = [
            'phone'     =>  $phone,
            'code'      =>  json_encode($data),
            'is_type'   =>  $type,  
            'name'      =>  $this->get_cn($type),
            'add_time'  =>  time(),
        ];

        if($rs['aliyun']['status'] == 'success' && $rs['aliyun']['result']['Code'] == 'OK'){
            $saveData['status'] = 1; // 发送成功
            $sms_log_model->insert($saveData);
            return ['code'=>200,'data'=>'Success','msg'=>'发送成功'];
        }else{
            $sms_log_model->insert($saveData);
            return ['code'=>500,'data'=>json_encode($rs),'msg'=>'发送失败'];
        }
        
    }

    /**
     * Undocumented function
     *
     * @param [type] $email  发送邮件
     * @param [type] $content 发送内容
     * @return void
     * @Description
     * @author hg <www.qingwuit.com>
     */
    public function send_email($email,$content,$subject="青梧信息科技",$is_type='4'){
        $sms_log_model = new SmsLog();
        $data['email'] = $email;
        $data['content'] = '验证码:'.$content;
        $data['subject'] = $subject;
        Mail::send('emails',['content'=>$data['content']],function($obj) use($email,$subject)
        {
            $obj->to($email)->subject($subject);
        });

        if( count(Mail::failures()) > 0 ){
        	return ['code'=>500,'data'=>'Success','msg'=>'发送失败'];
        }else{
            $saveData = [
                'phone'     =>  $email,
                'code'      =>  json_encode(['code'=>$content]),
                'is_type'   =>  $is_type,  
                'name'      =>  $this->get_cn($is_type),
                'add_time'  =>  time(),
            ];
            $sms_log_model->insert($saveData);
            return ['code'=>200,'data'=>'Success','msg'=>'发送成功'];
        }
    }


    // 类型中文名称
    public function get_cn($val){
        $name = '注册';
        switch($val){
            case 1:
                $name = '登录';
            case 2:
                $name = '注册';
            case 3:
                $name = '手机认证';
            case 4:
                $name = '邮件认证';
            case 5:
                $name = '忘记密码';
        }
        return $name;
    }
}