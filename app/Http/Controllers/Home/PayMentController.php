<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Models\Goods;
use App\Models\Groupbuy;
use App\Models\Order;
use App\Models\OrderGoods;
use App\Models\Users;
use Yansongda\Pay\Pay;
use Illuminate\Support\Facades\Log;

class PayMentController extends Controller
{
    // 阿里支付回调
    public function alipay(){
        return $this->pay_verify('alipay');
    }

    // 微信支付回调
    public function wxpay(){
        return $this->pay_verify('wxpay');
    }

    // 回调验证
    public function pay_verify($pay_name){

        if($pay_name == 'aliapy'){
            $out_trade_no = request()->input('out_trade_no');
            // 如果商户号没传回来，肯定报错
            if(empty($out_trade_no)){
                return;
            }
        }

        if($pay_name == 'wxpay'){
            $return_info = json_decode(json_encode(simplexml_load_string(file_get_contents("php://input"), 'SimpleXMLElement', LIBXML_NOCDATA)),true);
            $out_trade_no = $return_info['out_trade_no'];
            if(empty($out_trade_no)){
                return;
            }
        }

        $out_trade_no_arr = explode('|',$out_trade_no);
        $payment_name = $out_trade_no_arr[1];

        $order_status = false;

        try{
            //配置好支付的参数
            $this->get_payment_config($payment_name);

            // 微信和支付的回调验证
            $payment_name_arr = explode('_',$payment_name);
            if($payment_name_arr[0] == 'wxpay'){
                $wxpayObj = Pay::wechat($this->wx_config);
                $notify_info = $wxpayObj->verify();
            }elseif($payment_name_arr[0] == 'alipay'){
                $alipayObj = Pay::alipay($this->ali_config);
                $notify_info = $alipayObj->verify();
            }

            // 如果成功则来说处理订单信息 处理成功返回true
            $order_status = $this->handle_order($notify_info,$payment_name,$out_trade_no_arr[0]);
        } catch (\Exception $e) {
            // 验证失败 文档
            Log::channel('qwlog')->info('no:'.$out_trade_no.' '.$e->getMessage());
        }
        
        if($payment_name_arr[0] == 'wxpay' && $order_status){
            return $wxpayObj->success();
        }elseif($payment_name_arr[0] == 'alipay' && $order_status){
            return $alipayObj->success();
        }
    }

    // 处理订单信息 // 处理这边可以扩展redis
    private function handle_order($notify_info,$payment_name,$order_no){
        $payment_name_arr = explode('_',$payment_name);

        // 微信和支付宝
        if($payment_name_arr[0] == 'wxpay'){
            if($notify_info['result_code'] != 'SUCCESS'){
                return false;
            }
            
        }elseif($payment_name_arr[0] == 'alipay'){

            // 如果状态不为true 则表示支付失败
            if($notify_info['trade_status'] != 'TRADE_SUCCESS'){
                return false;
            }
        }

        $order_model = new Order(); // 实例化订单模型
        $groupbuy_model = new Groupbuy();// 实例化拼团模型
        $user_model = new Users;

        $order_info = $order_model->where('order_no',$order_no)->first();
        $user_info = $user_model->find($order_info['user_id']);

        if($order_info['pay_status'] == 1){
            return true;
        }

        $order_data = [
            'pay_type'      =>  $payment_name,
            'pay_time'      =>  time(),
            'pay_status'    =>  1,
        ];
        $rs = $order_model->where('order_no',$order_no)->update($order_data);

        // 开始生成产品的销量
        $order_list = $order_model->select('id','is_groupbuy','user_id','seller_id','total_price','balance')->where('order_no',$order_no)->get();
        $order_ids = [];

        $pay_money = 0; // 第三方支付的金额

        // 循环出订单ID
        foreach($order_list as $v){
            $order_ids[] = $v['id'];
            $pay_money += $v['total_price']-$v['balance'];
            
        }

        // 将支付的金额放入冻结资金
        $user_model->money_change('订单冻结','frozen_money',$pay_money,$user_info,'order_no:'.$order_no,'余额支付');

        $order_goods_model = new OrderGoods();
        $order_goods_list = $order_goods_model->whereIn('order_id',$order_ids)->get();

        // 生成参团信息
        if($order_list[0]['is_groupbuy'] == 1){
            $groupbuy_need = $order_goods_list[0];
            $groupbuy_need['order_id'] = $order_list[0]['id'];
            $groupbuy_need['seller_id'] = $order_list[0]['seller_id'];
            $groupbuy_need['order_no'] = $order_no;
            $groupbuy_need['user_info'] = $user_info;
            $groupbuy_model->add_groupbuy($groupbuy_need);
        }

        // 循环获取产品信息加上销量
        $goods_model = new Goods();
        foreach($order_goods_list as $v){
            $goods_model->where('id',$v['goods_id'])->increment('goods_sale_num',$v['goods_num']);
        }
        // 产品销量 结束

        if(empty($rs)){
            return false;
        }else{
            return true;
        }

    }

    // 配置好支付的参数
    protected function get_payment_config($payment_name){

        // 获取配置的模型
        $config_model = new Config();
        $config_info = $config_model->get_format_config();
        $db_config = [];

        switch($payment_name){
            case 'wxpay_native':
                // 配置支付密钥
                $db_config = json_decode($config_info['wxpay_jsapi'],true);
                $this->wx_config['app_id'] = $db_config['appid'];
                $this->wx_config['mch_id'] = trim($db_config['mchid']);
                $this->wx_config['key'] = $db_config['key'];
                $this->wx_config['notify_url'] = $db_config['notify_url'];
                
                break;
            case 'wxpay_jsapi':
                break;
            
            // 阿里支付
            case 'alipay_pc':
                // 配置支付密钥
                $db_config = json_decode($config_info['alipay_pc'],true);
                $this->ali_config['app_id'] = $db_config['appid'];
                $this->ali_config['ali_public_key'] = $db_config['public_key'];
                $this->ali_config['private_key'] = $db_config['private_key'];
                $this->ali_config['notify_url'] = $db_config['notify_url'];
                $this->ali_config['return_url'] = $db_config['return_url'];
                break;
        }
    }


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
