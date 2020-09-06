<?php
namespace App\Services;

use App\Http\Resources\Home\OrderResource\CreateOrderAfterCollection;
use App\Models\Address;
use App\Models\Goods;
use App\Models\GoodsSku;
use App\Models\Order;
use App\Models\OrderGoods;
use App\Traits\HelperTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderService extends BaseService{

    use HelperTrait;

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

        // 地址验证
        $address_resp = $this->checkAddress();
        if(!$address_resp['status']){
            return $this->format_error($rs['msg']);
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

        // 循环生成订单 多个商家则生成多个订单
        try{
            DB::beginTransaction();
                $resp_data = [];
                foreach($rs['data'] as $v){
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
                    ];

                    $order_info = $order_model->create($order_data); // 订单数据插入数据库

                    // 初始化其他费用
                    $total_price = 0 ; // 总金额
                    $order_price = 0 ; // 订单总金额
                    $freight_money = 0; // 运费

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
                        ];
                        
                        $order_goods_model->create($order_goods_data); // 插入订单商品表

                        // 开始减去库存
                        $this->orderStock($order_goods_data['goods_id'],$order_goods_data['sku_id'],$order_goods_data['buy_num']);

                        // 将金额全部计算加载到订单里面
                        $order_price += $order_goods_data['total_price'];
                    }


                    // 订单总金额做修改，然后保存
                    $total_price = $order_price+$freight_money; // 暂时总金额等于[订单金额+运费]
                    $order_info->total_price = $total_price;
                    $order_info->order_price = $order_price;
                    $order_info->save(); // 保存入数据库

                    $resp_data['order_id'][] = $order_info->id;
                    $resp_data['order_no'][] = $make_rand;
                }
            DB::commit();
            return $this->format($resp_data);
        }catch(\Exception $e){
            Log::channel('qwlog')->debug('createOrder:'.json_encode($e->getMessage()));
            DB::rollBack();
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

    // 地址验证
    protected function checkAddress(){
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

    // 根据订单ID获取商品数据并格式化
    public function createOrderFormat($params){
        $goods_model = new Goods();
        $goods_sku_model = new GoodsSku();
        $list = [];
        foreach($params['order'] as $v){
            $data = [];
            $data = $goods_model->with(['store'=>function($q){
                return $q->select('id','store_name','store_logo');
            }])->select('id','store_id','goods_name','goods_master_image','goods_price','goods_stock')->where('id',$v['goods_id'])->first();
            $data['sku_name'] = '-';
            $data['buy_num'] = abs(intval($v['buy_num']));
            $data['sku_id'] = $v['sku_id'];
            if($v['sku_id']>0){
                $goods_sku = $goods_sku_model->select('id','sku_name','goods_price','goods_stock')->where('goods_id',$v['goods_id'])->where('id',$v['sku_id'])->first();
                $data['sku_name'] = $goods_sku['sku_name'];
                $data['goods_price'] = $goods_sku['goods_price'];
                $data['goods_stock'] = $goods_sku['goods_stock'];
            }

            $data['goods_master_image'] = $this->thumb($data['goods_master_image'],150);
            $data['total'] = round($v['buy_num']*$data['goods_price']);
            
            $list[$data['store']['id']]['goods_list'][] = $data;
            $list[$data['store']['id']]['store_info'] = $data['store'];

            // 判断是否库存足够
            if($v['buy_num']>$data['goods_stock']){
                return $this->format_error(_('orders.stock_error'));
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
            return $this->format_error(_('orders.error'));
        }

        // 判断是否能解析
        try{
            $params = json_decode(base64_decode($base64),true);
        }catch(\Exception $e){
            return $this->format_error(_('orders.error').'2');
        }
        return $this->format($params);
    }
    
    
}
