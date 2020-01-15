<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Models\Order;
use Yansongda\Pay\Pay;
use App\Tools\Helper;
use Endroid\QrCode\QrCode;  // 生成二维码

class PayController extends BaseController
{

    public function pay(Request $req,Config $config_model,Order $order_model,Helper $helperObj){

        $payment_name = $req->payment_name??'';  // 获取支付方式
        $order_no = $req->order_no??''; // 订单号

        $user_info = auth()->user();

        if(empty($payment_name) || empty($order_no)){
            return $this->error_msg('支付方法错误或订单号非法');
        }

        $config_info = $config_model->get_format_config();
        $db_config = [];
        $pay_order_info = [];  // 传入第三方支付的订单数据
        $order_list = $order_model->where('order_no',$order_no)->get();// 数据库订单信息

        $payment_name_type = explode('_',$payment_name)[0]; // 微信还是支付宝

        if($payment_name_type == 'wxpay'){  // 微信支付
            // 支付订单信息
            $pay_order_info['out_trade_no'] = $order_list[0]['order_no'].'|'.$payment_name;
            $pay_order_info['total_fee'] = 0;
            foreach($order_list as $v){
                $pay_order_info['total_fee'] +=  $v['total_price']-$v['balance'];
            }
            $pay_order_info['total_fee'] = $pay_order_info['total_fee']*100;
            $pay_order_info['body'] = $order_list[0]['order_name'];
        }else{
            $pay_order_info['out_trade_no'] = $order_list[0]['order_no'].'|'.$payment_name;
            $pay_order_info['total_amount'] = 0;
            foreach($order_list as $v){
                $pay_order_info['total_amount'] +=  $v['total_price']-$v['balance'];
            }
            $pay_order_info['subject'] = $order_list[0]['order_name'];
        }

        if(empty($order_list)){
            return $this->error_msg('订单不存在！');
        }
        
        switch($payment_name){
            case 'wxpay_native':
                // 配置支付密钥
                $db_config = json_decode($config_info['wxpay_jsapi'],true);
                $this->wx_config['app_id'] = $db_config['appid'];
                $this->wx_config['mch_id'] = trim($db_config['mchid']);
                $this->wx_config['key'] = $db_config['key'];
                $this->wx_config['notify_url'] = $db_config['notify_url'];
                $rs = Pay::wechat($this->wx_config)->scan($pay_order_info);
                $qr_path = $helperObj->create_qrcode($rs['code_url'],'wxpay_qr_code/'.$user_info['id'].$order_no.'/','sacn_pay');
                $rs['qr_code'] = $config_info['web_url'].$qr_path;
                break;
            case 'wxpay_jsapi':
                break;
            
            // 阿里支付
            case 'alipay_pc':
                // 配置支付密钥
                $db_config = json_decode($config_info['alipay_pc'],true);
                $this->ali_config['app_id'] = $db_config['appid'];
                $this->ali_config['public_key'] = $db_config['public_key'];
                $this->ali_config['private_key'] = $db_config['private_key'];
                $this->ali_config['notify_url'] = $db_config['notify_url'];
                $this->ali_config['return_url'] = $db_config['return_url'];
                $rs = Pay::alipay($this->ali_config)->web($pay_order_info);
                $rs = (array)$rs;
                $rs = $rs["\0*\0content"];
                break;
        }

        return $this->success_msg('ok',$rs);

    }

    //  验证二维码支付是否正确
    public function check_wxpay_native(Request $req,Config $config_model){

        $out_trade_no = $req->out_trade_no;

        $config_info = $config_model->get_format_config();
        // 配置支付密钥
        $db_config = json_decode($config_info['wxpay_jsapi'],true);
        $this->wx_config['app_id'] = $db_config['appid'];
        $this->wx_config['mch_id'] = trim($db_config['mchid']);
        $this->wx_config['key'] = $db_config['key'];
        $this->wx_config['notify_url'] = $db_config['notify_url'];
        $rs = Pay::wechat($this->wx_config)->find($out_trade_no);
        $data = json_decode($rs,true);
        if($data['trade_state'] != 'NOTPAY'){
            return $this->success_msg('ok',$data);
        }else{
            return $this->error_msg('还未支付',$data);
        }
        
    }


    // 显示二维码接口
    // public function qr_show($code_url){
    //     $qrCode = new QrCode($code_url);
    //     // 指定内容类型
    //     header('Content-Type: '.$qrCode->getContentType());
    //     // 输出二维码
    //     echo $qrCode->writeString();
    // }


    protected $wx_config = [
        'appid' => 'wxb3fxxxxxxxxxxx', // APP APPID
        'app_id' => 'wxb3fxxxxxxxxxxx', // 公众号 APPID
        'miniapp_id' => 'wxb3fxxxxxxxxxxx', // 小程序 APPID
        'mch_id' => '14577xxxx',
        'key' => 'mF2suE9sU6Mk1Cxxxxxxxxxxx',
        'notify_url' => 'www.qingwuit.com',
        // 'cert_client' => './cert/apiclient_cert.pem', // optional，退款等情况时用到
        // 'cert_key' => './cert/apiclient_key.pem',// optional，退款等情况时用到
        // 'log' => [ // optional
        //     'file' => './logs/wechat.log',
        //     'level' => 'debug', // 建议生产环境等级调整为 info，开发环境为 debug
        //     'type' => 'single', // optional, 可选 daily.
        //     'max_file' => 30, // optional, 当 type 为 daily 时有效，默认 30 天
        // ],
        // 'http' => [ // optional
        //     'timeout' => 5.0,
        //     'connect_timeout' => 5.0,
        //     // 更多配置项请参考 [Guzzle](https://guzzle-cn.readthedocs.io/zh_CN/latest/request-options.html)
        // ],
        // 'mode' => 'dev', // optional, dev/hk;当为 `hk` 时，为香港 gateway。
    ];

    protected $ali_config = [
        'app_id' => '2016082000295641',
        'notify_url' => '',
        'return_url' => '',
        'ali_public_key' => '',
        // 加密方式： **RSA2**  
        'private_key' => '',
        // 'log' => [ // optional
        //     'file' => './logs/alipay.log',
        //     'level' => 'debug', // 建议生产环境等级调整为 info，开发环境为 debug
        //     'type' => 'single', // optional, 可选 daily.
        //     'max_file' => 30, // optional, 当 type 为 daily 时有效，默认 30 天
        // ],
        // 'http' => [ // optional
        //     'timeout' => 5.0,
        //     'connect_timeout' => 5.0,
        //     // 更多配置项请参考 [Guzzle](https://guzzle-cn.readthedocs.io/zh_CN/latest/request-options.html)
        // ],
        'mode' => 'dev', // optional,设置此参数，将进入沙箱模式
    ];
}
