<?php

namespace App\Qingwuit\Services;

use App\Http\Resources\OrderAfterHomeCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class OrderService extends BaseService
{
    // 要删除的购物车数据
    protected $cartId = [];

    // 获取创建订单前处理订单
    public function createOrderBefore()
    {
        $check = $this->base64Check();
        if (!$check['status']) {
            return $this->formatError($check['msg']);
        }
        $params = $check['data'];
        $rs = $this->createOrderFormat($params);
        return $rs['status'] ? $this->format($rs['data']) : $this->formatError($rs['msg']);
    }

    // 创建订单
    public function createOrder()
    {
        $check = $this->base64Check();
        if (!$check['status']) {
            return $this->formatError($check['msg']);
        }

        $params = $check['data'];
        $rs = $this->createOrderFormat($params);

        if (!$rs['status']) {
            return $this->formatError($rs['msg']);
        }

        // 优惠券的处理
        if (!isset(request()->coupon_id)) {
            return $this->formatError('coupon_id empty');
        }
        $couponId = explode(',', request()->coupon_id);

        // 地址验证
        $addressResp = $this->checkAddress();
        if (!$addressResp['status']) {
            return $this->formatError($addressResp['msg']);
        }

        $remark = request('remark', '');

        $userId = $this->getUserId('users');
        $makeRands = [];
        if (count($rs['data']) > 0) {
            foreach ($rs['data'] as $v) $makeRands[] = date('YmdHis') . substr($userId, 0, 1) . mt_rand(10000, 99999); // 生成订单号
        }

        // REDIS队列创建订单
        if (env('APP_REDIS')) {
            $this->getJob('CreateOrder', [
                'rs'                => $rs,
                'address_resp'      => $addressResp,
                'coupon_id'         => $couponId,
                'userId'            => $userId,
                'make_rands'        => $makeRands,
                'remark'            => $remark
            ])->onQueue('createOrder');
            return $this->format(['order_id' => [], 'order_no' => $makeRands], 'Queue executing.');
        }

        return $this->createOrderData($rs, $addressResp, $couponId, $userId, $makeRands, $remark);
    }

    // 封装个创建订单的插入方法
    public function createOrderData($rs, $address_resp, $coupon_id, $userId, $make_rands, $remark)
    {
        $address_info = $address_resp['data'];

        // 实例化订单表
        $order_model = $this->getService('Order', true);
        $order_goods_model = $this->getService('OrderGoods', true);
        $seckillService = $this->getService('Seckill');
        $fullReductionService = $this->getService('FullReduction');
        $collectiveService = $this->getService('Collective');

        // 循环生成订单 多个商家则生成多个订单
        try {
            DB::beginTransaction();
            $resp_data = [];
            foreach ($rs['data'] as $k => $v) {
                $order_data = [
                    'order_no'                  =>  $make_rands[$k], // 订单号
                    'user_id'                   =>  $userId, // 用户ID
                    'store_id'                  =>  $v['store_info']['id'], // 店铺ID
                    'order_name'                =>  $v['goods_list'][0]['goods_name'], // 商品ID
                    'order_image'               =>  $v['goods_list'][0]['goods_master_image'], // 商品图片
                    'receive_name'              =>  $address_info['receive_name'], // 收件人姓名
                    'receive_tel'               =>  $address_info['receive_tel'], // 收件人电话
                    'receive_area'              =>  $address_info['area_info'], // 收件人地区
                    'receive_address'           =>  $address_info['address'], // 详细地址
                    'coupon_id'                 =>  isset($coupon_id[$k]) ? intval(abs($coupon_id[$k])) : 0, // 优惠券ID
                    'remark'                    =>  $remark, // 备注
                ];

                $order_info = $order_model->create($order_data); // 订单数据插入数据库

                // 初始化其他费用
                $total_price = 0; // 总金额
                $order_price = 0; // 订单总金额
                $total_weight = 0; // 总重量
                $freight_money = 0; // 运费
                $coupon_money = 0; // 优惠券 金额

                // 循环将订单商品插入
                foreach ($v['goods_list'] as $vo) {
                    $order_goods_data = [
                        'order_id'      => $order_info->id, // 订单ID
                        'user_id'       => $order_data['user_id'], // 用户ID
                        'store_id'      => $order_data['store_id'], // 店铺ID
                        'sku_id'        => $vo['sku_id'], // skuid
                        'goods_id'      => $vo['id'], // 商品id
                        'goods_name'    => $vo['goods_name'], // 商品名称
                        'goods_image'   => $vo['goods_master_image'], // 商品图片
                        'sku_name'      => $vo['sku_name'], // sku名称
                        'buy_num'       => $vo['buy_num'], // 购买数量
                        'goods_price'   => $vo['goods_price'], // 商品价格
                        'total_price'   => $vo['total'], // 总价格
                        'total_weight'  => $vo['total_weight'], // 总重量
                    ];

                    // 秒杀
                    $seckillInfo = $seckillService->seckillInfoByGoodsId($order_goods_data['goods_id']);
                    if ($seckillInfo['status']) $coupon_money = bcadd($coupon_money, bcmul($order_goods_data['total_price'], $seckillInfo['data']['discount'] / 100, 2), 2);

                    $order_goods_model->create($order_goods_data); // 插入订单商品表

                    // 开始减去库存
                    $this->orderStock($order_goods_data['goods_id'], $order_goods_data['sku_id'], $order_goods_data['buy_num']);

                    // 将商品总总量加起来
                    $total_weight = bcadd($total_weight, $order_goods_data['total_weight'], 2);

                    // 将金额全部计算加载到订单里面
                    $order_price = bcadd($order_price, $order_goods_data['total_price'], 2);
                }

                // 获取优惠券的金额 并插入数据库
                if ($order_data['coupon_id'] > 0) {
                    $cpli = $this->getService('CouponLog', true)->where('id', $order_data['coupon_id'])
                        ->where('user_id', $order_data['user_id'])
                        ->where('store_id', $order_data['store_id'])
                        ->where('use_money', '<=', bcadd($order_price, $freight_money, 2))
                        ->where('start_time', '<', now())->where('end_time', '>', now())
                        ->where('status', 0)->first();
                    if (empty($cpli)) {
                        $order_data['coupon_id'] = 0;
                    } else {
                        $coupon_money = bcadd($coupon_money, $cpli['money'], 2);
                        $cpli->status = 1;
                        $cpli->order_id = $order_info->id;
                        $cpli->save();
                    }
                }

                // 满减
                $full_reduction_resp = $fullReductionService->fullReductionInfoByStoreId($order_data['store_id'], bcadd($order_price, $freight_money, 2));
                if ($full_reduction_resp['status']) {
                    $coupon_money = bcadd($coupon_money, $full_reduction_resp['data']['money'], 2);
                }

                // 判断是否是拼团 如果是拼团减去他的金额
                $collective_id = $v['goods_list'][0]['collective_id'] ?? 0;
                if ($collective_id != 0) {
                    $collective_resp = $this->getService('Collective', true)->where('goods_id', $v['goods_list'][0]['id'])->first();
                    if ($collective_resp) {
                        $coupon_money = bcadd($coupon_money, bcmul($order_price, $collective_resp->discount / 100, 2), 2);
                    } // 得出拼团减去的钱
                    $collective_id = $collectiveService->createCollectiveLog($collective_id, $collective_resp, $order_goods_data);
                }


                $freight_money = $this->sumFreight($v['goods_list']['0']['freight_id'], $total_weight, $order_data['store_id'], $address_info['province_id']); // 直接计算运费，如果多个不同的商品取第一个商品的运费

                // 订单总金额做修改，然后保存
                $total_price = bcsub(bcadd($order_price, $freight_money, 2), $coupon_money, 2); // 暂时总金额等于[订单金额+运费-优惠金额]
                $order_info->total_price = $total_price;
                $order_info->order_price = $order_price;
                $order_info->freight_money = $freight_money; // 运费
                $order_info->coupon_money = round($coupon_money, 2); // 优惠金额修改
                $order_info->coupon_id = $order_data['coupon_id']; // 优惠券ID修改 ，以免非法ID传入
                $order_info->collective_id = $collective_id; // 团购ID修改
                $order_info->save(); // 保存入数据库

                $resp_data['order_id'][] = $order_info->id;
                $resp_data['order_no'][] = $make_rands[$k];
            }

            // 执行成功则删除购物车
            $this->delCart();
            DB::commit();
            return $this->format($resp_data);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->formatError(__('tip.order.error') . ' - last');
        }
    }

    // 如果是购物车订单，则删除购物车
    private function delCart()
    {
        if (!empty($this->cartId)) {
            try {
                $this->getService('Cart', true)->whereIn('id', $this->cartId)->delete();
            } catch (\Exception $e) {
                throw new \Exception('order cart del error.');
            }
        }
    }


    // 创建订单后处理
    public function createOrderAfter()
    {
        $check = $this->base64Check();
        if (!$check['status']) {
            return $this->formatError($check['msg']);
        }

        $params = $check['data'];
        $model = $this->getService('Order', true);
        // 这里看要不要加个 用户限制 以后看
        // $userId = $this->getUserId('users');
        // $model = $model->where('user_id',$userId);
        if (!empty($params['order_id'])) $model = $model->whereIn('id', $params['order_id']);
        if (empty($params['order_id'])) $model = $model->whereIn('order_no', $params['order_no']);
        $list = $model->with('order_goods', 'refund')->get();

        return $this->format(new OrderAfterHomeCollection($list));
    }

    // 库存消减增加 
    public function orderStock($goodsId, $skuId, $quantity, $isIncrease = false)
    {
        try {
            DB::beginTransaction();

            // 根据是否提供了 SKU ID 来获取相应的模型
            $model = $this->getLockedModel($goodsId, $skuId);
            if (!$model) {
                throw new \Exception(__('tip.order.handleErr') . ' - stock lock');
            }

            // 根据 isIncrease 参数决定是增加还是减少库存
            if ($isIncrease) {
                $model->increment('goods_stock', $quantity);
            } else {
                $this->decreaseStockIfSufficient($model, $quantity);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception(__('tip.order.handleErr') . ' - stock: ' . $e->getMessage());
        }
    }

    /**
     * 获取锁定的商品或SKU模型
     *
     * @param int $goodsId 商品ID
     * @param int|null $skuId SKU ID
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    private function getLockedModel($goodsId, $skuId = null)
    {
        if (empty($skuId)) {
            return $this->getService('Goods', true)->lockForUpdate()->find($goodsId);
        }

        return $this->getService('GoodsSku', true)->lockForUpdate()->find($skuId);
    }

    /**
     * 如果库存足够，则减少库存；否则抛出异常
     *
     * @param \Illuminate\Database\Eloquent\Model $model 模型实例
     * @param int $quantity 数量
     * @throws \Exception 如果库存不足
     */
    private function decreaseStockIfSufficient($model, $quantity)
    {
        if ($model->goods_stock < $quantity) {
            throw new \Exception(__('tip.order.stockErr'));
        }

        $model->decrement('goods_stock', $quantity);
    }

    // 销量消减增加 is_type 0 减少  1增加
    public function orderSale($goods_id, $num, $is_type = 0)
    {
        try {
            $goods_model = $this->getService('Goods', true)->find($goods_id);
            if (!$goods_model) {
                throw new \Exception(__('tip.order.handleErr') . ' - sale lock');
            }
            if (empty($is_type)) {
                $goods_model->decrement('goods_sale', $num);
            } else {
                $goods_model->increment('goods_sale', $num);
            }
        } catch (\Exception $e) {
            throw new \Exception(__('tip.order.error') . ' - sale');
        }
    }

    /**
     * // 支付订单 function
     *
     * @param string $order_id 如：10,12,13
     * @param string $payment_name 如：wechat_scan|balance|wechat_h5
     * @param string $pay_password 如：123456 （非必填,payment_name=balance则需要填写)
     * @param string $recharge 如：1 （非必填）
     * @return void
     * @Description
     * @author hg <www.qingwuit.com>
     */
    public function payOrder()
    {
        $order_id = request()->order_id;
        $payment_name = request()->payment_name ?? '';
        $device = request()->device ?? 'web';

        // 获取用户信息
        $userInfo = $this->getUser('users');
        if (!$userInfo['status']) {
            return $this->formatError($userInfo['msg']);
        }
        $userId = $userInfo['data']['id'];

        // 检查支付方式是否传过来
        if (empty($payment_name)) {
            return $this->formatError(__('tip.order.paymentErr'));
        }

        // 如果是余额支付
        $balance = 0;
        if ($payment_name == 'balance') {
            if (!Hash::check(request()->pay_password ?? '', $userInfo['data']['pay_password'])) {
                return $this->formatError(__('tip.pwdErr'));
            }
            $balance = $userInfo['data']['money'];
        }

        // 判断订单号是否为空
        if (empty($order_id)) {
            return $this->formatError(__('tip.order.error') . ' - pay');
        }
        $order_arr = explode(',', $order_id); // 转化为数组
        $order_str = implode('', $order_arr); // 转化为字符串生成支付订单号

        // 判断是否订单是该用户的并且订单是否有支付成功过
        $order_model = $this->getService('Order', true);
        // 判断是否存在 指定订单
        if (!$order_model->whereIn('id', $order_arr)->where('user_id', $userId)->exists()) {
            return $this->formatError(__('tip.order.error') . ' - pay2');
        }
        // 判断是否已经支付过了
        $order_list = $order_model->whereIn('id', $order_arr)->where('user_id', $userId)->where('order_status', 1)->get();
        if ($order_list->isEmpty()) {
            return $this->formatError(__('tip.order.payed'));
        }

        $make_rand = date('YmdHis') . substr($userId, 0, 1) . mt_rand(10000, 99999); // 生成订单号
        $rs = $this->createPayOrder([
            'pay_no' => $make_rand,
            'user_id' => $userId,
            'payment_name' => $payment_name,
            'order_list' => $order_list,
            'device' => $device,
            'balance' => $balance,
        ]);

        // 创建支付订单失败
        if (!$rs['status']) {
            return $this->formatError($rs['msg']);
        }

        // 获取支付信息,调取第三方支付
        $payment_model = new PaymentService();
        $rs = $payment_model->pay($payment_name, $device, $rs['data']);
        return $rs['status'] ? $this->format($rs['data']) : $this->formatError($rs['msg']);
    }

    // 创建支付订单
    // @param bool $recharge_pay 是否是充值 还是订单
    // @param string $pay_no 支付订单号
    protected function createPayOrder($opt = [])
    {
        // 创建支付订单
        $params = [
            'recharge_pay' => false,
            'user_id' => 0,
            'pay_no' => '',
            'payment_name' => '',
            'order_list' => [],
            'device' => 'pc',
            'balance' => 0, // 用户余额
        ];

        if (empty($opt['user_id'])) {
            $params['user_id'] = $this->getUserId('users');
        }

        $params = array_merge($params, $opt);
        $create_data = [];
        if ($params['recharge_pay']) {
            $pay_no = date('YmdHis') . mt_rand(10000, 99999);
            $create_data = [
                'belong_id'             =>  $params['user_id'],
                'pay_no'                =>  $pay_no,
                'payment_name'          =>  $params['payment_name'],
                'is_recharge'           =>  1,
                'device'                =>  $params['device'],
                'total'                 =>  abs(request()->total ?? 1), // 充值金额
            ];
        } else {
            $order_ids = [];
            $total_price = 0;
            $order_balance = 0;
            foreach ($params['order_list'] as $v) {
                $order_ids[] = $v['id'];
                $total_price = bcadd($total_price, $v['total_price'], 2);
                // $order_balance += $v['order_balance'];
            }
            // 余额支付时判断是否余额足够
            if ($params['payment_name'] == 'balance') {
                if ($total_price > $params['balance']) {
                    return $this->formatError(__('tip.order.moneyNotEnough'));
                }
                $order_balance = $total_price;
            }
            $create_data = [
                'belong_id'                 =>  $params['user_id'],
                'name'                      =>  mb_substr($params['order_list'][0]['order_name'], 0, 60),
                'pay_no'                    =>  $params['pay_no'],
                'order_ids'                 =>  implode(',', $order_ids),
                'payment_name'              =>  $params['payment_name'],
                'device'                    =>  $params['device'],
                'total'                     =>  $total_price, // 订单总金额
                'balance'                   =>  $order_balance, // 余额支付金额
            ];
        }

        try {
            // 三秒钟不能重复支付订单创建 设计订单支付号 当前时间到秒的十位+用户ID+订单ID号
            $op = $this->getService('OrderPay', true)->where('belong_id', $params['user_id'])->orderBy('id', 'desc')->first();
            if ($op) {
                if (($op->created_at->timestamp + 3) > time()) {
                    return $this->formatError(__('tip.order.moneyPay'));
                }
            }
            $order_pay_info = $this->getService('OrderPay', true)->create($create_data);
            return $this->format($order_pay_info);
        } catch (\Exception $e) {
            return $this->formatError(__('tip.order.payErr') . $e->getMessage());
        }
    }

    /**
     * 订单状态修改 function
     *
     * @param [type] $order_id 订单ID
     * @param [type] $order_status 订单状态
     * @param [type] $auth 用户操作还是管理员操作 user|admin
     * @return void
     * @Description
     * @author hg <www.qingwuit.com>
     */
    public function editOrderStatus($order_id, $order_status, $auth = "users")
    {
        try {
            DB::beginTransaction();
            $order_model = $this->getService('Order', true)->lockForUpdate()->find($order_id);
            if (!$order_model) {
                throw new \Exception(__('tip.error'));
            }
            if ($auth == 'users') {
                $userId = $this->getUserId($auth);
                // 用户不允许随意操作状态，只能修改 取消订单和确定订单
                $statusArr = [0, 1, 4];
                if (!in_array($order_status, $statusArr)) {
                    throw new \Exception(__('tip.error'));
                }
                if ($order_model->user_id != $userId) {
                    throw new \Exception(__('tip.error'));
                }
            }

            // 商家可操作
            if ($auth == 'seller') {
                $storeId = $this->getService('Store')->getStoreId()['data'];
                $statusArr = [3];
                if (!in_array($order_status, $statusArr)) {
                    throw new \Exception(__('tip.error'));
                }
                if ($order_model->store_id != $storeId) {
                    throw new \Exception(__('tip.error'));
                }
            }

            switch ($order_status) {
                case 0: // 取消订单
                    if ($order_model->order_status != 1) {
                        throw new \Exception(__('tip.error'));
                    }  // 只有待支付的订单能取消
                    $og_list = $this->getService('OrderGoods', true)->select('goods_id', 'sku_id', 'buy_num')->where('order_id', $order_id)->get();
                    // 库存修改
                    foreach ($og_list as $v) {
                        $this->orderStock($v['goods_id'], $v['sku_id'], $v['buy_num'], true);
                    }
                    // 如果有优惠券则修改优惠券
                    $this->getService('CouponLog', true)->where('order_id', $order_id)->update(['status' => 0, 'order_id' => 0]);
                    break;
                case 1: // 等待支付

                    break;
                case 2: // 等待发货 用户已经支付完成

                    break;
                case 3: // 发货完成 等待 确认收货
                    if ($order_model->order_status != 2) {
                        throw new \Exception(__('tip.error'));
                    }
                    $delivery_code = request()->delivery_code ?? '';
                    $delivery_no = request()->delivery_no ?? '';
                    $order_model->delivery_code = $delivery_code;
                    $order_model->delivery_no = $delivery_no;
                    if (empty($delivery_code) || empty($delivery_no)) {
                        throw new \Exception(__('tip.order.deliveryEmpty'));
                    }

                    // 如果已经是该数据就不再更新订单状态
                    if ($order_model->order_status >= 3) $order_status = $order_model->order_status;
                    break;
                case 4: // 确认收货 等待评论
                    if ($order_model->order_status != 3) {
                        throw new \Exception(__('tip.error'));
                    }  // 只有待收货的订单能 确认
                    if (empty($order_model->delivery_no) || empty($order_model->delivery_code)) {
                        throw new \Exception(__('tip.error'));
                    }
                    break;
                case 5: // 5售后 商家处理
                    break;
                case 6: // 6订单完成
                    break;
            }
            $order_model->order_status = $order_status;
            $order_model->save();
            DB::commit();
            return $this->format([$order_status], __('tip.success'));
        } catch (\Exception $e) {
            DB::rollback();
            return $this->formatError($e->getMessage());
        }
    }

    // 地址验证
    public function checkAddress()
    {
        $id = request()->address_id ?? 0;
        if (empty($id)) {
            return $this->formatError(__('tip.order.addrErr'));
        }

        $address_info = $this->getService('Address', true)->find($id);

        if (empty($address_info)) {
            return $this->formatError(__('tip.order.addrErr') . '2');
        }

        return $this->format($address_info);
    }

    // 计算运费
    // @param mixed $freight_id 运费模版
    // @param mixed $total_weight 总重量
    // @param mixed $store_id 店铺ID
    // @param mixed $area_id 省份ID
    protected function sumFreight($freight_id, $total_weight, $store_id, $area_id)
    {
        if ($freight_id == -1) return 0;
        $freight_model = $this->getService('Freight', true);
        $default_info = $freight_model->where('is_type', 0)->where('store_id', $store_id)->first(); // 默认快递模版
        if ($freight_id == 0) { // 默认模版
            $info = $default_info;
        } else {
            $info = $freight_model->find($freight_id);
        }

        if (empty($info) || ($info->f_weight == 0 && $info->f_price == 0)) {
            return 0;
        }

        $area = [];
        if (!empty($info->area_id)) {
            $area = explode(',', $info->area_id);
        }
        // 如果配送地址不存在 商家配置的地址则走默认的
        if (!in_array($area_id, $area)) {
            $info = $default_info;
        }

        // 如果没有设置则为0
        if ($info->f_weight == 0 && $info->f_price == 0) {
            return 0;
        }

        // 如果设置了运费，没设置重量则代表无限重量 同一运费
        if ($info->f_weight == 0 && $info->f_price > 0) {
            return $info->f_price;
        }

        // 如果首重和首价格设置了
        if (($info->f_weight > 0 && $info->f_price > 0)) {
            // 判断是否重量有超过
            if ($info->f_weight >= $total_weight) {
                return $info->f_price;
            } else {
                // 超过了则开始分析是否有超出另外计价
                if ($info->o_weight == 0 && $info->o_price == 0) {
                    return $info->f_price;
                }
                // 超过了则开始分析是否有配置超出另外计价
                if ($info->o_weight == 0 && $info->o_price == 0) {
                    return $info->f_price;
                }
                // 超过了则开始分析是否有超出另外计价
                if ($info->o_weight > 0) {
                    $o_num = ceil(bcdiv(bcsub($total_weight, $info->f_weight, 2), $info->o_weight, 2)); // 超出的倍数
                    return bcadd($info->f_price, bcmul($o_num, $info->o_price, 2), 2);
                }
            }
        }

        return 0;
    }

    // 根据订单ID获取商品数据并格式化
    public function createOrderFormat($params)
    {
        $goods_model = $this->getService('Goods', true);
        $goods_sku_model = $this->getService('GoodsSku', true);

        $userId = $this->getUserId('users');
        $tool = $this->getService('Tool');

        $list = [];
        $this->cartId = []; // 购物车ID 初始化
        foreach ($params['order'] as $v) {
            $data = [];
            $data = $goods_model->with(['store' => function ($q) {
                return $q->select('id', 'store_name', 'store_logo');
            }])->select('id', 'store_id', 'goods_name', 'goods_master_image', 'goods_price', 'goods_stock', 'goods_weight', 'freight_id')->where('id', $v['goods_id'])->first();
            $data['sku_name'] = '-';
            $data['buy_num'] = abs(intval($v['buy_num']));
            $data['sku_id'] = $v['sku_id'];
            if ($v['sku_id'] > 0) {
                $goods_sku = $goods_sku_model->select('id', 'sku_name', 'goods_price', 'goods_stock', 'goods_weight')->where('goods_id', $v['goods_id'])->where('id', $v['sku_id'])->first();
                $data['sku_name'] = $goods_sku['sku_name'];
                $data['goods_price'] = $goods_sku['goods_price'];
                $data['goods_stock'] = $goods_sku['goods_stock'];
                $data['goods_weight'] = $goods_sku['goods_weight'];
            }

            $data['goods_master_image'] = $tool->thumb($data['goods_master_image'], 150);
            $data['total'] = round($v['buy_num'] * $data['goods_price'], 2);
            $data['total_weight'] = round($v['buy_num'] * $data['goods_weight'], 2);

            // 判断是否是团购
            $data['collective_id'] = 0;
            if (isset($v['collective_id'])) {
                $data['collective_id'] = $v['collective_id'];
            }

            $list[$data['store']['id']]['goods_list'][] = $data;
            $list[$data['store']['id']]['store_info'] = $data['store'];

            if (empty($list[$data['store']['id']]['store_total_price'])) {
                $list[$data['store']['id']]['store_total_price'] = 0;
            }
            $list[$data['store']['id']]['store_total_price'] = bcadd($list[$data['store']['id']]['store_total_price'], $data['total'], 2);

            // 判断是否库存足够
            if ($v['buy_num'] > $data['goods_stock']) {
                return $this->formatError(__('tip.order.stockErr'));
            }

            // 判断是否是购物车
            if (!empty($params['ifcart'])) {
                $this->cartId[] = $v['cart_id'];
            }
        }

        // 循环查看是否存在优惠券
        $coupon_log_model = $this->getService('CouponLog', true);
        foreach ($list as $k => &$v) {
            $coupon_list = $coupon_log_model->select('id', 'money', 'name')->where('user_id', $userId)->where('store_id', $k)->where('use_money', '<=', $v['store_total_price'])->where('status', 0)->get();
            $v['is_coupon'] = true;
            if ($coupon_list->isEmpty()) {
                $v['is_coupon'] = false;
            }
            $v['coupons'] = $coupon_list;
            if ($v['is_coupon']) {
                $v['coupon_id'] = $coupon_list[0]['id'];
            } else {
                $v['coupon_id'] = 0;
            }
        }

        $list = array_merge($list, []);
        return $this->format($list);
    }

    // base64 代码验证
    public function base64Check()
    {
        $base64 = request()->params ?? '';

        // 如果为空
        if (empty($base64)) {
            return $this->formatError(__('tip.order.error'));
        }

        // 判断是否能解析
        try {
            $params = json_decode(base64_decode($base64), true);
        } catch (\Exception $e) {
            return $this->formatError(__('tip.order.error') . '2');
        }
        return $this->format($params);
    }

    // // 获取订单
    public function getOrders($type = "users")
    {
        $tableModelName = 'Order';
        $order_model = $this->getService($tableModelName, true);
        if ($type == 'users') {
            $userId = $this->getUserId('users');
            $order_model = $order_model->where('user_id', $userId);
        }
        if ($type == 'seller') {
            $store_id = $this->getService('Store')->getStoreId();
            $order_model = $order_model->where('store_id', $store_id);
        }

        $order_model = $order_model->with(['store' => function ($q) {
            return $q->select('id', 'store_name', 'store_logo');
        }, 'user' => function ($q) {
            return $q->select('id', 'username');
        }, 'order_goods']);

        // 订单号
        $order_no  = request()->order_no;
        if (!empty($order_no)) {
            $order_model = $order_model->where('order_no', 'like', '%' . $order_no . '%');
        }

        // 拼团订单ID查询
        $collective_id = request()->collective_id;
        if (!empty($collective_id)) {
            $order_model = $order_model->where('collective_id', $collective_id);
        }

        // 用户ID
        $user_id = request()->user_id;
        if (!empty($user_id)) {
            $order_model = $order_model->where('user_id', $user_id);
        }

        // 店铺ID
        $store_id = request()->store_id;
        if (!empty($store_id)) {
            $order_model = $order_model->where('store_id', $store_id);
        }

        // 下单时间
        $created_at = request()->created_at;
        if (!empty($created_at)) {
            $order_model = $order_model->whereBetween('created_at', [$created_at[0], $created_at[1]]);
        }

        // 订单状态
        if (isset(request()->order_status) && request()->order_status != -1) {
            $order_model = $order_model->where('order_status', request()->order_status);
        }

        // 获取退款订单
        if (isset(request()->is_refund)) {
            $order_model = $order_model->where('order_status', 5)->where('refund_status', 0);
        }

        // 获取退货订单
        if (isset(request()->is_return)) {
            $order_model = $order_model->where('order_status', 5)->where('refund_status', 1);
        }

        $pageData = $order_model->orderBy('id', 'desc')
            ->paginate(request()->per_page ?? 30);
        $data = $pageData;
        if (!empty(request('isResource'))) {
            $tableModelName .=  request('isResource');
        } // 自定义资源文件
        $hasResource = $this->hasResource($pageData, $tableModelName);
        if ($hasResource) {
            $data = $hasResource;
        }
        return $this->format($data);
    }

    // 获取订单信息通过订单ID 默认是需要用用户
    public function getOrderInfoById($id, $auth = 'users')
    {
        $tableModelName = 'Order';
        $order_model = $this->getService($tableModelName, true);

        if ($auth == 'users') {
            $userId = $this->getUserId('users');
            $order_model = $order_model->where('user_id', $userId);
        }

        if ($auth == 'seller') {
            $store_id = $this->getService('Store')->getStoreId();
            $order_model = $order_model->where('store_id', $store_id);
        }

        $pageData = $order_model->with('order_goods')->where('id', $id)->first();
        $data = $pageData;
        if (!empty(request('isResource'))) {
            $tableModelName .=  request('isResource');
        } // 自定义资源文件
        $hasResource = $this->hasResource($pageData, $tableModelName, 1);
        if ($hasResource) {
            $data = $hasResource;
        }
        return $this->format($data);
    }

    // 获取订单状态
    public function getOrderStatusCn($order_info)
    {
        $cn = '未知订单';
        switch ($order_info['order_status']) {
            case 0:
                $cn = __('tip.orderCancel');
                break;
            case 1:
                $cn = __('tip.waitPay');
                break;
            case 2:
                $cn = __('tip.waitSend');
                break;
            case 3:
                $cn = __('tip.orderConfirm');
                break;
            case 4:
                $cn = __('tip.waitComment');
                break;
            case 5:
                if ($order_info['refund']) {
                    if ($order_info['refund_type'] == 0) {
                        $cn = __('tip.orderRefund');
                    } elseif ($order_info['refund_type'] == 1) {
                        $cn = __('tip.orderReturned');
                    } else {
                        $cn = __('tip.orderRefundOver');
                    }
                } else {
                    $cn = __('tip.orderRefund');
                }
                break;
            case 6:
                $cn = __('tip.orderCompletion');
                break;
        }
        return $cn;
    }

    // 获取支付类型
    public function getOrderPayMentCn($payment_name)
    {
        $cn = __('tip.waitPay');
        switch ($payment_name) {
            case 'wechat':
                $cn = __('tip.paymentWechat');
                break;
            case 'ali':
                $cn = __('tip.paymentAli');
                break;
            case 'balance':
                $cn = __('tip.paymentMoney');
                break;
        }
        return $cn;
    }
}
