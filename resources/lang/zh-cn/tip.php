<?php

// 简体中文

return [
    'error'                             =>  '操作失败.',
    'success'                           =>  '操作成功.',
    'failed'                            =>  '异常错误.',
    'loginOk'                           =>  '登录成功.',
    'loginErr'                          =>  '登录失败.',
    'loginThr'                          =>  '登录异常.',
    'loginOut'                          =>  '注销成功.',
    'loginInv'                          =>  '账号失效.',
    'pmsThr'                            =>  '权限异常.',
    'pwdErr'                            =>  '密码错误.',
    'userThr'                           =>  '账号异常.',
    'userErr'                           =>  '账号或密码错误.',
    'oauthThr'                          =>  'oAuth异常.',
    'configThr'                         =>  '配置信息异常.',
    'phoneThr'                          =>  '手机号码错误.',
    'userExist'                         =>  '手机号码错误.',
    'userNameExist'                     =>  '用户名已经存在.',
    'phoneExist'                        =>  '手机号码已经存在.',

    // 订单状态
    'orderCancel'                                  =>   '订单取消',
    'waitPay'                                      =>   '等待支付',
    'waitSend'                                     =>   '等待发货',
    'waitRec'                                      =>   '等待收货',
    'orderConfirm'                                 =>   '等待收货', // 确认收货
    'waitComment'                                  =>   '等待评论',
    'orderRefund'                                  =>   '售后退款',
    'orderReturned'                                =>   '售后退货',
    'orderRefundOver'                              =>   '售后完成',
    'orderCompletion'                              =>   '订单完成',

    'systemHandleMoney'                            =>   '系统处理',

    // 支付类型
    'paymentWechat'                                =>   '微信支付',
    'paymentAli'                                   =>   '支付宝支付',
    'paymentMoney'                                 =>   '余额支付',


    // 权限的一些中文
    'permission' => [
        'view'      =>  '查看',
        'store'     =>  '添加',
        'update'    =>  '编辑',
        'destroy'   =>  '删除',
        'index'     =>  '列表',
    ],

    // 上传的错误信息
    'upload' => [
        'fileNotFound'  =>  '没有找到上传文件.',
        'invalidFile'   =>  '无效文件上传.',
        'notAllow'      =>  '文件类型不允许上传.',
        'uploadThr'     =>  '上传异常.',
    ],

    // 短信相关sms
    'sms' => [
        'phoneError'    =>  '手机号码不正确.',
        'signEmpty'     =>  '签名不存在.',
        'sendErr'       =>  '发送短信失败.',
        'smsErr'        =>  '验证码错误.',
        'smsInvalid'    =>  '验证码失效.',
        'reSend'        =>  '请不要频繁发送.',
        'phoneExists'   =>  '手机号码已经存在.',
        'phoneNoExists' =>  '手机号码不存在.',
    ],

    // 支付相关payment
    'payment' => [
        'orderErr'          =>  '订单信息错误.',
        'onlineRecharge'    =>  '在线充值',
        'orderPay'          =>  '订单支付',
        'paymentFailed'     =>  '调取支付失败.',
    ],

    // 店铺相关
    'store' => [
        'defined'       =>  '店铺已经存在.',
        'notMall'       =>  '您不是店铺拥有者.',
        'subLimit'      =>  '子账户不允许开设店铺.',
    ],

    // 提现
    'cash' => [
        'notCheck'          =>  '请先用户认证填写银行信息.',
        'moneyZero'         =>  '金额不能为0.',
        'moneyNotEnough'    =>  '提现余额不足.',
    ],

    // 商品
    'goods' => [
        'checkSku'          =>  '请选择商品规格.',
    ],

    // 营销
    'discount' => [
        'over100'           =>  '折扣不能超过100.',
        'need'              =>  '成团人数不能低于2.',
        'couponStockErr'    =>  '优惠劵已经领完.',
        'couponExists'      =>  '优惠劵不存在.',
        'couponHas'         =>  '优惠劵已经领取过.',
    ],

    // 订单
    'order' => [
        'error'     =>  '订单非法.',
        'empty'     =>  '没有找到订单.',
        'handleErr' =>  '订单操作失败.',
        'stockErr'  =>  '库存不足.',
        'addrErr'   =>  '地址不能为空.',
        'paymentErr' =>  '支付方式非法.',
        'payed'     =>  '该订单已支付.',
        'payErr'    =>  '支付失败.',
        'moneyNotEnough'    =>  '余额不足.',
        'moneyPay'  =>  '重复创建支付订单.',
        'deliveryEmpty'  =>  '物流信息不能为空.',
        'orderSettlement'  =>  '系统结算.',
        'orderSettlementHandle'  =>  '手动结算.',
        'goodsCommission'  =>  '商品分佣',
    ]
];
