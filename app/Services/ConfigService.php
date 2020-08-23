<?php
namespace App\Services;

use App\Models\Config;
use App\Models\SmsLog;

class ConfigService extends BaseService{
    
    // 获取格式化配置
    public function getFormatConfig($name=''){
        $config_model = new Config;
        $list = $config_model->get();
        $data = [];
        foreach($list as $v){
            $data[$v['name']] = $v['val'];
        }

        $data = $this->pay_format($data);
        if(empty($name)){
            return $data;
        }
        return $data[$name];
    }

    // 获取短信配置信息
    public function getSmsConfig($name='register'){
        $sms_log_model = new SmsLog();
        $sign_info = $sms_log_model->where('name',$name)->first();
        $info = json_decode($this->getFormatConfig('alisms'),true);
        $info['sign_name'] = $sign_info->val;
        return $info;
    }

    public function pay_format($data){
        if(empty($data['wechat_pay'])){
            $info = [
                'app_id'=>'',
                'app_secret'=>'',
                'mch_id'=>'',
                'key'=>'',
                'notify_url'=>'',
            ];
            $data['wechat_pay'] = [
                'wechat_h5'=>$info,
                'wechat_public'=>$info,
                'wechat_app'=>$info,
                'wechat_mini'=>$info,
                'wechat_scan'=>$info,
            ];
        }else{
            $data['wechat_pay'] = json_decode($data['wechat_pay'],true);
        }
        if(empty($data['ali_pay'])){
            $info = [
                'app_id'=>'',
                'ali_public_key'=>'',
                'private_key'=>'',
                'return_url'=>'',
                'notify_url'=>'',
            ];
            $data['ali_pay'] = [
                'ali_h5'=>$info,
                'ali_app'=>$info,
                'ali_mini'=>$info,
                'ali_scan'=>$info,
            ];
        }else{
            $data['ali_pay'] = json_decode($data['ali_pay'],true);
        }
        if(empty($data['alioss'])){
            $data['alioss'] = [
                'status'=>false,
                'access_id'=>'',
                'access_key'=>'',
                'bucket'=>'',
                'endpoint'=>'',
                'cdnDomain'=>'',
                'ssl'=>false,
                'isCName'=>false,
            ];
        }else{
            $data['alioss'] = json_decode($data['alioss'],true);
        }
        if(empty($data['alisms'])){
            $data['alisms'] = [
                'key'=>'',
                'secret'=>'',
            ];
        }else{
            $data['alisms'] = json_decode($data['alisms'],true);
        }
        return $data;
    }

}