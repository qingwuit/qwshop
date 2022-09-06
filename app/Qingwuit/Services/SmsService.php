<?php

namespace App\Qingwuit\Services;

use Overtrue\EasySms\EasySms;

class SmsService extends BaseService
{
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
    public function sendSms($phone, $name)
    {

        // 检测手机号码
        if (!$this->check_phone($phone)) {
            return $this->formatError(__('tip.sms.phoneError'));
        }

        $configService = $this->getService('Configs');
        $alisms = $configService->getFormatConfig('sms'); // 获取下签名密钥
        $sms = $this->getService('Sms', true)->where('name', $name)->first(); // 获取签名配置

        if (!$sms) {
            return $this->formatError(__('tip.sms.signEmpty'));
        }

        $this->configs['gateways']['aliyun']['access_key_id'] = $alisms['key'];
        $this->configs['gateways']['aliyun']['access_key_secret'] = $alisms['secret'];
        $this->configs['gateways']['aliyun']['sign_name'] = $sms['content'];

        $easySms = new EasySms($this->configs);

        $sendBefore = $this->sendBefore($phone, $name);
        if (!$sendBefore['status']) {
            return $this->formatError($sendBefore['msg']);
        }

        $rand = mt_rand(1000, 9999);


        try {
            // 插入日志
            $smsLog = $this->getService('SmsLog', true);
            $saveData = [
                'phone'     =>  $phone,
                'content'   =>  $rand,
                'status'    =>  1,
                'name'      =>  $name,
                'error_msg' =>  '[]',
                'ip'        =>  request()->getClientIp(),
            ];
            $smsLog = $smsLog->create($saveData);

            $rs = $easySms->send($phone, [
                'content'  => '',
                'template' => $sms['code'],
                'data' => ['code' => $rand],
            ]);
        } catch (\Overtrue\EasySms\Exceptions\NoGatewayAvailableException $e) {
            $error_msg = $e->getException('aliyun')->getMessage();
            $smsLog->error_msg = $error_msg;
            $smsLog->status = 0;
            $smsLog->save();
            return $this->formatError(__('tip.sms.sendErr'));
        }


        if (isset($rs) && $rs['aliyun']['status'] == 'success' && $rs['aliyun']['result']['Code'] == 'OK') {
            return $this->format([], __('tip.sms.send_success'));
        } else {
            $smsLog->error_msg = json_encode($rs);
            $smsLog->status = 0;
            $smsLog->save();
            return $this->formatError(__('tip.sms.sendErr'));
        }
    }

    // 验证短信是否正确
    public function checkSms($phone, $code, $smsName = 'register')
    {
        $failureTime = 600; // 失效时间
        if (empty($smsInfo = $this->getService('SmsLog', true)->where([
            'ip'    =>  request()->getClientIp(),
            'content'    =>  $code,
            'status'    =>  1,
            'name'      =>  $smsName,
            'phone'   =>  $phone,
        ])->first())) {
            return $this->formatError(__('tip.sms.smsErr'));
        }

        // 验证码失效 十分钟
        $ct = strtotime($smsInfo->created_at->format('Y-m-d H:i:s'));
        if (($ct + $failureTime) < time()) {
            return $this->formatError(__('tip.sms.smsInvalid'));
        }

        return $this->format();
    }

    // 短信判断是否能发送
    public function sendBefore($phone, $name)
    {
        // IP 加时间验证
        $smsInfo = $this->getService('SmsLog', true)->where('ip', request()->getClientIp())->where('phone', $phone)->where('name', $name)->orderBy('id', 'desc')->first();
        // 验证码失效
        if ($smsInfo) {
            $ct = strtotime($smsInfo->created_at->format('Y-m-d H:i:s'));
            if (($ct + 30) > time()) {
                return $this->formatError(__('tip.sms.reSend'));
            }
        }


        // 创建注册的时候判断是否存在账号
        if ($name == 'register') {
            if ($this->getService('User', true)->where('phone', $phone)->exists()) {
                return $this->formatError(__('tip.sms.phoneExists'));
            }
        }


        // 忘记密码 和 修改资料的时候判断是否存在
        if ($name == 'forget_password' || $name == 'edit_user') {
            if (!$this->getService('User', true)->where('phone', $phone)->exists()) {
                return $this->formatError(__('tip.sms.phoneNoExists'));
            }
        }

        return $this->format([]);
    }

    // 验证是否手机号码正确
    public function check_phone($phone)
    {
        $g = "/^1[3456789]\d{9}$/";
        if (preg_match($g, $phone)) {
            return true;
        }
        return false;
    }
}
