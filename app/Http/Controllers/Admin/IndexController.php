<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CrontabDay;
use App\Models\CrontabMonth;
use App\Models\Order;
use App\Models\Store;
use Illuminate\Support\Facades\DB;

class IndexController extends BaseController
{
    public function get_statistics(Order $order_model,CrontabDay $crontab_day_model,CrontabMonth $crontab_month_model,Store $store_model){
        
        $data['total_price'] = $order_model->sum('total_price');// 平台总销售量
        $data['today_total_price'] = $order_model->where('pay_time','>',strtotime(date('Y-m-d')))->where('pay_time','<',strtotime('+1 day',strtotime(date('Y-m-d')))-1)->sum('total_price');// 今日销售
        $data['pay_order'] = $order_model->where(get_order_params('NOPAY'))->count();// 未支付订单
        $data['wait_order'] = $order_model->where(get_order_params('WAITSEND'))->count();// 等待发货
        $data['complete_order'] = $order_model->where(get_order_params('COMPLETE'))->count();// 完成

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
        $data['week'] = $crontab_day_model->select('users','format_time')->whereIn('format_time',$where)->orderBy('id','asc')->groupBy('format_time')->get()->toArray();
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
        
        $data['week2'] = $crontab_day_model->select('users','format_time')->whereIn('format_time',$where)->orderBy('id','asc')->groupBy('format_time')->get()->toArray();
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
        $data['month'] = $crontab_month_model->select('price','format_time')->whereIn('format_time',$where)->orderBy('id','asc')->groupBy('format_time')->get()->toArray();
        for($i=0;$i<12;$i++){
            if(!isset($data['month'][$i])){
                $data['month'][] = ['price'=>0];
            }
        }

        // 销售店铺排行
        $store_list = $crontab_day_model->select(DB::Raw("(select sum(price) from crontab_day as B where B.user_id = crontab_day.user_id) as sum_total,crontab_day.user_id"))->groupBy('user_id')->orderBy('sum_total','desc')->take(6)->get();
        $data['store'] = [];
        for($i=0;$i<6;$i++){
            if(!isset($store_list[$i])){
                $data['store'][] = ['store_name'=>'-','sum_total'=>0.00];
            }else{
                $store_info = $store_model->where('user_id',$store_list[$i]['user_id'])->first();
                $data['store'][] = ['store_name'=>$store_info['store_name'],'sum_total'=>$store_list[$i]['sum_total']];
            }
            
        }

        return $this->success_msg('ok',$data);

    }
}
