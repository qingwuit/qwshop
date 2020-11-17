<?php
namespace App\Services;

use App\Http\Resources\Home\DistributionResource\DistributionUserCollection;
use App\Http\Resources\Seller\DistributionResource\DistributionCollection;
use App\Http\Resources\Seller\DistributionResource\DistributionGoodsCollection;
use App\Http\Resources\Seller\DistributionResource\DistributionLogCollection;
use App\Models\Distribution;
use App\Models\DistributionLog;
use App\Models\Goods;
use App\Models\OrderGoods;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DistributionService extends BaseService{
    
    // 获取商家分销列表
    public function getList(){
        $dis_model = new Distribution();
        $store_id = $this->get_store(true);
        $list = $dis_model->with(['goods'=>function($q){
            $q->select('id','goods_name','goods_master_image');
        }])->where('store_id',$store_id)->withCount(['distribution_logs as total_money'=>function($q){
            $q->select(DB::raw('SUM(money) as total_money'));
        }])->withCount(['distribution_logs as total_commission'=>function($q){
            $q->select(DB::raw('SUM(commission) as total_commission'));
        }])->orderBy('id','desc')->paginate(request()->per_page??30);
        return $this->format(new DistributionCollection($list));
    }

    // 获取分销日志
    public function getLogList($auth="user"){
        $dislog_model = new DistributionLog();
        if($auth=='user'){
            $user_service = new UserService;
            $user_info = $user_service->getUserInfo();
            $dislog_model = $dislog_model->where('user_id',$user_info['id']);
        }
        if($auth=='seller'){
            $dislog_model = $dislog_model->where('store_id',$this->get_store(true));
        }
        $list = $dislog_model->with(['user:id,nickname','store:id,store_name','order:id,order_no'])->orderBy('id','desc')->paginate(request()->per_page??30);
        return $this->format(new DistributionLogCollection($list));
    }

    // 获取分销商品列表
    public function getDistributionGoods(){
        $goods_model = new Goods();
        $store_id = $this->get_store(true);

        if(isset(request()->goods_name) && !empty(request()->goods_name)){
            $goods_model = $goods_model->where('goods_name','like','%'.request()->goods_name.'%');
        }

        $list = $goods_model->with(['distribution'=>function($q){
            $q->select('goods_id');
        }])->where('store_id',$store_id)->paginate(request()->per_page??30);

        return $this->format(new DistributionGoodsCollection($list));
    }

    // 订单支付成功生成分销日志
    public function addDisLog($oid_array){
        $og_model = new OrderGoods();
        $dislog_model = new DistributionLog();
        $list = $og_model->has('distribution')->whereHas('user',function($q){
            $q->select('id','inviter_id')->where('inviter_id','>',0);
        })->whereIn('order_id',$oid_array)->get();

        if(empty($list)){
            return $this->format_error();
        }

        $data = [];
        foreach($list as $v){
            $item = [];
            
            $item['store_id'] = $v->store_id;
            $item['order_id'] = $v->order_id;
            $item['distribution_id'] = $v->distribution->id;
            $item['order_goods_id'] = $v->id;
            $item['money'] = $v->total_price;
            $item['user_id'] = 0;
            $item['commission'] = 0;
            $item['lev'] = 0;
            $item['created_at'] = now();
            $item['updated_at'] = now();
            
            $lev = 0; // 默认为有邀请人
            $u = User::select('inviter_id')->where('id',$v->user->inviter_id)->where('inviter_id','>',0)->first();
            if(!empty($u)){
                $lev = 1; // 有二级
                $su = User::select('inviter_id')->where('id',$u->inviter_id)->where('inviter_id','>',0)->first();
                if(!empty($su)){
                    $lev = 2; // 有三级
                }
            }

            if($lev == 0){
                $item2 = $item;
                $item2['name'] = '一级分销';
                $item2['user_id'] = $v->user->inviter_id;
                $item2['commission'] = round($v->distribution->lev_1*$v->total_price,2);
                $item2['lev'] = $v->distribution->lev_1;
                $data[] = $item2;
            }

            if($lev == 1){
                $item2 = $item;
                $item2['name'] = '二级分销';
                $item2['user_id'] = $u->inviter_id;
                $item2['commission'] = round($v->distribution->lev_2*$v->total_price,2);
                $item2['lev'] = $v->distribution->lev_2;
                $data[] = $item2;

                $item2 = $item;
                $item2['name'] = '一级分销';
                $item2['user_id'] = $v->user->inviter_id;
                $item2['commission'] = round($v->distribution->lev_1*$v->total_price,2);
                $item2['lev'] = $v->distribution->lev_1;
                $data[] = $item2;
            }

            if($lev == 2){
                $item2 = $item;
                $item2['name'] = '三级分销';
                $item2['user_id'] = $su->inviter_id;
                $item2['commission'] = round($v->distribution->lev_3*$v->total_price,2);
                $item2['lev'] = $v->distribution->lev_3;
                $data[] = $item2;

                $item2 = $item;
                $item2['name'] = '二级分销';
                $item2['user_id'] = $u->inviter_id;
                $item2['commission'] = round($v->distribution->lev_2*$v->total_price,2);
                $item2['lev'] = $v->distribution->lev_2;
                $data[] = $item2;

                $item2 = $item;
                $item2['name'] = '一级分销';
                $item2['user_id'] = $v->user->inviter_id;
                $item2['commission'] = round($v->distribution->lev_1*$v->total_price,2);
                $item2['lev'] = $v->distribution->lev_1;
                $data[] = $item2;
            }
        }

        $dislog_model->insert($data);
        return $this->format([]);

    }

    //获取该用户的分销会员
    public function getUser(){
        $user_service = new UserService;
        if(!$user_info = $user_service->getUserInfo()){
            return $this->format_error(); 
        }
        $user_model = new User;
        if(request()->lev==0){
            $user_model = $user_model->where('inviter_id',$user_info['id']);
        }
        if(request()->lev==1){
            $f = User::select('id','inviter_id')->where('inviter_id',$user_info['id'])->get();
            if($f->isEmpty()){
                $user_model = $user_model->where('id',0);
            }else{
                $ids = [];
                foreach($f as $v){
                    $ids[] = $v->id;
                }
                $user_model = $user_model->whereIn('inviter_id',$ids);
            }
        }
        if(request()->lev==2){
            $f = User::select('id')->where('inviter_id',$user_info['id'])->get();
            if($f->isEmpty()){
                $user_model = $user_model->where('id',0);
            }else{
                $ids = [];
                foreach($f as $v){
                    $ids[] = $v->id;
                }
                $s = User::select('id')->whereIn('inviter_id',$ids)->get();
                if($s->isEmpty()){
                    $user_model = $user_model->where('id',0);
                }else{
                    $ids2 = [];
                    foreach($s as $v){
                        $ids2[] = $v->id;
                    }
                    $user_model = $user_model->whereIn('inviter_id',$ids2);
                }
            }
        }
        $list = $user_model->paginate(request()->per_page??30);
        return $this->format(new DistributionUserCollection($list));
    }

    // 结算处理分销金额到用户
    public function handleSettlement($order){
        $dislog_model = new DistributionLog();
        $list = $dislog_model->select('user_id','commission')->whereIn('order_id',$order)->get();
        if($list->isEmpty()){
            return $this->format_error();
        }
        $ml_service = new MoneyLogService;
        foreach($list as $v){
            $ml_service->editMoney(__('users.money_log_distribution'),$v->user_id,$v->commission);
        }
        $dislog_model->whereIn('order_id',$order)->update(['status'=>1,'updated_at'=>now()]);
        return $this->format([]);
    }
}
