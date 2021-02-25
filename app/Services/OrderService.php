<?php
namespace App\Services;

use App\Http\Resources\Home\OrderResource\CreateOrderAfterCollection;
use App\Models\Address;
use App\Models\Cart;
use App\Models\CouponLog;
use App\Models\Freight;
use App\Models\Goods;
use App\Models\GoodsSku;
use App\Models\Order;
use App\Models\OrderGoods;
use App\Models\OrderPay;
use App\Models\Store;
use App\Traits\HelperTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderService extends BaseService{

    use HelperTrait;
    // 要删除的购物车数据
    protected $cartId = [];

    // 获取创建订单前处理订单
    public function createOrderBefore(){
        $check = $this->base64Check();
        if(!$check['status']){
            return $this->format_error($check['msg']);
        }

        $params = $check['data'];
        $rs = $this->createOrderFormat($params);

        return $rs['status']?$this->format($rs['data']):$this->format_error($rs['msg']);
    }

    // 创建订单
    public function createOrder(){
        $check = $this->base64Check();
        if(!$check['status']){
            return $this->format_error($check['msg']);
        }

        $params = $check['data'];
        $rs = $this->createOrderFormat($params);

        if(!$rs['status']){
            return $this->format_error($rs['msg']);
        }

        // 优惠券的处理
        if(!isset(request()->coupon_id)){
            return $this->format_error('coupon_id empty');
        }
        $coupon_id = explode(',',request()->coupon_id);

        // 地址验证
        $address_resp = $this->checkAddress();
        if(!$address_resp['status']){
            return $this->format_error($address_resp['msg']);
        }
        $address_info = $address_resp['data'];

        $user_service = new UserService;
        $user_info = $user_service->getUserInfo();
        if(empty($user_info)){
            return $this->format_error(__('base.error').' - order_service');
        }
        
        // 实例化订单表
        $order_model = new Order();
        $order_goods_model = new OrderGoods();
        $coupon_log_model = new CouponLog();
        $seckill_service = new SeckillService;
        $full_reduction_service = new FullReductionService();
        $collective_service = new CollectiveService();

        // 循环生成订单 多个商家则生成多个订单
        try{
            DB::beginTransaction();
                $resp_data = [];
                foreach($rs['data'] as $k=>$v){
                    $make_rand = date('YmdHis').$user_info['id'].mt_rand(1000,9999); // 生成订单号

                    $order_data = [
                        'order_no'                  =>  $make_rand, // 订单号
                        'user_id'                   =>  $user_info['id'], // 用户ID
                        'store_id'                  =>  $v['store_info']['id'], // 店铺ID
                        'order_name'                =>  $v['goods_list'][0]['goods_name'], // 商品ID
                        'order_image'               =>  $v['goods_list'][0]['goods_master_image'], // 商品图片
                        'receive_name'              =>  $address_info['receive_name'], // 收件人姓名
                        'receive_tel'               =>  $address_info['receive_tel'], // 收件人电话
                        'receive_area'              =>  $address_info['area_info'], // 收件人地区
                        'receive_address'           =>  $address_info['address'], // 详细地址
                        'coupon_id'                 =>  isset($coupon_id[$k])?intval(abs($coupon_id[$k])):0, // 优惠券ID
                        'remark'                    =>  request()->remark??'', // 备注
                    ];

                    $order_info = $order_model->create($order_data); // 订单数据插入数据库

                    // 初始化其他费用
                    $total_price = 0 ; // 总金额
                    $order_price = 0 ; // 订单总金额
                    $total_weight = 0; // 总重量
                    $freight_money = 0; // 运费
                    $coupon_money = 0; // 优惠券 金额

                    // 循环将订单商品插入
                    foreach($v['goods_list'] as $vo){
                        $order_goods_data = [
                            'order_id'      =>$order_info->id, // 订单ID
                            'user_id'       =>$order_data['user_id'], // 用户ID
                            'store_id'      =>$order_data['store_id'], // 店铺ID
                            'sku_id'        =>$vo['sku_id'], // skuid
                            'goods_id'      =>$vo['id'], // 商品id
                            'goods_name'    =>$vo['goods_name'], // 商品名称
                            'goods_image'   =>$vo['goods_master_image'], // 商品图片
                            'sku_name'      =>$vo['sku_name'], // sku名称
                            'buy_num'       =>$vo['buy_num'], // 购买数量
                            'goods_price'   =>$vo['goods_price'], // 商品价格
                            'total_price'   =>$vo['total'], // 总价格
                            'total_weight'  =>$vo['total_weight'], // 总重量
                        ];

                        // 秒杀
                        $seckill_info = $seckill_service->getSeckillInfoByGoodsId($order_goods_data['goods_id']);
                        if($seckill_info['status']){
                            $coupon_money += $order_goods_data['total_price']*($seckill_info['data']['discount']/100);
                        }
                        
                        $order_goods_model->create($order_goods_data); // 插入订单商品表

                        // 开始减去库存
                        $this->orderStock($order_goods_data['goods_id'],$order_goods_data['sku_id'],$order_goods_data['buy_num']);
                        
                        // 将商品总总量加起来
                        $total_weight += $order_goods_data['total_weight'];

                        // 将金额全部计算加载到订单里面
                        $order_price += $order_goods_data['total_price'];
                    }

                    // 获取优惠券的金额 并插入数据库
                    if($order_data['coupon_id']>0){
                        $cpli = $coupon_log_model->where('id',$order_data['coupon_id'])
                                                    ->where('user_id',$order_data['user_id'])
                                                    ->where('store_id',$order_data['store_id'])
                                                    ->where('use_money','<=',($order_price+$freight_money))
                                                    ->where('start_time','<',now())->where('end_time','>',now())
                                                    ->where('status',0)->first();
                        if(empty($cpli)){
                            $order_data['coupon_id'] = 0;
                        }else{
                            $coupon_money = $cpli['money'];
                            $cpli->status = 1;
                            $cpli->order_id = $order_info->id;
                            $cpli->save();
                        }
                    }

                    // 满减
                    $full_reduction_resp = $full_reduction_service->getFullReductionInfoByStoreId($order_data['store_id'],($order_price+$freight_money));
                    if($full_reduction_resp['status']){
                        $coupon_money += $full_reduction_resp['data']['money'];
                    }

                    // 判断是否是拼团 如果是拼团减去他的金额
                    $collective_id = $v['goods_list'][0]['collective_id']??0;
                    if($collective_id != 0){
                        $collective_resp = $collective_service->getCollectiveInfoByGoodsId($v['goods_list'][0]['id']);
                        if($collective_resp['status']){
                            $coupon_money += $order_price*($collective_resp['data']['discount']/100); // 得出拼团减去的钱
                        }
                        $collective_id = $collective_service->createCollectiveLog($collective_id,$collective_resp,$order_goods_data);
                    }
                    
                    
                    $freight_money = $this->sumFreight($v['goods_list']['0']['freight_id'],$total_weight,$order_data['store_id'],$address_info['province_id']); // 直接计算运费，如果多个不同的商品取第一个商品的运费
                    
                    // 订单总金额做修改，然后保存
                    $total_price = $order_price+$freight_money-$coupon_money; // 暂时总金额等于[订单金额+运费-优惠金额]
                    $order_info->total_price = round($total_price,2);
                    $order_info->order_price = $order_price;
                    $order_info->freight_money = $freight_money; // 运费
                    $order_info->coupon_money = $coupon_money; // 优惠金额修改
                    $order_info->coupon_id = $order_data['coupon_id']; // 优惠券ID修改 ，以免非法ID传入
                    $order_info->collective_id = $collective_id; // 团购ID修改 
                    $order_info->save(); // 保存入数据库

                    $resp_data['order_id'][] = $order_info->id;
                    $resp_data['order_no'][] = $make_rand;
                }

                // 执行成功则删除购物车
                $this->delCart();
            DB::commit();
            return $this->format($resp_data);
        }catch(\Exception $e){
            Log::channel('qwlog')->debug('createOrder:'.json_encode($e->getMessage()));
            DB::rollBack();
            return $this->format_error(__('orders.error'));
        }
        

    }

    // 如果是购物车订单，则删除购物车
    private function delCart(){
        if(!empty($this->cartId)){
            $cart_model = new Cart();
            try{
                $cart_model->whereIn('id',$this->cartId)->delete();
            }catch(\Exception $e){
                throw new \Exception(__('orders.order_cart_del_error'));
            }
        }
    }


    // 创建订单后处理
    public function createOrderAfter(){
        $check = $this->base64Check();
        if(!$check['status']){
            return $this->format_error($check['msg']);
        }
        $params = $check['data'];
        $order_model = new Order;
        $list = $order_model->whereIn('id',$params['order_id'])->with('order_goods')->get();

        return $this->format(new CreateOrderAfterCollection($list));
    }

    // 库存消减增加 is_type 0 减少  1增加
    public function orderStock($goods_id,$sku_id,$num,$is_type=0){
        
        try{
            if(empty($sku_id)){
                $goods_model = new Goods;
                if(empty($is_type)){
                    $goods_model->where('id',$goods_id)->decrement('goods_stock',$num);
                }else{
                    $goods_model->where('id',$goods_id)->increment('goods_stock',$num);
                }
            }else{
                $goods_sku_model = new GoodsSku();
                if(empty($is_type)){
                    $goods_sku_model->where('id',$sku_id)->decrement('goods_stock',$num);
                }else{
                    $goods_sku_model->where('id',$sku_id)->increment('goods_stock',$num);
                }
            }
        }catch(\Exception $e){
            throw new \Exception(__('base.error').' - stock');
        }
        
    }

    // 销量消减增加 is_type 0 减少  1增加
    public function orderSale($goods_id,$num,$is_type=0){
        
        try{
            $goods_model = new Goods;
            if(empty($is_type)){
                $goods_model->where('id',$goods_id)->decrement('goods_sale',$num);
            }else{
                $goods_model->where('id',$goods_id)->increment('goods_sale',$num);
            }
        }catch(\Exception $e){
            throw new \Exception(__('base.error').' - stock');
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
    public function payOrder(){
        $order_id = request()->order_id;
        $payment_name = request()->payment_name??'';

        // 检查支付方式是否传过来
        if(empty($payment_name)){
            return $this->format_error(__('orders.empty_payment'));
        }

        // 判断订单号是否为空
        if(empty($order_id)){
            return $this->format_error(__('orders.error').' - pay');
        }
        $order_arr = explode(',',$order_id); // 转化为数组
        $order_str = implode('',$order_arr); // 转化为字符串生成支付订单号

        // 获取用户信息
        $user_service = new UserService();
        $user_info = $user_service->getUserInfo();
        if(empty($user_info)){
            return $this->format_error(__('user.no_token'));
        }

        // 判断是否订单是该用户的并且订单是否有支付成功过
        $order_model = new Order();
        // 判断是否存在 指定订单
        if(!$order_model->whereIn('id',$order_arr)->where('user_id',$user_info['id'])->exists()){
            return $this->format_error(__('orders.error').' - pay2');
        } 
        // 判断是否已经支付过了
        $order_list = $order_model->whereIn('id',$order_arr)->where('user_id',$user_info['id'])->where('order_status',1)->get();
        if($order_list->isEmpty()){
            return $this->format_error(__('orders.order_pay'));
        }

        // 十秒钟不能重复支付 设计订单支付号 当前时间到秒的十位+用户ID+订单ID号
        $second = substr(date('YmdHis'),0,13);
        $pay_no = $second.$user_info['id'].$order_str; // 订单支付号
        $rs = $this->createPayOrder(false,$user_info,$pay_no,$order_list);

        // 创建支付订单失败
        if(!$rs['status']){
            return $this->format_error($rs['msg']);
        }

        // 获取支付信息,调取第三方支付
        $payment_model = new PayMentService();
        $rs = $payment_model->pay($payment_name,$rs['data']);
        return $rs['status']?$this->format($rs['data']):$this->format_error($rs['msg']);

    }

    // 创建支付订单
    // @param bool $recharge_pay 是否是充值 还是订单
    // @param string $pay_no 支付订单号
    protected function createPayOrder($recharge_pay = false,$user_info,$pay_no='',$order_list=[]){
        // 创建支付订单
        $create_data = [];
        if($recharge_pay){
            $pay_no = date('YmdHis').mt_rand(10000,99999);
            $create_data = [
                'user_id'               =>  $user_info['id'],
                'pay_no'                =>  $pay_no,
                'pay_type'              =>  'r',
                'total_price'           =>  abs(request()->total??1), // 充值金额
            ];
        }else{
            $order_ids = [];
            $total_price = 0;
            $order_balance = 0;
            foreach($order_list as $v){
                $order_ids[] = $v['id'];
                $total_price += $v['total_price']; 
                $order_balance += $v['order_balance']; 
            }
            $create_data = [
                'user_id'                   =>  $user_info['id'],
                'pay_no'                    =>  $pay_no,
                'order_ids'                 =>  implode(',',$order_ids),
                'pay_type'                  =>  'o',
                'total_price'               =>  $total_price, // 订单总金额
                'order_balance'             =>  $order_balance, // 余额支付金额
            ];
        }
        $order_pay_model = new OrderPay();
        
        try{
            $order_pay_info = $order_pay_model->create($create_data);
        }catch(\Exception $e){
            Log::channel('qwlog')->debug($e->getMessage());
            return $this->format_error(__('orders.payment_failed'));
        }

        return $this->format($order_pay_info);
        
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
    public function editOrderStatus($order_id,$order_status,$auth="user"){
        $order_model = new Order;
        $order_model = $order_model->where('id',$order_id);
        if($auth == 'user'){
            $user_service = new UserService;
            $user_info = $user_service->getUserInfo();
            if(empty($user_info)){
                return $this->format_error(__('users.no_token'));
            }
            // 用户不允许随意操作状态，只能修改 取消订单和确定订单
            if($order_status !=0 && $order_status !=4){
                return $this->format_error(__('base.error'));
            }
            $order_model = $order_model->where('user_id',$user_info['id']);
        }
        $order_model = $order_model->first();

        if(empty($order_model)){
            return $this->format_error(__('users.error_token'));
        }

        switch($order_status){
            case 0: // 取消订单
                if($order_model->order_status != 1){ // 只有待支付的订单能取消
                    return $this->format_error(__('base.error').' - 0');
                }
                $og_model = new OrderGoods();
                $og_list = $og_model->select('goods_id','sku_id','buy_num')->where('order_id',$order_id)->get();
                foreach($og_list as $v){
                    $this->orderStock($v['goods_id'],$v['sku_id'],$v['buy_num'],1);
                }
                // 如果有优惠券则修改优惠券
                $coupon_log_model = new CouponLog();
                $coupon_log_model->where('order_id',$order_id)->update(['status'=>0,'order_id'=>0]);

                // 库存修改
            break;
            case 1: // 等待支付
            break;
            case 2: // 等待发货
            break;
            case 3: // 确认收货
                if(empty($order_model->delivery_no) || empty($order_model->delivery_code)){ // 只有待支付的订单能取消
                    return $this->format_error(__('base.error').' - 3');
                }
            break;
            case 4: // 等待评论
            break;
            case 5: // 5售后
            break;
            case 6: // 6订单完成
            break;
        }
        $order_model->order_status = $order_status;
        $order_model->save();
        return $this->format([$order_status],__('base.success'));
    }

    // 地址验证
    public function checkAddress(){
        $id = request()->address_id??0;
        if(empty($id)){
            return $this->format_error(__('orders.no_address'));
        }

        $address_model = new Address();
        $address_info = $address_model->find($id);
        
        if(empty($address_info)){
            return $this->format_error(__('orders.no_address').'2');
        }

        return $this->format($address_info);
    }

    // 计算运费
    // @param mixed $freight_id 运费模版
    // @param mixed $total_weight 总重量
    // @param mixed $store_id 店铺ID
    // @param mixed $area_id 省份ID
    protected function sumFreight($freight_id,$total_weight,$store_id,$area_id){
        $freight_model = new Freight();
        $default_info = $freight_model->where('is_type',0)->where('store_id',$store_id)->first(); // 默认快递模版
        if($freight_id == 0){ // 默认模版
            $info = $default_info;
        }else{
            $info = $freight_model->find($freight_id);
        }

        if(empty($info) || ($info->f_weight==0 && $info->f_price==0)){
            return 0;
        }
        
        $area = [];
        if(!empty($info->area_id)){
            $area = explode(',',$info->area_id);
        }
        // 如果配送地址不存在 商家配置的地址则走默认的
        if(!in_array($area_id,$area)){
            $info = $default_info;
        }

        // 如果没有设置则为0
        if($info->f_weight==0 && $info->f_price==0){
            return 0;
        }

        // 如果设置了运费，没设置重量则代表无限重量 同一运费
        if($info->f_weight ==0 && $info->f_price>0 ){
            return $info->f_price;
        }

        // 如果首重和首价格设置了
        if(($info->f_weight >0 && $info->f_price>0)){
            // 判断是否重量有超过
            if($info->f_weight>=$total_weight){
                return $info->f_price;
            }else{
                // 超过了则开始分析是否有超出另外计价
                if($info->o_weight==0 && $info->o_price==0){
                    return $info->f_price;
                }
                // 超过了则开始分析是否有配置超出另外计价
                if($info->o_weight==0 && $info->o_price==0){
                    return $info->f_price;
                }
                // 超过了则开始分析是否有超出另外计价
                if($info->o_weight>0 ){
                    $o_num = ceil(($total_weight-$info->f_weight)/$info->o_weight); // 超出的倍数
                    return round($info->f_price+($o_num*$info->o_price),2);
                }
            }
        }

        return 0;
        
    }

    // 根据订单ID获取商品数据并格式化
    public function createOrderFormat($params){
        $goods_model = new Goods();
        $goods_sku_model = new GoodsSku();

        $user_service = new UserService();
        if(!$user_info = $user_service->getUserInfo()){
            return $this->format_error(__('auth.no_token'));
        }
        
        $list = [];
        $this->cartId = []; // 购物车ID 初始化
        foreach($params['order'] as $v){
            $data = [];
            $data = $goods_model->with(['store'=>function($q){
                return $q->select('id','store_name','store_logo');
            }])->select('id','store_id','goods_name','goods_master_image','goods_price','goods_stock','goods_weight','freight_id')->where('id',$v['goods_id'])->first();
            $data['sku_name'] = '-';
            $data['buy_num'] = abs(intval($v['buy_num']));
            $data['sku_id'] = $v['sku_id'];
            if($v['sku_id']>0){
                $goods_sku = $goods_sku_model->select('id','sku_name','goods_price','goods_stock','goods_weight')->where('goods_id',$v['goods_id'])->where('id',$v['sku_id'])->first();
                $data['sku_name'] = $goods_sku['sku_name'];
                $data['goods_price'] = $goods_sku['goods_price'];
                $data['goods_stock'] = $goods_sku['goods_stock'];
                $data['goods_weight'] = $goods_sku['goods_weight'];
            }

            $data['goods_master_image'] = $this->thumb($data['goods_master_image'],150);
            $data['total'] = round($v['buy_num']*$data['goods_price'],2);
            $data['total_weight'] = round($v['buy_num']*$data['goods_weight'],2);

            // 判断是否是团购
            $data['collective_id'] = 0;
            if(isset($v['collective_id'])){
                $data['collective_id'] = $v['collective_id']; 
            }
            
            $list[$data['store']['id']]['goods_list'][] = $data;
            $list[$data['store']['id']]['store_info'] = $data['store'];

            if(empty($list[$data['store']['id']]['store_total_price'])){
                $list[$data['store']['id']]['store_total_price'] = 0;
            }
            $list[$data['store']['id']]['store_total_price'] += $data['total'];

            // 判断是否库存足够
            if($v['buy_num']>$data['goods_stock']){
                return $this->format_error(__('orders.stock_error'));
            }

            // 判断是否是购物车
            if(!empty($params['ifcart'])){
                $this->cartId[] = $v['cart_id'];
            }

        }

        // 循环查看是否存在优惠券
        $coupon_log_model = new CouponLog();
        foreach($list as $k=>&$v){
            $coupon_list = $coupon_log_model->select('id','money','name')->where('user_id',$user_info['id'])->where('store_id',$k)->where('use_money','<=',$v['store_total_price'])->where('status',0)->get();
            $v['is_coupon'] = true;
            if($coupon_list->isEmpty()){
                $v['is_coupon'] = false;
            }
            $v['coupons'] = $coupon_list;
            if($v['is_coupon']){
                $v['coupon_id'] = $coupon_list[0]['id'];
            }else{
                $v['coupon_id'] = 0;
            }
        }

        $list = array_merge($list,[]);

        return $this->format($list);

        
    }

    // base64 代码验证
    public function base64Check(){
        $base64 = request()->params??'';

        // 如果为空
        if(empty($base64)){
            return $this->format_error(__('orders.error'));
        }

        // 判断是否能解析
        try{
            $params = json_decode(base64_decode($base64),true);
        }catch(\Exception $e){
            return $this->format_error(__('orders.error').'2');
        }
        return $this->format($params);
    }

    // 获取订单
    public function getOrders($type="user"){
        $order_model = new Order();

        if($type == 'user'){
            $user_service = new UserService;
            $user_info = $user_service->getUserInfo();
            $order_model = $order_model->where('user_id',$user_info['id']);
        }
        if($type == 'seller'){
            $store_id = $this->get_store(true);
            $order_model = $order_model->where('store_id',$store_id);
        }
        
        $order_model = $order_model->with(['store'=>function($q){
             return $q->select('id','store_name','store_logo');
        },'user'=>function($q){
            return $q->select('id','username');
        },'order_goods']);
        
        // 订单号
        $order_no  = request()->order_no;
        if(!empty($order_no)){
            $order_model = $order_model->where('order_no','like','%'.$order_no.'%');
        }

        // 拼团订单ID查询
        $collective_id = request()->collective_id;
        if(!empty($collective_id)){
            $order_model = $order_model->where('collective_id',$collective_id);
        }

        // 用户ID
        $user_id = request()->user_id;
        if(!empty($user_id)){
            $order_model = $order_model->where('user_id',$user_id);
        }

        // 店铺ID
        $store_id = request()->store_id;
        if(!empty($store_id)){
            $order_model = $order_model->where('store_id',$store_id);
        }

        // 下单时间
        $created_at = request()->created_at;
        if(!empty($created_at)){
            $order_model = $order_model->whereBetween('created_at',[$created_at[0],$created_at[1]]);
        }
        
        // 订单状态
        if(isset(request()->order_status) && request()->order_status!=-1){
            $order_model = $order_model->where('order_status',request()->order_status);
        }

        // 获取退款订单
        if(isset(request()->is_refund)){
            $order_model = $order_model->where('order_status',5)->where('refund_status',0);
        }

        // 获取退货订单
        if(isset(request()->is_return)){
            $order_model = $order_model->where('order_status',5)->where('refund_status',1);
        }

        $order_model = $order_model->orderBy('id','desc')
                    ->paginate(request()->per_page??30);
        return $this->format($order_model);
    }

    // 获取订单信息通过订单ID 默认是需要用用户
    public function getOrderInfoById($id,$auth='user'){
        $order_model = new Order();

        if($auth=='user'){
            $user_service = new UserService;
            $user_info = $user_service->getUserInfo();
            $order_model = $order_model->where('user_id',$user_info['id']);
        }

        if($auth=='seller'){
            $store_id = $this->get_store(true);
            $order_model = $order_model->where('store_id',$store_id);
        }

        $order_info = $order_model->with('order_goods')->where('id',$id)->first();
        return $this->format($order_info);
    }

    // 获取订单状态 
    public function getOrderStatusCn($order_info){
        $cn = '未知订单';
        switch($order_info['order_status']){
            case 0:
                $cn = __('admins.order_cancel');
                break;
            case 1:
                $cn = __('admins.wait_pay');
                break;
            case 2:
                $cn = __('admins.wait_send');
                break;
            case 3:
                $cn = __('admins.order_confirm');
                break;
            case 4:
                $cn = __('admins.wait_comment');
                break;
            case 5:
                if($order_info['refund_type'] == 0){
                    $cn = __('admins.order_refund');
                }elseif($order_info['refund_type'] == 1){
                    $cn = __('admins.order_returned');
                }else{
                    $cn = __('admins.order_refund_over');
                }
                
                break;
            case 6:
                $cn = __('admins.order_completion');
                break;
        }
        return $cn;
    }

    // 获取支付类型
    public function getOrderPayMentCn($payment_name){
        $cn = '未支付';
        switch($payment_name){
            case 'wechat':
                $cn = __('admins.payment_wechat');
                break;
            case 'ali':
                $cn = __('admins.payment_ali');
                break;
            case 'money':
                $cn = __('admins.payment_money');
                break;
        }
        return $cn;
    }
    
}
