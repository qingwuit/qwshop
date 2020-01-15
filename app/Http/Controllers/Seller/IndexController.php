<?php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\CrontabDay;
use App\Models\CrontabMonth;
use App\Models\OrderGoods;
use App\Models\Store;
use Illuminate\Support\Facades\DB;

class IndexController extends BaseController
{
    public function get_statistics(Order $order_model,CrontabDay $crontab_day_model,CrontabMonth $crontab_month_model,OrderGoods $order_goods_model){

        $user_info = auth()->user();
        
        $data['total_price'] = $order_model->where('seller_id',$user_info['id'])->sum('total_price');// 平台总销售量
        $data['today_total_price'] = $order_model->where('seller_id',$user_info['id'])->where('pay_time','>',strtotime(date('Y-m-d')))->where('pay_time','<',strtotime('+1 day',strtotime(date('Y-m-d')))-1)->sum('total_price');// 今日销售
        $data['pay_order'] = $order_model->where('seller_id',$user_info['id'])->where(get_order_params('NOPAY'))->count();// 未支付订单
        $data['wait_order'] = $order_model->where('seller_id',$user_info['id'])->where(get_order_params('WAITSEND'))->count();// 等待发货
        $data['complete_order'] = $order_model->where('seller_id',$user_info['id'])->where(get_order_params('COMPLETE'))->count();// 完成

        // 会员趋势
        $now_week = date('N');
        $where = [];
        $f = date('Y-m-d',strtotime('-'.$now_week.' day'));
        $day_time = 24*60*60;
        $where[] = $f;
        for($i=0;$i<6;$i++){
            $times = (($i+1)*$day_time);
            $where[] = date('Y-m-d',strtotime($f)+$times);
        }
        $data['week'] = $crontab_day_model->where('user_id',$user_info['id'])->select('users','format_time')->whereIn('format_time',$where)->orderBy('id','asc')->groupBy('format_time')->get()->toArray();
        for($i=0;$i<7;$i++){
            if(!isset($data['week'][$i])){
                $data['week'][] = ['users'=>0];
            }
        }

        // 上周
        $now_week = date('N');
        $where = [];
        $f = date('Y-m-d',strtotime('-'.($now_week+6).' day'));
        $day_time = 24*60*60;
        $where[] = $f;
        for($i=0;$i<6;$i++){
            $times = (($i+1)*$day_time);
            $where[] = date('Y-m-d',strtotime($f)+$times);
        }
        
        $data['week2'] = $crontab_day_model->where('user_id',$user_info['id'])->select('users','format_time')->whereIn('format_time',$where)->orderBy('id','asc')->groupBy('format_time')->get()->toArray();
        for($i=0;$i<7;$i++){
            if(!isset($data['week2'][$i])){
                $data['week2'][] = ['users'=>0];
            }
        }


        // 销售趋势
        $where = [];
        for($i=0;$i<12;$i++){
            $times = $i+1;
            $time_str = '0'.$times;
            if($times>9){
                $time_str  = $times;
            }
            $where[] = date('Y'.'-'.$time_str.'-01');
        }
        $data['month'] = $crontab_month_model->where('user_id',$user_info['id'])->select('price','format_time')->whereIn('format_time',$where)->orderBy('id','asc')->groupBy('format_time')->get()->toArray();
        for($i=0;$i<12;$i++){
            if(!isset($data['month'][$i])){
                $data['month'][] = ['price'=>0];
            }
        }

        // 销售店铺排行
        $store_list = $order_goods_model->select(DB::Raw("(select sum(goods_price*goods_num) from order_goods as B where B.goods_id=order_goods.goods_id) as all_price,order_goods.goods_name,order_goods.goods_id"))->join('goods','goods.id','order_goods.goods_id')->where('goods.user_id',$user_info['id'])->orderBy('all_price','desc')->groupBy('order_goods.goods_id')->take(6)->get();// 平台总销售量
        $data['store'] = [];
        for($i=0;$i<6;$i++){
            if(!isset($store_list[$i])){
                $data['store'][] = ['store_name'=>'-','sum_total'=>0.00];
            }else{
                $data['store'][] = ['store_name'=>$store_list[$i]['order_name'],'sum_total'=>$store_list[$i]['all_price']];
            }
            
        }

        return $this->success_msg('ok',$data);

    }
}
