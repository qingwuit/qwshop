<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // 首页
    public function all()
    {
        // 时间条件
        $created_at = request()->created_at;
        $is_type = request()->is_type;

        
        $data['total_price'] = $this->getService('Order', true)->where('order_status', '>', 1)->where('refund_status','<',2)->sum('total_price');// 总销售额
        $data['today_price'] = $this->getService('Order', true)->where('order_status', '>', 1)->where('refund_status','<',2)->where('pay_time', '>=', now()->startOfDay()->toDateTimeString())->sum('total_price'); // 今日销售额
        $data['yesterday_price'] = $this->getService('Order', true)->where('order_status', '>', 1)->where('refund_status','<',2)->where('pay_time', '<', now()->startOfDay()->toDateTimeString())->where('pay_time', '>=', Carbon::yesterday()->startOfDay()->toDateTimeString())->sum('total_price'); // 昨日销售额
        $data['week_price'] = $this->getService('Order', true)->where('order_status', '>', 1)->where('refund_status','<',2)->where('pay_time', '>=', Carbon::now()->startOfWeek()->startOfDay()->toDateTimeString())->sum('total_price'); // 这周销售额
        $data['last_week_price'] = $this->getService('Order', true)->where('order_status', '>', 1)->where('refund_status','<',2)->where('pay_time', '<', Carbon::now()->startOfWeek()->startOfDay()->toDateTimeString())->where('pay_time', '>=', Carbon::now()->subWeeks(1)->startOfWeek()->startOfDay()->toDateTimeString())->sum('total_price'); // 上周销售额
        $data['month_price'] = $this->getService('Order', true)->where('order_status', '>', 1)->where('refund_status','<',2)->where('pay_time', '>=', Carbon::now()->startOfMonth()->startOfDay()->toDateTimeString())->sum('total_price'); // 这月销售额
        $data['last_month_price'] = $this->getService('Order', true)->where('order_status', '>', 1)->where('refund_status','<',2)->where('pay_time', '<', Carbon::now()->startOfMonth()->startOfDay()->toDateTimeString())->where('pay_time', '>=', Carbon::now()->subMonths(1)->startOfMonth()->startOfDay()->toDateTimeString())->sum('total_price'); // 上月销售额
        $data['order_wait'] = $this->getService('Order', true)->where('order_status', 1)->count(); // 等待支付
        $data['order_send'] = $this->getService('Order', true)->where('order_status', 2)->count(); // 等待发货
        $data['order_complete'] = $this->getService('Order', true)->where('order_status', 6)->count(); // 完成
        $data['order_refund'] = $this->getService('Order', true)->where('order_status', 5)->count(); // 售后

        $data['day_rate'] = 0.00; // 日比
        $data['week_rate'] = 0.00; // 周比
        $data['month_rate'] = 100.00; // 月比
        $week_diff = $data['week_price']-$data['last_week_price'];
        if ($week_diff != 0 && $data['last_week_price']!=0) {
            $data['week_rate'] = round($week_diff/$data['last_week_price']*100, 2);
        }
        $day_diff = $data['today_price']-$data['yesterday_price'];
        if ($day_diff != 0 && $data['yesterday_price']!=0) {
            $data['day_rate'] = round($day_diff/$data['yesterday_price']*100, 2);
        }
        $month_diff = $data['month_price']-$data['last_month_price'];
        if ($month_diff < 0 && $data['last_month_price']!=0) {
            $data['month_rate'] = round($data['month_price']/$data['last_month_price']*100, 2);
        }
        
        // 销售绘图
        // 如果有传时间有 以时间为准，如果未传时间则取当前一周
        $first_time = Carbon::now()->startOfWeek()->format('Y-m-d');
        $end_time = Carbon::now()->endOfWeek()->format('Y-m-d');
        if (!empty($created_at)) {
            $first_time = Carbon::parse($created_at[0])->format('Y-m-d');
            $end_time = Carbon::parse($created_at[1])->format('Y-m-d');
        }
        
        $format = ['Y-m-d','%Y-%m-%d','DAY'];
        $diffDay = Carbon::parse($first_time)->diffInDays(Carbon::parse($end_time))+1; // 获取两个时间一共多少天
        if ($is_type==1) {
            $format = ['Y-m','%Y-%m','MONTH'];
            if (empty($created_at)) {
                $first_time = Carbon::now()->startOfYear()->format('Y-m-d');
                $end_time = Carbon::now()->endOfYear()->format('Y-m-d');
            }
            $diffDay = Carbon::parse($first_time)->diffInMonths(Carbon::parse($end_time));
        }
        if ($is_type==2) {
            $format = ['Y','%Y','YEAR'];
            if (empty($created_at)) {
                $first_time = Carbon::now()->subYears(5)->startOfYear()->format('Y-m-d');
                $end_time = Carbon::now()->endOfYear()->format('Y-m-d');
            }
            $diffDay = Carbon::parse($first_time)->diffInYears(Carbon::parse($end_time));
        }

        $sql = "select tpl.time,ifNull(U.num,0) as num from (select @s :=@s + 1 AS _index,DATE_FORMAT(DATE_SUB('".$end_time."', INTERVAL @s ".$format[2]."),'".$format[1]."') AS time FROM information_schema.CHARACTER_SETS,(SELECT @s := 0) temp where @s<".$diffDay." ORDER BY time) as tpl";
        $sql .= " left join (select sum(total_price) as num,DATE_FORMAT(created_at,'".$format[1]."') as time from orders where created_at between ? and ? and order_status>1 group by time) as U on U.time=tpl.time";
        $data['order_plot'] = DB::select($sql, [$first_time,$end_time]);


        // 用户绘图
        $sql = "select tpl.time,ifNull(U.num,0) as num from (select @s :=@s + 1 AS _index,DATE_FORMAT(DATE_SUB('".Carbon::now()->addDays(1)->format('Y-m-d')."', INTERVAL @s DAY),'%Y-%m-%d') AS time FROM information_schema.CHARACTER_SETS,(SELECT @s := 0) temp where @s<12 ORDER BY time) as tpl";
        $sql .= " left join (select count(*) as num,DATE_FORMAT(created_at,'%Y-%m-%d') as time from users where created_at between ? and ?  group by time) as U on U.time=tpl.time";
        $data['user_plot'] = DB::select($sql, [Carbon::now()->subDays(11)->format('Y-m-d'),Carbon::now()->addDays(1)->format('Y-m-d')]);

        // 获取店铺销售排行
        $data['list'] = $this->getService('Store', true)->select('store_name', 'id')->where('store_status',1)->where('store_verify',4)->withCount(['orders'=>function ($q) {
            $q->select(DB::raw('sum(total_price)'))->where('order_status', '>', 1);
        }])->orderBy('orders_count', 'desc')->take(6)->get();

        return $this->success($data);
    }

    // 用户数据分析
    public function user()
    {
        // 时间条件
        $created_at = request()->created_at;
        $is_type = request()->is_type;

        // 如果有传时间有 以时间为准，如果未传时间则取当前一周
        $first_time = Carbon::now()->startOfWeek()->format('Y-m-d');
        $end_time = Carbon::now()->endOfWeek()->format('Y-m-d');
        if (!empty($created_at)) {
            $first_time = $created_at[0];
            $end_time = $created_at[1];
        }
        
        $format = ['Y-m-d','%Y-%m-%d','DAY'];
        $diffDay = Carbon::parse($first_time)->diffInDays(Carbon::parse($end_time))+1; // 获取两个时间一共多少天
        if ($is_type==1) {
            $format = ['Y-m','%Y-%m','MONTH'];
            if (empty($created_at)) {
                $first_time = Carbon::now()->startOfYear()->format('Y-m-d');
                $end_time = Carbon::now()->endOfYear()->format('Y-m-d');
            }
            $diffDay = Carbon::parse($first_time)->diffInMonths(Carbon::parse($end_time));
        }
        if ($is_type==2) {
            $format = ['Y','%Y','YEAR'];
            if (empty($created_at)) {
                $first_time = Carbon::now()->subYears(5)->startOfYear()->format('Y-m-d');
                $end_time = Carbon::now()->endOfYear()->format('Y-m-d');
            }
            $diffDay = Carbon::parse($first_time)->diffInYears(Carbon::parse($end_time));
        }

        // dd($end_time,$first_time);
        $sql = "select tpl.time,ifNull(U.num,0) as num from (select @s :=@s + 1 AS _index,DATE_FORMAT(DATE_SUB('".$end_time."', INTERVAL @s ".$format[2]."),'".$format[1]."') AS time FROM information_schema.CHARACTER_SETS,(SELECT @s := 0) temp where @s<".$diffDay." ORDER BY time) as tpl";
        $sql .= " left join (select count(*) as num,DATE_FORMAT(created_at,'".$format[1]."') as time from users where created_at between ? and ? group by time) as U on U.time=tpl.time";
        // dd($sql);
        $data['plot'] = DB::select($sql, [$first_time,$end_time]);
        
        $nickname = explode('|',request('nickname',''))[0];
        $userListModel = $this->getService('User', true)->whereBetween('created_at', [$first_time,$end_time])->orderBy('id', 'desc');
        if(!empty($nickname)) $userListModel = $userListModel->where('nickname','like',$nickname.'%');
        $userListModel = $userListModel->paginate(request('per_page',30));
        $data['list'] = new UserCollection($userListModel);

        return $this->success($data);
    }

    // 店铺数据分析
    public function stores()
    {
        // 时间条件
        $created_at = request()->created_at;
        $is_type = request()->is_type;

        // 如果有传时间有 以时间为准，如果未传时间则取当前一周
        $first_time = Carbon::now()->startOfWeek()->format('Y-m-d');
        $end_time = Carbon::now()->endOfWeek()->format('Y-m-d');
        if (!empty($created_at)) {
            $first_time = $created_at[0];
            $end_time = $created_at[1];
        }
        
        $format = ['Y-m-d','%Y-%m-%d','DAY'];
        $diffDay = Carbon::parse($first_time)->diffInDays(Carbon::parse($end_time))+1; // 获取两个时间一共多少天
        if ($is_type==1) {
            $format = ['Y-m','%Y-%m','MONTH'];
            if (empty($created_at)) {
                $first_time = Carbon::now()->startOfYear()->format('Y-m-d');
                $end_time = Carbon::now()->endOfYear()->format('Y-m-d');
            }
            $diffDay = Carbon::parse($first_time)->diffInMonths(Carbon::parse($end_time));
        }
        if ($is_type==2) {
            $format = ['Y','%Y','YEAR'];
            if (empty($created_at)) {
                $first_time = Carbon::now()->subYears(5)->startOfYear()->format('Y-m-d');
                $end_time = Carbon::now()->endOfYear()->format('Y-m-d');
            }
            $diffDay = Carbon::parse($first_time)->diffInYears(Carbon::parse($end_time));
        }

        // dd($end_time,$first_time);
        $sql = "select tpl.time,ifNull(U.num,0) as num from (select @s :=@s + 1 AS _index,DATE_FORMAT(DATE_SUB('".$end_time."', INTERVAL @s ".$format[2]."),'".$format[1]."') AS time FROM information_schema.CHARACTER_SETS,(SELECT @s := 0) temp where @s<".$diffDay." ORDER BY time) as tpl";
        $sql .= " left join (select count(*) as num,DATE_FORMAT(created_at,'".$format[1]."') as time from stores where created_at between ? and ? group by time) as U on U.time=tpl.time";
        // dd($sql);
        $data['plot'] = DB::select($sql, [$first_time,$end_time]);

        $storeName = explode('|',request('store_name',''))[0];
        $storeListModel = $this->getService('Store', true)->whereBetween('created_at', [$first_time,$end_time])->orderBy('id', 'desc');
        if(!empty($nickname)) $storeListModel = $storeListModel->where('store_name','like',$storeName.'%');
        $storeListModel = $storeListModel->paginate(request('per_page',30));
        $data['list'] = $storeListModel;

        return $this->success($data);
    }

    // 订单数据分析
    public function order()
    {
        // 时间条件
        $created_at = request()->created_at;
        $is_type = request()->is_type;

        // 如果有传时间有 以时间为准，如果未传时间则取当前一周
        $first_time = Carbon::now()->startOfWeek()->format('Y-m-d');
        $end_time = Carbon::now()->endOfWeek()->format('Y-m-d');
        if (!empty($created_at)) {
            $first_time = $created_at[0];
            $end_time = $created_at[1];
        }
        
        $format = ['Y-m-d','%Y-%m-%d','DAY'];
        $diffDay = Carbon::parse($first_time)->diffInDays(Carbon::parse($end_time))+1; // 获取两个时间一共多少天
        if ($is_type==1) {
            $format = ['Y-m','%Y-%m','MONTH'];
            if (empty($created_at)) {
                $first_time = Carbon::now()->startOfYear()->format('Y-m-d');
                $end_time = Carbon::now()->endOfYear()->format('Y-m-d');
            }
            $diffDay = Carbon::parse($first_time)->diffInMonths(Carbon::parse($end_time));
        }
        if ($is_type==2) {
            $format = ['Y','%Y','YEAR'];
            if (empty($created_at)) {
                $first_time = Carbon::now()->subYears(5)->startOfYear()->format('Y-m-d');
                $end_time = Carbon::now()->endOfYear()->format('Y-m-d');
            }
            $diffDay = Carbon::parse($first_time)->diffInYears(Carbon::parse($end_time));
        }

        // dd($end_time,$first_time);
        $sql = "select tpl.time,ifNull(U.num,0) as num from (select @s :=@s + 1 AS _index,DATE_FORMAT(DATE_SUB('".$end_time."', INTERVAL @s ".$format[2]."),'".$format[1]."') AS time FROM information_schema.CHARACTER_SETS,(SELECT @s := 0) temp where @s<".$diffDay." ORDER BY time) as tpl";
        $sql .= " left join (select sum(total_price) as num,DATE_FORMAT(created_at,'".$format[1]."') as time from orders where created_at between ? and ? and order_status>1 group by time) as U on U.time=tpl.time";
        // dd($sql);
        $data['plot'] = DB::select($sql, [$first_time,$end_time]);
        // $data['list'] = new OrderCollection($this->getService('Order',true)->whereBetween('created_at',[$first_time,$end_time])->where('order_status','>',1)->orderBy('id','desc')->paginate(request()->per_page??30));

        // 获取店铺销售排行
        $data['store'] = $this->getService('Store', true)->select('store_name', 'id')->withCount(['orders'=>function ($q) {
            $q->select(DB::raw('sum(total_price)'));
        }])->orderBy('orders_count', 'desc')->take(10)->get();

        // 获取商品销售排行
        $data['goods'] = $this->getService('Goods', true)->select('goods_name', 'id')->withCount(['order_goods as orders_count'=>function ($q) {
            $q->select(DB::raw('sum(total_price)'));
        }])->orderBy('orders_count', 'desc')->take(10)->get();

        return $this->success($data);
    }

    // 支付数据分析
    public function pay()
    {
        // 时间条件
        $created_at = request()->created_at;
        $is_type = request()->is_type;

        // 如果有传时间有 以时间为准，如果未传时间则取当前一周
        $first_time = Carbon::now()->startOfWeek()->format('Y-m-d');
        $end_time = Carbon::now()->endOfWeek()->format('Y-m-d');
        if (!empty($created_at)) {
            $first_time = $created_at[0];
            $end_time = $created_at[1];
        }
        
        $format = ['Y-m-d','%Y-%m-%d','DAY'];
        $diffDay = Carbon::parse($first_time)->diffInDays(Carbon::parse($end_time))+1; // 获取两个时间一共多少天
        if ($is_type==1) {
            $format = ['Y-m','%Y-%m','MONTH'];
            if (empty($created_at)) {
                $first_time = Carbon::now()->startOfYear()->format('Y-m-d');
                $end_time = Carbon::now()->endOfYear()->format('Y-m-d');
            }
            $diffDay = Carbon::parse($first_time)->diffInMonths(Carbon::parse($end_time));
        }
        if ($is_type==2) {
            $format = ['Y','%Y','YEAR'];
            if (empty($created_at)) {
                $first_time = Carbon::now()->subYears(5)->startOfYear()->format('Y-m-d');
                $end_time = Carbon::now()->endOfYear()->format('Y-m-d');
            }
            $diffDay = Carbon::parse($first_time)->diffInYears(Carbon::parse($end_time));
        }

        // dd($end_time,$first_time);
        $sql = "select tpl.time,ifNull(U.num,0) as num from (select @s :=@s + 1 AS _index,DATE_FORMAT(DATE_SUB('".$end_time."', INTERVAL @s ".$format[2]."),'".$format[1]."') AS time FROM information_schema.CHARACTER_SETS,(SELECT @s := 0) temp where @s<".$diffDay." ORDER BY time) as tpl";
        $sql .= " left join (select sum(total) as num,DATE_FORMAT(created_at,'".$format[1]."') as time from order_pays where created_at between ? and ?  group by time) as U on U.time=tpl.time";
        // dd($sql);
        $data['plot'] = DB::select($sql, [$first_time,$end_time]);

        $payListModel = $this->getService('OrderPay', true)->whereBetween('created_at', [$first_time,$end_time])->orderBy('id', 'desc');
        if(!empty(request('pay_no',''))) $payListModel = $payListModel->where('pay_no','like',explode('|',request('pay_no'))[0].'%');
        if(!empty(request('trade_no',''))) $payListModel = $payListModel->where('trade_no',explode('|',request('trade_no'))[0].'%');
        if(!empty(request('payment_name',''))) $payListModel = $payListModel->where('payment_name',explode('|',request('payment_name'))[0]);
        if(!empty(request('pay_status',0))) $payListModel = $payListModel->where('pay_status',explode('|',request('pay_status'))[0]);
        $payListModel = $payListModel->paginate(request('per_page',30));
        $data['list'] = $payListModel;

        return $this->success($data);
    }
}
