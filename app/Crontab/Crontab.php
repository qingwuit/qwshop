<?php

namespace App\Crontab;

use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Models\CrontabDay;
use App\Models\CrontabMonth;
use App\Models\CrontabWeek;
use App\Models\Goods;
use App\Models\Order;
use App\Models\Store;
use App\Models\StoreComment;
use App\Models\Users;
use Illuminate\Support\Facades\Log;

/**
 * CronTab
 *
 * @Description 定时任务
 * @author hg <www.qingwuit.com>
 */
class Crontab extends Controller{
    
    // 统计每天 每个店铺的订单量，订单价格，平台入驻用户量，平台商家入驻数量，平台商品添加
    public function crontab_day(){
        $crontab_model = new CrontabDay();
        $user_model = new Users();
        $store_model = new Store();
        $goods_model = new Goods();
        $order_model = new Order();
        $now_format_time = date('Y-m-d');
        $last_format_time = date('Y-m-d',strtotime('-1 day',strtotime($now_format_time)));
        $info = $crontab_model->where('format_time',$last_format_time)->first();
        
        // 查看是否存在了
        $need_info = $crontab_model->where('format_time',$now_format_time)->first();
        if(!empty($need_info)){
            return;
        }

        $isData = true;

        if(empty($info)){
            $isData = false; // 没有数据
        }

        $data = [
            'users'         =>  0,
            'store'         =>  0,
            'orders'        =>  0,
            'price'         =>  0,
            'goods'         =>  0,
            'user_id'       =>  0,
            'format_time'   => $now_format_time,
            'times'         => strtotime($now_format_time),
            'add_time'      =>  time(),
        ];

        $user_count = $user_model->count();
        $store_count = $store_model->count();
        $data['users'] = $isData?$user_count-$info['users']:$user_count;
        $data['store'] = $isData?$store_count-$info['store']:$store_count;

        $allData = [];

        $store_list = $store_model->get();
        
        // 循环计算每个店铺
        foreach($store_list as $v){

            $data['user_id'] = $v['user_id'];

            // 商品
            $goods_count = $goods_model->where('user_id',$v['user_id'])->count();
            $data['goods'] = $isData?$goods_count-$info['goods']:$goods_count;

            // 订单量
            $order_count = $order_model->where('seller_id',$v['user_id'])->count();
            $data['orders'] = $isData?$order_count-$info['orders']:$order_count;

            // 订单价格
            $price_count = $order_model->where('seller_id',$v['user_id'])->sum('total_price');
            $data['price'] = $isData?$price_count-$info['price']:$price_count;

            $allData[] = $data;
        }
        

        $crontab_model->insert($allData);
    }

    // 每周统计
    public function crontab_week(){
        $crontab_model = new CrontabWeek();
        $user_model = new Users();
        $store_model = new Store();
        $goods_model = new Goods();
        $order_model = new Order();
        $now_format_time = date('Y-m-d');
        $last_format_time = date('Y-m-d',strtotime('-1 day',strtotime($now_format_time)));
        $info = $crontab_model->where('format_time',$last_format_time)->first();

        // 查看是否存在了
        $need_info = $crontab_model->where('format_time',$now_format_time)->first();
        if(!empty($need_info)){
            return;
        }

        $isData = true;

        if(empty($info)){
            $isData = false; // 没有数据
        }

        $data = [
            'users'         =>  0,
            'store'         =>  0,
            'orders'        =>  0,
            'price'         =>  0,
            'goods'         =>  0,
            'user_id'       =>  0,
            'format_time'   => $now_format_time,
            'times'         => strtotime($now_format_time),
            'add_time'      =>  time(),
        ];

        $user_count = $user_model->count();
        $store_count = $store_model->count();
        $data['users'] = $isData?$user_count-$info['users']:$user_count;
        $data['store'] = $isData?$store_count-$info['store']:$store_count;

        $allData = [];

        $store_list = $store_model->get();
        
        // 循环计算每个店铺
        foreach($store_list as $v){

            $data['user_id'] = $v['user_id'];

            // 商品
            $goods_count = $goods_model->where('user_id',$v['user_id'])->count();
            $data['goods'] = $isData?$goods_count-$info['goods']:$goods_count;

            // 订单量
            $order_count = $order_model->where('seller_id',$v['user_id'])->count();
            $data['orders'] = $isData?$order_count-$info['orders']:$order_count;

            // 订单价格
            $price_count = $order_model->where('seller_id',$v['user_id'])->sum('total_price');
            $data['price'] = $isData?$price_count-$info['price']:$price_count;

            $allData[] = $data;
        }
        
        $crontab_model->insert($allData);
    }

    // 每周统计
    public function crontab_month(){
        $crontab_model = new CrontabMonth();
        $user_model = new Users();
        $store_model = new Store();
        $goods_model = new Goods();
        $order_model = new Order();
        $now_format_time = date('Y-m-d');
        $last_format_time = date('Y-m-d',strtotime('-1 month',strtotime($now_format_time)));
        $info = $crontab_model->where('format_time',$last_format_time)->first();

        // 查看是否存在了
        $need_info = $crontab_model->where('format_time',$now_format_time)->first();
        if(!empty($need_info)){
            return;
        }
        
        $isData = true;

        if(empty($info)){
            $isData = false; // 没有数据
        }

        $data = [
            'users'         =>  0,
            'store'         =>  0,
            'orders'        =>  0,
            'price'         =>  0,
            'goods'         =>  0,
            'user_id'       =>  0,
            'format_time'   => $now_format_time,
            'times'         => strtotime($now_format_time),
            'add_time'      =>  time(),
        ];

        $user_count = $user_model->count();
        $store_count = $store_model->count();
        $data['users'] = $isData?$user_count-$info['users']:$user_count;
        $data['store'] = $isData?$store_count-$info['store']:$store_count;

        $allData = [];

        $store_list = $store_model->get();
        
        // 循环计算每个店铺
        foreach($store_list as $v){

            $data['user_id'] = $v['user_id'];

            // 商品
            $goods_count = $goods_model->where('user_id',$v['user_id'])->count();
            $data['goods'] = $isData?$goods_count-$info['goods']:$goods_count;

            // 订单量
            $order_count = $order_model->where('seller_id',$v['user_id'])->count();
            $data['orders'] = $isData?$order_count-$info['orders']:$order_count;

            // 订单价格
            $price_count = $order_model->where('seller_id',$v['user_id'])->sum('total_price');
            $data['price'] = $isData?$price_count-$info['price']:$price_count;

            $allData[] = $data;
        }
        

        $crontab_model->insert($allData);
    }

    // 定时确认收货
    public function complete_order(){
        $order_model = new Order();
        $config_model = new Config();

        try{
            $config_info = json_decode($config_model->get_format_config()['task_time'],true);
            $where_time = time()-$config_info['auto_confirm']*86400;
            $order_list = $order_model->where(['delivery_status'=>1,'order_status'=>0])->where('delivery_time','<=',$where_time)->get();

            if(empty($order_list)){
                return; 
            }

            foreach($order_list as $v){
                $order_model->complete_order($v['id']);
            }

        }catch(\Exception $e){
            Log::channel('qwlog')->info($e->getMessage());
        }

        echo '执行 '. count($order_list) . '条数据';
    }

    // 定时执行未评论，但是确认的订单
    public function comment_order(){
        $order_model = new Order();
        $config_model = new Config();
        $user_model = new Users();
        $store_comment_model = new StoreComment();
        try{
            $config_info = json_decode($config_model->get_format_config()['task_time'],true);
            $where_time = time()-$config_info['auto_comment']*86400;
            $order_list = $order_model->with('order_goods')->where(['delivery_status'=>1,'comment_status'=>0])->where('complete_time','<=',$where_time)->get();

            $order_ids = [];
            
            if(empty($order_list)){
                return; 
            }

            foreach($order_list as $v){
                $user_info = $user_model->find($v['user_id']);
                $order_ids[] = $v['id'];
                foreach($v['order_goods'] as $vo){
                    $data['content'] = '很不错的！';
                    $data['score'] = 5;
                    $data['agree'] = 5;
                    $data['service'] = 5;
                    $data['speed'] = 5;
                    $data['store_id'] = $v['store_id'];
                    $data['seller_id'] = $v['seller_id'];
                    $data['store_name'] = $v['store_name'];
                    $data['user_id'] = $v['user_id'];
                    $data['nickname'] = $v['user_nickname'];
                    $data['avatar'] = $user_info['avatar'];
                    $data['goods_id'] = $vo['goods_id'];
                    $data['goods_name'] = $vo['goods_name'];
                    $data['goods_image'] = $vo['goods_image'];
                    $data['comment_images'] = '';
                    $data['add_time'] = time();
                    $store_comment_model->addComment($data);
                }
            }

            $order_model->whereIn('id',$order_ids)->update(['comment_status'=>1,'comment_time'=>time()]);

        }catch(\Exception $e){
            Log::channel('qwlog')->info($e->getMessage());
        }

        echo '执行 '. count($order_list) . '条数据';
    }

    // 定时执行 未支付订单
    public function cancel_order(){
        $order_model = new Order();
        $config_model = new Config();

        try{

            $config_info = json_decode($config_model->get_format_config()['task_time'],true);
            $where_time = time()-$config_info['auto_cancel']*86400;
            $order_list = $order_model->where(['pay_status'=>0,'order_status'=>0])->where('add_time','<=',$where_time)->get();

            if(empty($order_list)){
                return; 
            }

            $order_ids = [];

            foreach($order_list as $v){
                $order_ids[] = $v['id'];
            }

            $order_model->whereIn('id',$order_ids)->update(['order_status'=>2,'close_time'=>time()]);

        }catch(\Exception $e){
            Log::channel('qwlog')->info($e->getMessage());
        }

        echo '执行 '. count($order_list) . '条数据';
    }
    
}