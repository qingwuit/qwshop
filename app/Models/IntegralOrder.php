<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class IntegralOrder extends Model
{
    protected $table = "integral_order"; //指定表
    protected $primaryKey = "id"; //指定id字段
    public $timestamps = false;


    // 生成积分订单
    public function create_order($data){

        
        // 随机数生成  订单号
        $make_rand = make_rand();

        // 用户信息
        $user_info = auth()->user();

        // 默认快递信息
        $address_model = new Address();
        $address_info = $address_model->where(['user_id'=>$user_info['id'],'is_default'=>1])->first();
        $area_info = explode(' ',$address_info['area_info']);


        // 实例化订单商品表
        $integral_order_goods_model = new IntegralOrderGoods();
        // 实例化快递模版

        $integral_goods_model = new IntegralGoods();
        $money_log_model = new MoneyLog();

        $integral_goods_info = $integral_goods_model->find($data['data'][0]);

        try{
            // 开启事务
            DB::beginTransaction();
            
            // 多个商家生成订单  会生成多个订单，但是订单号一样，方便支付

            // 订单表
            $order_data = [
                'order_no'              =>  $make_rand,
                'user_id'               =>  $user_info['id'],
                'user_nickname'         =>  $user_info['nickname'],
                'order_name'            =>  $integral_goods_info['goods_name'].'等',
                'image'                 =>  get_format_image($integral_goods_info['goods_master_image'],200),
                'total_price'           =>  0,
                'province'              =>  $area_info[0],
                'city'                  =>  $area_info[1],
                'region'                =>  $area_info[2],
                'address'               =>  $address_info['address'],
                'receive_tel'           =>  $address_info['receive_tel'],
                'receive_name'          =>  $address_info['receive_name'],
                'remark'                =>  $data['remark'],
                'pay_status'            =>  1,
                'pay_time'              =>  time(),
                'add_time'              =>  time(),
            ];
            
            // 初始化 订单价格
            $total_price = 0;

            // 订单商品表
            $order_goods_data_all = [];
            $order_goods_data = [
                'order_id'          =>  '',
                'goods_id'          =>  $integral_goods_info['id'],
                'goods_name'        =>  $integral_goods_info['goods_name'],
                'image'             =>  get_format_image($integral_goods_info['goods_master_image'],200),
                'total_price'       =>  $integral_goods_info['goods_price']*$data['data'][1],
                'goods_price'       =>  $integral_goods_info['goods_price'],
                'goods_num'         =>  $data['data'][1],
            ];
            $order_goods_data_all[] = $order_goods_data;
            

           
            $integral_goods_model->where('id',$order_goods_data['goods_id'])->decrement('goods_num',$data['data'][1]); // 修改库存
                

            // 总金额加上运费
            $total_price = $integral_goods_info['goods_price']*$data['data'][1];
            $order_data['total_price'] = $total_price;

            // 余额是否足够
            if($total_price>$user_info['integral']){
                return ['status'=>false,'msg'=>'积分不足'];
            }
            
            // 插入订单表
            $order_id = $this->insertGetId($order_data);

            // 订单商品表插入订单号 
            foreach($order_goods_data_all as $key=>$vo){
                $vo['order_id'] = $order_id;
                $order_goods_data_all[$key] = $vo;
            }

            $integral_order_goods_model->insert($order_goods_data_all);
            $user_model = new Users();
            $user_model->where('id',$user_info['id'])->decrement('integral',$total_price);
                

            // 资金变更日志
            $log_data = [
                'log_no'        =>  $make_rand,
                'user_id'       =>  $user_info['id'],
                'nickname'      =>  $user_info['nickname'],
                'name'          =>  '积分兑换',
                'integral'      =>  -$total_price,
                'pay_type'      =>  '-',
            ];
            $money_log_model->addLog($log_data);

            // 执行无错误则提交
            DB::commit();
            return ['status'=>true,'msg'=>'ok','data'=>['order_no'=>$make_rand]];
        }catch(\Exception $e){
            DB::rollBack();
            return ['status'=>false,'msg'=>$e->getMessage()];
        }

    }

    // 模行关联
    public function integral_order_goods(){
        return $this->hasMany('App\Models\IntegralOrderGoods','order_id','id');
    }
}
