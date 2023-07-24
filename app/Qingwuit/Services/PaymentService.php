<?php

namespace App\Qingwuit\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yansongda\Pay\Pay;

class PaymentService extends BaseService
{

    // 第三方支付回调 paymentName [wechat | alipay]
    // $config 是多租户配置
    public function payment($paymentName = 'wechat', $device = 'web', $config = 'default')
    {
        $this->setConfig($paymentName, $device, $config);
        $result = Pay::$paymentName($this->config)->callback(null, ['_config' => $config]);

        try {
            DB::beginTransaction();
            if ($paymentName == 'wechat') $out_trade_no = $result->resource['ciphertext']['out_trade_no'];
            if ($paymentName == 'alipay') $out_trade_no = $result->out_trade_no;
            if (empty($out_trade_no)) throw new \Exception('not found out_trade_no');

            // REDIS 队列处理第三方回调
            if (env('APP_REDIS')) {
                $this->getJob('Payment', [
                    'payment_name'  => $paymentName,
                    'device'        => $device,
                    'config'        => $config,
                    'out_trade_no'  => $out_trade_no,
                    'result'        => $result
                ])->onQueue('payment');
                return Pay::$paymentName($this->config)->success();
            }

            $orderPay = $this->getService('OrderPay', true)->where('pay_no', $out_trade_no)->first();
            $paySuccessData = $this->paySuccess($paymentName, $orderPay, $result);
            if (!is_array($paySuccessData)) {
                DB::commit();
                return $paySuccessData;
            }
            if (!$paySuccessData['status']) throw new \Exception($paySuccessData['msg']);
            DB::commit();
            return $paySuccessData;
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return $this->formatError($e->getMessage());
        }
    }

    /**
     * 调取第三方支付 function
     *
     * @param [String] $paymentName  支付类型 如：wechat_jsapi
     * @param [String] $device 设备[web | app | wap | h5] 详细文档 https://pay.yansongda.cn/docs/v3/alipay/pay.html
     * @param [Array] $orderPay 支付订单的支付数据 order_pay 表内数据
     * @param [Boolean] $recharge 是否是充值方式
     * @return Mix
     * @Description
     * @author hg <www.qingwuit.com>
     */
    public function pay($paymentName = 'wechat', $device = 'web', $orderPay = [], $recharge = false, $config = 'default')
    {
        if (empty($orderPay)) {
            return $this->formatError(__('tip.payment.orderErr'));
        }

        // 支付配置
        $this->payData = [];
        $this->payData['_config'] = $config;

        // 余额支付处理
        if ($paymentName == 'balance') {
            // 支付订单回调处理  下面的处理看能否整理成公共
            $orderPayModel = $this->getService('OrderPay', true)->where('pay_no', $orderPay['pay_no'])->first();
            try {
                DB::beginTransaction();
                $paySuccessData = $this->paySuccess($paymentName, $orderPayModel);
                if (!$paySuccessData['status']) throw new \Exception($paySuccessData['msg']);
                DB::commit();
                return $this->format($paySuccessData['data']);
            } catch (\Exception $e) {
                DB::rollback();
                Log::error($e->getMessage());
                return $this->formatError($e->getMessage());
            }
        }

        // orderPay 的数据进行赋值给payData
        // 充值管理
        if ($recharge) {
            $this->payData['name'] = __('tip.payment.onlineRecharge');
        }

        // 微信
        if ($paymentName == 'wechat') {
            $this->payData['out_trade_no'] = $orderPay['pay_no'];
            $this->payData['description'] = $recharge ? $this->payData['name'] : $orderPay['name'];
            $this->payData['amount'] = [
                'total' => $orderPay['total'] * 100,
            ];

            // 小程序和公众号需要openID
            if (in_array($device, ['mp', 'mini'])) {
                $this->payData['payer'] = [
                    'openid' => request('openid', ''),
                ];
            }

            // 如果是wap 必填场景信息
            if (in_array($device, ['wap'])) {
                $this->payData['scene_info'] = [
                    'payer_client_ip' => request()->getClientIp(),
                    'h5_info' => [
                        'type' => 'Wap'
                    ]
                ];
            }
        }

        // 支付宝
        if ($paymentName == 'alipay') {
            $this->payData['out_trade_no'] = $orderPay['pay_no'];
            $this->payData['subject'] = $recharge ? $this->payData['name'] : $orderPay['name'];
            $this->payData['total_amount'] = $orderPay['total'];
        }

        try {
            $this->setConfig($paymentName, $device, $config);
            $result = Pay::$paymentName($this->config)->$device($this->payData);
            if (in_array($device, ['app', 'wap', 'web'])) return $this->format($result->getBody()->getContents());
            return $this->format($result);
        } catch (\Exception $e) {
            Log::error('[' . $paymentName . ']:' . $e->getMessage());
            dd($e->getMessage());
            return $this->formatError(__('tip.payment.paymentFailed'));
        }
    }

    // 支付成功后处理订单
    public function paySuccess($paymentName = 'balance', $orderPay = null, $result = null)
    {
        if (empty($orderPay)) return $this->formatError('pay success params $orderPay empty .');

        // 支付订单回调处理  下面的处理看能否整理成公共
        if ($orderPay->pay_status == 1) return $this->formatError(__('tip.order.payed')); // 订单已经支付
        $trade_no = '-';

        if ($paymentName == 'balance') {
            $resp = $this->getService('MoneyLog')->edit(['money' => -$orderPay->total, 'user_id' => $orderPay->belong_id]);
            if (!$resp['status']) return $this->formatError($resp['msg']);
        } else {
            if ($paymentName == 'wechat') {
                if ($result->event_type != 'TRANSACTION.SUCCESS' && $result->resource['ciphertext']['trade_state'] != 'SUCCESS') {
                    Log::error($result);
                    throw new \Exception('wechat pay error - ' . $result->resource['ciphertext']['out_trade_no']);
                }
                $trade_no = $result->resource['ciphertext']['transaction_id'];
            }
            if ($paymentName == 'alipay') {
                if ($result->trade_status != 'TRADE_SUCCESS') {
                    Log::error($result);
                    throw new \Exception('alipay pay error - ' . $result->out_trade_no);
                }
                $trade_no = $result->trade_no;
            }

            // 金额日志 用户账户变更 第三方支付的只要日志不要实质删除用户余额
            $this->getService('MoneyLog')->edit([
                'user_id'  =>  $orderPay->belong_id,
                'money'  =>  -$orderPay->total,
                'info'  =>  $trade_no,
                'isLog'  =>  true,
            ]);
        }

        $orderPay->trade_no = $trade_no;
        $orderPay->pay_status = 1;
        $orderPay->pay_time = now();
        $orderPay->balance = $orderPay->total;
        $orderPay->save();

        // 如果是充值
        if ($orderPay->is_recharge == 1) {
            $this->getService('MoneyLog')->edit([
                'name'  =>  __('tip.payment.onlineRecharge'),
                'user_id'  =>  $orderPay->belong_id,
                'money'  =>  $orderPay->total,
            ]);
            return $this->format();
        }

        // 如果不是充值 
        // 增加销量 - 其他支付回调的时候也要处理一遍
        if (empty($orderPay->order_ids)) return $this->formatError('paySuccess $orderPay fail .');
        $orderIds = explode(',', $orderPay->order_ids);
        $orderGoodsList = $this->getService('OrderGoods', true)->whereIn('order_id', $orderIds)->get();
        if (!$orderGoodsList->isEmpty()) {
            $orderService = $this->getService('Order');
            foreach ($orderGoodsList as $v) {
                $orderService->orderSale($v->goods_id, $v->buy_num, 1);
            }
        }
        // 订单状态修改
        $this->getService('Order', true)->whereIn('id', $orderIds)->update([
            'order_status'  =>  2,
            'pay_time'      =>  now(),
            'payment_name'  =>  $paymentName,
        ]);

        // 分销处理
        try {
            $this->getService('Distribution')->addDisLog($orderIds);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

        // 余额支付需要返回信息 第三方支付需要返回指定信息给回调服务器
        return $paymentName == 'balance' ? $this->format() : Pay::$paymentName($this->config)->success();
    }

    // 检测是否订单支付成功
    public function check($orderId)
    {
        if (empty($orderId)) {
            return $this->formatError('order not found.');
        }
        $order = $this->getService('Order', true)->where('id', $orderId)->where('order_status', '>', 1)->first();
        if (!$order) {
            return $this->formatError();
        }
        return $this->format();
    }

    // 配置支付密钥
    public function setConfig($paymentName, $device = 'web', $config = 'default')
    {
        $this->config['logger']['file'] = storage_path('logs/alipay.log'); // 日志目录
        $pay = $this->getService('Configs')->getFormatConfig('pay');
        // 给证书加上绝对链接
        $payServiceConfig = $pay[$paymentName . $device];
        if (!empty($payServiceConfig)) {
            foreach ($payServiceConfig as $k => $v) {
                if (stripos($k, 'cert') != false && $k != 'app_secret_cert') {
                    if (stripos($k, 'http：//') == false && stripos($k, 'https：//') == false) {
                        $payServiceConfig[$k] = public_path() . $v;
                    } else {
                        $payServiceConfig[$k] = $v;
                    }
                }
            }
        }

        $this->config[$paymentName][$config] = array_merge($this->config[$paymentName][$config], $payServiceConfig);
    }

    // 支付展示信息
    protected $payData = [];

    protected $config = [
        'alipay' => [
            'default' => [
                // 必填-支付宝分配的 app_id
                'app_id' => '2016082000295641',
                // 必填-应用私钥 字符串或路径
                'app_secret_cert' => '',
                // 必填-应用公钥证书 路径
                'app_public_cert_path' => '/Users/yansongda/pay/cert/appCertPublicKey_2016082000295641.crt',
                // 必填-支付宝公钥证书 路径
                'alipay_public_cert_path' => '/Users/yansongda/pay/cert/alipayCertPublicKey_RSA2.crt',
                // 必填-支付宝根证书 路径
                'alipay_root_cert_path' => '/Users/yansongda/pay/cert/alipayRootCert.crt',
                'return_url' => 'https://yansongda.cn/alipay/return',
                'notify_url' => 'https://yansongda.cn/alipay/notify',
                // 选填-服务商模式下的服务商 id，当 mode 为 Pay::MODE_SERVICE 时使用该参数
                'service_provider_id' => '',
                // 选填-默认为正常模式。可选为： MODE_NORMAL, MODE_SANDBOX, MODE_SERVICE
                'mode' => Pay::MODE_NORMAL,
            ]
        ],
        'wechat' => [
            'default' => [
                // 必填-商户号，服务商模式下为服务商商户号
                'mch_id' => '',
                // 必填-商户秘钥
                'mch_secret_key' => '',
                // 必填-商户私钥 字符串或路径
                'mch_secret_cert' => '',
                // 必填-商户公钥证书路径
                'mch_public_cert_path' => '',
                // 必填
                'notify_url' => 'https://yansongda.cn/wechat/notify',
                // 选填-公众号 的 app_id
                'mp_app_id' => '2016082000291234',
                // 选填-小程序 的 app_id
                'mini_app_id' => '',
                // 选填-app 的 app_id
                'app_id' => '',
                // 选填-合单 app_id
                'combine_app_id' => '',
                // 选填-合单商户号
                'combine_mch_id' => '',
                // 选填-服务商模式下，子公众号 的 app_id
                'sub_mp_app_id' => '',
                // 选填-服务商模式下，子 app 的 app_id
                'sub_app_id' => '',
                // 选填-服务商模式下，子小程序 的 app_id
                'sub_mini_app_id' => '',
                // 选填-服务商模式下，子商户id
                'sub_mch_id' => '',
                // 选填-微信公钥证书路径, optional，强烈建议 php-fpm 模式下配置此参数
                'wechat_public_cert_path' => [
                    '45F59D4DABF31918AFCEC556D5D2C6E376675D57' => __DIR__ . '/Cert/wechatPublicKey.crt',
                ],
                // 选填-默认为正常模式。可选为： MODE_NORMAL, MODE_SERVICE
                'mode' => Pay::MODE_NORMAL,
            ]
        ],
        'logger' => [
            'enable' => false,
            'file' => './logs/alipay.log',
            'level' => 'info', // 建议生产环境等级调整为 info，开发环境为 debug
            'type' => 'single', // optional, 可选 daily.
            'max_file' => 30, // optional, 当 type 为 daily 时有效，默认 30 天
        ],
        'http' => [ // optional
            'timeout' => 5.0,
            'connect_timeout' => 5.0,
            // 更多配置项请参考 [Guzzle](https://guzzle-cn.readthedocs.io/zh_CN/latest/request-options.html)
        ],
    ];
}
