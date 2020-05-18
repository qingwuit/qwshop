<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Tools\Uploads;

class ConfigController extends BaseController
{
    // 站点设置
    public function web_config(Request $req,Config $config_model){
        $config_list = $config_model->get();
        $config_format = get_format_config($config_list);

        if(!$req->isMethod('post')){
            return $this->success_msg('Success',$config_format);
        }

        $save_data['web_name'] = $req->web_name;
        $save_data['web_url'] = $req->web_url;
        $save_data['keywords'] = $req->keywords;
        $save_data['description'] = $req->description;
        $save_data['logo'] = $req->logo;
        $save_data['mobile'] = $req->mobile;
        $save_data['email'] = $req->email;
        $save_data['icp'] = $req->icp;
        $save_data['web_status'] = $req->web_status;
        $save_data['goods_verify'] = $req->goods_verify;
        $save_data['web_close_info'] = $req->web_close_info;
        $save_data['cash_rate'] = $req->cash_rate;

        foreach($save_data as $k=>$v){
            $config_model->where('name',$k)->update(['val'=>$v]);
        }

        return $this->success_msg();

        
    }

    // 上传设置
    public function upload_config(Request $req,Config $config_model){
        $config_list = $config_model->get();
        $config_format = get_format_config($config_list);
        if(!$req->isMethod('post')){
            $oss_config = json_decode($config_format['alioss'],true);
            return $this->success_msg('Success',$oss_config);
        }

        $save_data['status'] = $req->status;
        $save_data['access_key'] = $req->access_key;
        $save_data['secret_access'] = $req->secret_access;
        $save_data['bucket'] = $req->bucket;
        $save_data['endpoint'] = $req->endpoint;
        
        $save_data_json = json_encode($save_data);
        $config_model->where(['name'=>'alioss'])->update(['val'=>$save_data_json]);
        return $this->success_msg();

    }

    // 地图配置
    public function map_config(Request $req,Config $config_model){
        $config_list = $config_model->get();
        $config_format = get_format_config($config_list);
        if(!$req->isMethod('post')){
            return $this->success_msg('Success',$config_format);
        }

        $save_data['baidu_ak'] = $req->baidu_ak;
        $save_data['amap_ak'] = $req->amap_ak;
        
        $config_model->where(['name'=>'baidu_ak'])->update(['val'=>$save_data['baidu_ak']]);
        $config_model->where(['name'=>'amap_ak'])->update(['val'=>$save_data['amap_ak']]);
        return $this->success_msg();
    }

    // 微信支付H5
    public function wxpay_h5_config(Request $req,Config $config_model){
        $config_list = $config_model->get();
        $config_format = get_format_config($config_list);
        if(!$req->isMethod('post')){
            $oss_config = json_decode($config_format['wxpay_h5'],true);
            return $this->success_msg('Success',$oss_config);
        }

        $save_data['appid'] = $req->appid;
        $save_data['appsecret'] = $req->appsecret;
        $save_data['mchid'] = $req->mchid;
        $save_data['key'] = $req->key;
        $save_data['notify_url'] = $req->notify_url;
        
        $save_data_json = json_encode($save_data);
        $config_model->where(['name'=>'wxpay_h5'])->update(['val'=>$save_data_json]);
        return $this->success_msg();
    }

    // 微信支付APP
    public function wxpay_app_config(Request $req,Config $config_model){
        $config_list = $config_model->get();
        $config_format = get_format_config($config_list);
        if(!$req->isMethod('post')){
            $oss_config = json_decode($config_format['wxpay_app'],true);
            return $this->success_msg('Success',$oss_config);
        }

        $save_data['appid'] = $req->appid;
        $save_data['appsecret'] = $req->appsecret;
        $save_data['mchid'] = $req->mchid;
        $save_data['key'] = $req->key;
        $save_data['notify_url'] = $req->notify_url;
        
        $save_data_json = json_encode($save_data);
        $config_model->where(['name'=>'wxpay_app'])->update(['val'=>$save_data_json]);
        return $this->success_msg();
    }

    // 微信支付JSAPI
    public function wxpay_jsapi_config(Request $req,Config $config_model){
        $config_list = $config_model->get();
        $config_format = get_format_config($config_list);
        if(!$req->isMethod('post')){
            $oss_config = json_decode($config_format['wxpay_jsapi'],true);
            return $this->success_msg('Success',$oss_config);
        }

        $save_data['appid'] = $req->appid;
        $save_data['appsecret'] = $req->appsecret;
        $save_data['mchid'] = $req->mchid;
        $save_data['key'] = $req->key;
        $save_data['notify_url'] = $req->notify_url;
        
        $save_data_json = json_encode($save_data);
        $config_model->where(['name'=>'wxpay_jsapi'])->update(['val'=>$save_data_json]);
        return $this->success_msg();
    }

    // 微信支付MINI 小程序
    public function wxpay_mini_config(Request $req,Config $config_model){
        $config_list = $config_model->get();
        $config_format = get_format_config($config_list);
        if(!$req->isMethod('post')){
            $oss_config = json_decode($config_format['wxpay_mini'],true);
            return $this->success_msg('Success',$oss_config);
        }

        $save_data['appid'] = $req->appid;
        $save_data['appsecret'] = $req->appsecret;
        $save_data['mchid'] = $req->mchid;
        $save_data['key'] = $req->key;
        $save_data['notify_url'] = $req->notify_url;
        
        $save_data_json = json_encode($save_data);
        $config_model->where(['name'=>'wxpay_mini'])->update(['val'=>$save_data_json]);
        return $this->success_msg();
    }

    // 支付宝支付 H5
    public function alipay_h5_config(Request $req,Config $config_model){
        $config_list = $config_model->get();
        $config_format = get_format_config($config_list);
        if(!$req->isMethod('post')){
            $oss_config = json_decode($config_format['alipay_h5'],true);
            return $this->success_msg('Success',$oss_config);
        }

        $save_data['appid'] = $req->appid;
        $save_data['public_key'] = $req->public_key;
        $save_data['private_key'] = $req->private_key;
        $save_data['notify_url'] = $req->notify_url;
        $save_data['return_url'] = $req->return_url;
        
        $save_data_json = json_encode($save_data);
        $config_model->where(['name'=>'alipay_h5'])->update(['val'=>$save_data_json]);
        return $this->success_msg();
    }

    // 支付宝支付 app
    public function alipay_app_config(Request $req,Config $config_model){
        $config_list = $config_model->get();
        $config_format = get_format_config($config_list);
        if(!$req->isMethod('post')){
            $oss_config = json_decode($config_format['alipay_app'],true);
            return $this->success_msg('Success',$oss_config);
        }

        $save_data['appid'] = $req->appid;
        $save_data['public_key'] = $req->public_key;
        $save_data['private_key'] = $req->private_key;
        $save_data['notify_url'] = $req->notify_url;
        $save_data['return_url'] = $req->return_url;
        
        $save_data_json = json_encode($save_data);
        $config_model->where(['name'=>'alipay_app'])->update(['val'=>$save_data_json]);
        return $this->success_msg();
    }

    // 支付宝支付 pc
    public function alipay_pc_config(Request $req,Config $config_model){
        $config_list = $config_model->get();
        $config_format = get_format_config($config_list);
        if(!$req->isMethod('post')){
            $oss_config = json_decode($config_format['alipay_pc'],true);
            return $this->success_msg('Success',$oss_config);
        }

        $save_data['appid'] = $req->appid;
        $save_data['public_key'] = $req->public_key;
        $save_data['private_key'] = $req->private_key;
        $save_data['notify_url'] = $req->notify_url;
        $save_data['return_url'] = $req->return_url;
        
        $save_data_json = json_encode($save_data);
        $config_model->where(['name'=>'alipay_pc'])->update(['val'=>$save_data_json]);
        return $this->success_msg();
    }

    // SMS 设置
    public function alisms_config(Request $req,Config $config_model){
        $config_list = $config_model->get();
        $config_format = get_format_config($config_list);
        if(!$req->isMethod('post')){
            $oss_config = json_decode($config_format['alisms'],true);
            return $this->success_msg('Success',$oss_config);
        }

        $save_data['key'] = $req->key;
        $save_data['secret'] = $req->secret;
        $save_data['sign_name'] = $req->sign_name;
        
        $save_data_json = json_encode($save_data);
        $config_model->where(['name'=>'alisms'])->update(['val'=>$save_data_json]);
        return $this->success_msg();
    }

    // 微信公众号 设置
    public function wechat_public_config(Request $req,Config $config_model){
        $config_list = $config_model->get();
        $config_format = get_format_config($config_list);
        if(!$req->isMethod('post')){
            $oss_config = json_decode($config_format['wechat_public'],true);
            return $this->success_msg('Success',$oss_config);
        }

        $save_data['url'] = $req->url;
        $save_data['token'] = $req->token;
        $save_data['name'] = $req->name;
        $save_data['appid'] = $req->appid;
        $save_data['secret'] = $req->secret;
        $save_data['mini_appid'] = $req->mini_appid;
        $save_data['mini_secret'] = $req->mini_secret;
        
        $save_data_json = json_encode($save_data);
        $config_model->where(['name'=>'wechat_public'])->update(['val'=>$save_data_json]);
        return $this->success_msg();
    }

    // 物流密钥 设置
    public function freight_config(Request $req,Config $config_model){
        $config_list = $config_model->get();
        $config_format = get_format_config($config_list);
        if(!$req->isMethod('post')){
            $oss_config = $config_format['freight_key'];
            return $this->success_msg('Success',$oss_config);
        }

        $config_model->where(['name'=>'freight_key'])->update(['val'=>$req->freight_key]);
        return $this->success_msg();
    }

    // 微信Oauth 配置 微信PC登录
    public function oauth_config(Request $req,Config $config_model){
        $config_list = $config_model->get();
        $config_format = get_format_config($config_list);
        if(!$req->isMethod('post')){
            $oss_config = json_decode($config_format['oauth_config'],true);
            return $this->success_msg('Success',$oss_config);
        }

        $save_data['appid'] = $req->appid;
        $save_data['app_secret'] = $req->app_secret;
        
        $save_data_json = json_encode($save_data);
        $config_model->where(['name'=>'oauth_config'])->update(['val'=>$save_data_json]);
        return $this->success_msg();
    }

    // 分销设置
    public function distribution_config(Request $req,Config $config_model){
        $config_list = $config_model->get();
        $config_format = get_format_config($config_list);
        if(!$req->isMethod('post')){
            $oss_config = json_decode($config_format['distribution'],true);
            return $this->success_msg('Success',$oss_config);
        }

        $save_data['status'] = $req->status;
        $save_data['distribution'] = $req->distribution;
        $save_data['distribution_1'] = $req->distribution_1;
        $save_data['distribution_2'] = $req->distribution_2;
        
        $save_data_json = json_encode($save_data);
        $config_model->where(['name'=>'distribution'])->update(['val'=>$save_data_json]);
        return $this->success_msg();

    }

    // 定时任务 自动执行配置
    public function task_time_config(Request $req,Config $config_model){
        $config_list = $config_model->get();
        $config_format = get_format_config($config_list);
        if(!$req->isMethod('post')){
            $oss_config = json_decode($config_format['task_time'],true);
            return $this->success_msg('Success',$oss_config);
        }

        $save_data['auto_confirm'] = $req->auto_confirm;
        $save_data['auto_comment'] = $req->auto_comment;
        $save_data['auto_cancel'] = $req->auto_cancel;
        
        $save_data_json = json_encode($save_data);
        $config_model->where(['name'=>'task_time'])->update(['val'=>$save_data_json]);
        return $this->success_msg();

    }


    public function logo_upload(Request $req){
        $uploads_model = new Uploads;
        $data['is_thumb'] = 1; // 缩略图
        $rs = $uploads_model->uploads($data);
        if($rs['status']){
            return $this->success_msg('Success',$rs['path']);
        }else{
            return $this->error_msg($rs['msg']);
        }
    }

    
}
