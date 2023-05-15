<?php

return [
    'error' => 'Operation failed.',
    'success' => 'The operation was successful.',
    'failed' => 'Exception error.',
    'loginOk' => 'Login successful.',
    'loginErr' => 'Login failed.',
    'loginThr' => 'Login exception.',
    'loginOut' => 'Logout successful.',
    'loginInv' => 'Account expired.',
    'pmsThr' => 'Permission exception.',
    'pwdErr' => 'Password error.',
    'userThr' => 'Account exception.',
    'userErr' => 'Account or password error.',
    'oauthThr' => 'oAuth exception.',
    'configThr' => 'Configuration information is abnormal.',
    'phoneThr' => 'The mobile phone number is wrong.',
    'userExist' => 'Mobile phone number error.',
    'userNameExist' => 'Username already exists.',
    'phoneExist' => 'The phone number already exists.',

    // Order Status
    'orderCancel' => 'order cancellation',
    'waitPay' => 'waiting for payment',
    'waitSend' => 'waiting for delivery',
    'waitRec' => 'waiting for receipt',
    'orderConfirm' => 'waiting for receipt', // confirm receipt
    'waitComment' => 'waiting for comments',
    'orderRefund' => 'After-sales refund',
    'orderReturned' => 'After-sales returns',
    'orderRefundOver' => 'After-sales completion',
    'orderCompletion' => 'order completion',

    'systemHandleMoney' => 'system handling',

    // Payment Types
    'paymentWechat' => 'Wechat payment',
    'paymentAli' => 'Alipay payment',
    'paymentMoney' => 'balance payment',


    // Some Chinese for permissions
    'permission' => [
        'view' => 'view',
        'store' => 'add',
        'update' => 'edit',
        'destroy' => 'delete',
        'index' => 'list',
    ],

    // Upload error message
    'upload' => [
        'fileNotFound' => 'No upload file found.',
        'invalidFile' => 'Invalid file upload.',
        'notAllow' => 'The file type is not allowed to upload.',
        'uploadThr' => 'upload exception.',
    ],

    // SMS related sms
    'sms' => [
        'phoneError' => 'The mobile phone number is incorrect.',
        'signEmpty' => 'Signature does not exist.',
        'sendErr' => 'Failed to send SMS.',
        'smsErr' => 'Verification code error.',
        'smsInvalid' => 'The verification code is invalid.',
        'reSend' => 'Please do not send frequently.',
        'phoneExists' => 'The phone number already exists.',
        'phoneNoExists' => 'The phone number does not exist.',
    ],

    // Payment related payment
    'payment' => [
        'orderErr' => 'order information error.',
        'onlineRecharge' => 'online recharge',
        'orderPay' => 'order payment',
        'paymentFailed' => 'failed to call payment.',
    ],

    // store related
    'store' => [
        'defined' => 'The store already exists.',
        'notMall' => 'You are not the shop owner.',
        'subLimit' => 'Sub-accounts are not allowed to open stores.',
    ],

    // withdraw
    'cash' => [
        'notCheck' => 'Please fill in the bank information for user authentication first.',
        'moneyZero' => 'The amount cannot be 0.',
        'moneyNotEnough' => 'Insufficient withdrawal balance.',
    ],

    // product
    'goods' => [
        'checkSku' => 'Please select the product specification.',
    ],

    // marketing
    'discount' => [
        'over100' => 'The discount cannot exceed 100.',
        'need' => 'The number of people in a group cannot be less than 2.',
        'couponStockErr' => 'The coupons have been received.',
        'couponExists' => 'coupon does not exist.',
        'couponHas' => 'coupon has been received.',
    ],

    // Order
    'order' => [
        'error' => 'Illegal order.',
        'empty' => 'No order found.',
        'handleErr' => 'Order operation failed.',
        'stockErr' => 'Insufficient stock.',
        'addrErr' => 'The address cannot be empty.',
        'paymentErr' => 'Illegal payment method.',
        'payed' => 'The order has been paid.',
        'payErr' => 'Payment failed.',
        'moneyNotEnough' => 'Insufficient balance.',
        'moneyPay' => 'Repeatedly create payment orders.',
        'deliveryEmpty' => 'Logistics information cannot be empty.',
        'orderSettlement' => 'system settlement.',
        'orderSettlementHandle' => 'Manual settlement.',
        'goodsCommission' => 'commodity commission',
    ]
];
