<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Goods;
use App\Models\Store;
use App\Models\GoodsSpec;
use App\Models\Cart;
use App\Models\Address;
use App\Models\OrderGoods;
use App\Models\FreightTemplate;
use App\Models\MoneyLog;

class Order extends Model
{
    protected $table = "orders"; //指定表
    protected $primaryKey = "id"; //指定id字段
    public $timestamps = false;


    public function create_order($data){

        // 是否是购物车订单
        $is_cart = $data['is_cart'];

        // 是否余额支付
        $is_money_pay = request()->input('is_money_pay')??false;
        $is_money_pay = $is_money_pay=='true'?true:false;

        if($is_cart){
            $store_list = $this->get_befor_order($data['data'],true);
        }else{
            $store_list = $this->get_befor_order($data['data'],false);
        }
        
        // 随机数生成  订单号
        $make_rand = make_rand();

        // 用户信息
        $user_info = auth()->user();

        // 默认快递信息
        $address_model = new Address();
        $address_info = $address_model->where(['user_id'=>$user_info['id'],'is_default'=>1])->first();
        $area_info = explode(' ',$address_info['area_info']);

        $is_balance = false;  // 整个订单 是否余额支付

        // 实例化订单商品表
        $order_goods_model = new OrderGoods();
        // 实例化快递模版
        $freight_template_model = new FreightTemplate();
        $goods_spec_model = new GoodsSpec(); // 实例化规格属性
        $goods_model = new Goods();
        $user_model = new Users();

        // 团购表
        $groupbuy_model = new Groupbuy();

        try{
            // 开启事务
            DB::beginTransaction();
            
            // 多个商家生成订单  会生成多个订单，但是订单号一样，方便支付
            foreach($store_list['data'] as $v){

                // 订单表
                $order_data = [
                    'order_no'              =>  $make_rand,
                    'user_id'               =>  $user_info['id'],
                    'user_nickname'         =>  $user_info['nickname'],
                    'seller_id'             =>  $v[0]['seller_id'],
                    'store_id'              =>  $v[0]['store_id'],
                    'store_name'            =>  $v[0]['store_name'],
                    'store_logo'            =>  $v[0]['store_logo'],
                    'order_name'            =>  $v[0]['goods_name'].'等',
                    'image'                 =>  $v[0]['image'],
                    'total_price'           =>  0,
                    'order_price'           =>  0,
                    'freight_money'         =>  0,
                    'province'              =>  $area_info[0],
                    'city'                  =>  $area_info[1],
                    'region'                =>  $area_info[2],
                    'address'               =>  $address_info['address'],
                    'receive_tel'           =>  $address_info['receive_tel'],
                    'receive_name'          =>  $address_info['receive_name'],
                    'remark'                =>  $data['remark'],
                    'balance'               =>  0,
                    'add_time'              =>  time(),
                ];
                
                // 初始化 订单价格
                $order_price = 0;
                $total_price = 0;
                $freight_money = 0;
                $freight_template = [];

                // 订单商品表
                $order_goods_data_all = [];
                foreach($v as $vo){
                    $order_goods_data = [
                        'order_id'          =>  '',
                        'spec_id'           =>  $vo['spec_id']??0,
                        'goods_id'          =>  $vo['goods_id'],
                        'goods_name'        =>  $vo['goods_name'],
                        'image'             =>  $vo['image'],
                        'total_price'       =>  $vo['goods_price']*$vo['goods_num'],
                        'goods_price'       =>  $vo['goods_price'],
                        'goods_num'         =>  $vo['goods_num'],
                        'goods_spec'        =>  $vo['goods_spec']??'',
                    ];

                    // 团购则取团购价格
                    if(isset($data['is_groupbuy'])){
                        $order_goods_data['total_price'] = $vo['groupbuy_price']*$vo['goods_num'];
                    }

                    $order_goods_data_all[] = $order_goods_data;
                    
                    // 设置订单总金额
                    $order_price += $order_goods_data['total_price'];

                    // 判断中间是否有快递模版  同一个店铺订单 优先模版  取最多钱的
                    if($vo['freight_id']>0){
                        $freight_template[] = $vo['freight_id'];
                    }
                    
                    if($vo['goods_freight']>$freight_money){
                        $freight_money = $vo['goods_freight'];
                    }

                    // 修改库存
                    if($order_goods_data['spec_id']>0){
                        $goods_spec_model->where('id',$order_goods_data['spec_id'])->decrement('goods_num',$order_goods_data['goods_num']); // 修改库存
                    }else{
                        $goods_model->where('id',$order_goods_data['goods_id'])->decrement('goods_num',$order_goods_data['goods_num']); // 修改库存
                    }
                    
                }

                // 重新赋值统计数据
                $order_data['order_price'] = $order_price;
                
                // 计算运费
                $freight_template_list = $freight_template_model->whereIn('id',$freight_template)->get()->toArray();
                $freight_money_template = $freight_template_model->high_freight($freight_template_list,$address_info['region_id']); // 优先获取最高的运费
                if($freight_money_template>$freight_money){
                    $freight_money = $freight_money_template;
                }
                
                // 判断商家是否有满减 进行满减处理
                if($order_price>$v[0]['free_freight']){
                    $freight_money = 0;
                }

                // 总金额加上运费
                $total_price = $order_price+$freight_money;
                $order_data['total_price'] = $total_price;

                // 运费插入订单表
                $order_data['freight_money'] = $freight_money;

                // 余额是否足够
                if(!empty($is_money_pay) && $total_price>$user_info['money']){
                    $order_data['balance'] = $user_info['money'];
                    $user_info['money'] = 0; // 清空余额
                }elseif(!empty($is_money_pay)){ // 余额直接支付完成
                    $order_data['balance'] = $total_price;
                    $order_data['pay_type'] = 'money_pay';
                    $order_data['pay_status'] = 1;
                    $order_data['pay_time'] = time();
                    $is_balance = true;
                }

                // 如果团购则订单带上团购标识 
                if(isset($data['is_groupbuy'])){
                    $order_data['is_groupbuy'] = 1;
                }
                
                // 插入订单表
                $order_id = $this->insertGetId($order_data);

                // 拥有余额支付则 将余额加入冻结资金
                if($order_data['balance']>0){
                    $user_model->money_change('订单支付','money',-$order_data['balance'],$user_info,'order_no:'.$make_rand,'余额支付');
                    $user_model->money_change('订单冻结','frozen_money',$order_data['balance'],$user_info,'order_no:'.$make_rand,'余额支付');
                }

                // 订单商品表插入订单号 
                foreach($order_goods_data_all as $key=>$vo){
                    $vo['order_id'] = $order_id;
                    $order_goods_data_all[$key] = $vo;
                }

                $order_goods_model->insert($order_goods_data_all);

                // 生成团购数据
                if(isset($data['is_groupbuy']) && $is_balance){
                    $groupbuy_need = $v[0];
                    $groupbuy_need['order_id'] = $order_id;
                    $groupbuy_need['seller_id'] = $v[0]['seller_id'];
                    $groupbuy_need['order_no'] = $make_rand;
                    $groupbuy_need['user_info'] = $user_info;
                    $groupbuy_model->add_groupbuy($groupbuy_need);
                }
                
            } // 结束循环

            // 删除购物车 数据
            if($is_cart){
                $cart_model = new Cart();
                $cart_model->whereIn('id',$store_list['cart_id'])->delete();
            }

            // 执行无错误则提交
            DB::commit();
            return ['status'=>true,'msg'=>'ok','data'=>['order_no'=>$make_rand,'is_balance'=>$is_balance]];
        }catch(\Exception $e){
            DB::rollBack();
            return ['status'=>false,'msg'=>$e->getMessage()];
        }

    }

    // 根据订单号获取订单信息
    public function getOrderInfoByOrderNo($order_no){
        return $this->where('order_no',$order_no)->get()->toArray();
    }


    // 获取订单信息
    public function get_befor_order($data,$is_cart,$is_groupbuy=false){
        $goods_model = new Goods();
        $store_model = new Store();
        $goods_spec_model = new GoodsSpec();
        $info = $data;
        $infoArr = explode(',',$info);
        $cart_ids = [];
        $store_arr = [];
        foreach($infoArr as $k=>$v){
            $res=  [];
            $res = explode('|',$v);
            if($is_cart){
                $is_cart_arr = explode('-',$res[0]);
                $res[0] = $is_cart_arr[0];
                $cart_ids[] = $is_cart_arr[1];
            }
            $store_arr[$res[1]]['id'][$k] = $res[0];
            if(isset($res[3])){
                $store_arr[$res[1]]['spec_id'][$k] = $res[3];
            }
            if(isset($res[2])){
                $store_arr[$res[1]]['buy_num'][$k] = $res[2];
            }
        }

        // 看是从购物车过来还是直接购买
        $arr = [];
        foreach($store_arr as $k=>$v){
            $store_info = $store_model->find($k);
            $arr[$k] = $goods_model->whereIn('id',$v['id'])->get()->toArray();
            
            foreach($arr[$k] as $key=>$vo){
                $vo['store_id'] = $store_info['id'];
                $vo['store_name'] = $store_info['store_name'];
                $vo['store_logo'] = $store_info['store_logo'];
                $vo['seller_id'] = $store_info['user_id'];
                $vo['free_freight'] = $store_info['free_freight'];
                $vo['image'] = get_format_image($vo['goods_master_image'],200);
                $vo['goods_id'] = $vo['id'];

                $index = array_search($vo['goods_id'],$v['id']); // 对应的排序位置
                
                if(isset($v['spec_id'][$index])){
                    $spec_info = $goods_spec_model->find($v['spec_id'][$index]);
                    $vo['spec_id'] = $v['spec_id'][$index];
                    $vo['goods_spec'] = $spec_info['spec_name'];
                    $vo['goods_price'] = $spec_info['goods_price'];
                }

                if($is_groupbuy){
                    $vo['goods_price'] = $vo['groupbuy_price'];
                }

                if(isset($v['buy_num'][$index])){
                    $vo['goods_num'] = $v['buy_num'][$index];
                }
                $arr[$k][$key] = $vo;
            }
        }
        $store_arr = array_merge($arr);
        
        $res_arr['data'] = $store_arr;
        $res_arr['cart_id'] = $cart_ids;

        return $res_arr;
    }

    // 确定收货
    public function complete_order($order_id,$user_id=0){
        if(!empty($user_id)){
            $this->where('id',$order_id)->where('user_id',$user_id)->update(['order_status'=>1,'complete_time'=>time()]);
        }else{
            $this->where('id',$order_id)->update(['order_status'=>1,'complete_time'=>time()]);
        }

        // 将钱返回给商家
        $user_model = new Users();
        $order_info = $this->find($order_id);
        $buyer_info = $user_model->find($order_info['user_id']);
        $seller_info = $user_model->find($order_info['seller_id']);

        // 扣除用户冻结金额
        $user_model->money_change('订单确认','frozen_money',-$order_info['total_price'],$buyer_info,'order_no:'.$order_info['order_no'],'订单确认');

        // 付给商家的钱到账
        $user_model->money_change('订单确认','money',$order_info['total_price'],$seller_info,'order_no:'.$order_info['order_no'],'订单确认');

        return true;
    }

    // 模行关联
    public function order_goods(){
        return $this->hasMany('App\Models\OrderGoods','order_id','id');
    }


}
