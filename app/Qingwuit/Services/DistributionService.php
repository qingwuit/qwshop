<?php
namespace App\Qingwuit\Services;

use App\Http\Resources\DistributionLogCollection;
use Illuminate\Support\Facades\DB;

class DistributionService extends BaseService
{
    // 获取商家分销列表
    public function getList()
    {
        $dis_model = $this->getService('Distribution', true);
        $store_id = $this->getService('Store')->getStoreId()['data'];
        $list = $dis_model->with(['goods'=>function ($q) {
            $q->select('id', 'goods_name', 'goods_master_image');
        }])->where('store_id', $store_id)->withCount(['distribution_logs as total_money'=>function ($q) {
            $q->select(DB::raw('SUM(money) as total_money'));
        }])->withCount(['distribution_logs as total_commission'=>function ($q) {
            $q->select(DB::raw('SUM(commission) as total_commission'));
        }])->orderBy('id', 'desc')->paginate(request()->per_page??30);
        // return $this->format(new DistributionCollection($list));
    }

    // 获取分销日志
    public function getLogList($auth="user")
    {
        $dislog_model = $this->getService('DistributionLog', true);
        if ($auth=='user') {
            $userId = $this->getUser('users');
            $dislog_model = $dislog_model->where('user_id', $userId);
        }
        if ($auth=='seller') {
            $store_id = $this->getService('Store')->getStoreId()['data'];
            $dislog_model = $dislog_model->where('store_id', $store_id);
        }
        $list = $dislog_model->with(['user:id,nickname','store:id,store_name','order:id,order_no'])->orderBy('id', 'desc')->paginate(request()->per_page??30);
        return $this->format(new DistributionLogCollection($list));
    }

    // 获取分销商品列表
    public function getDistributionGoods()
    {
        $goods_model = $this->getService('Goods', true);
        $store_id = $this->getService('Store')->getStoreId()['data'];

        if (isset(request()->goods_name) && !empty(request()->goods_name)) {
            $goods_model = $goods_model->where('goods_name', 'like', '%'.request()->goods_name.'%');
        }

        $list = $goods_model->with(['distribution'=>function ($q) {
            $q->select('goods_id');
        }])->where('store_id', $store_id)->paginate(request()->per_page??30);

        // return $this->format(new DistributionGoodsCollection($list));
    }

    // 订单支付成功生成分销日志
    public function addDisLog($oid_array)
    {
        $og_model = $this->getService('OrderGoods', true);
        $dislog_model = $this->getService('DistributionLog', true);
        $list = $og_model->has('distribution')->whereHas('user', function ($q) {
            $q->select('id', 'inviter_id')->where('inviter_id', '>', 0);
        })->whereIn('order_id', $oid_array)->get();

        if ($list->isEmpty()) {
            return $this->formatError();
        }

        $data = [];
        foreach ($list as $v) {
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
            $u = $this->getService('User', true)->select('inviter_id')->where('id', $v->user->inviter_id)->where('inviter_id', '>', 0)->first();
            if (!empty($u)) {
                $lev = 1; // 有二级
                $su = $this->getService('User', true)->select('inviter_id')->where('id', $u->inviter_id)->where('inviter_id', '>', 0)->first();
                if (!empty($su)) {
                    $lev = 2; // 有三级
                }
            }

            if ($lev == 0) {
                $item2 = $item;
                $item2['name'] = '一级分销';
                $item2['user_id'] = $v->user->inviter_id;
                $item2['commission'] = round($v->distribution->lev_1*$v->total_price, 2);
                $item2['lev'] = $v->distribution->lev_1;
                $data[] = $item2;
            }

            if ($lev == 1) {
                $item2 = $item;
                $item2['name'] = '二级分销';
                $item2['user_id'] = $u->inviter_id;
                $item2['commission'] = round($v->distribution->lev_2*$v->total_price, 2);
                $item2['lev'] = $v->distribution->lev_2;
                $data[] = $item2;

                $item2 = $item;
                $item2['name'] = '一级分销';
                $item2['user_id'] = $v->user->inviter_id;
                $item2['commission'] = round($v->distribution->lev_1*$v->total_price, 2);
                $item2['lev'] = $v->distribution->lev_1;
                $data[] = $item2;
            }

            if ($lev == 2) {
                $item2 = $item;
                $item2['name'] = '三级分销';
                $item2['user_id'] = $su->inviter_id;
                $item2['commission'] = round($v->distribution->lev_3*$v->total_price, 2);
                $item2['lev'] = $v->distribution->lev_3;
                $data[] = $item2;

                $item2 = $item;
                $item2['name'] = '二级分销';
                $item2['user_id'] = $u->inviter_id;
                $item2['commission'] = round($v->distribution->lev_2*$v->total_price, 2);
                $item2['lev'] = $v->distribution->lev_2;
                $data[] = $item2;

                $item2 = $item;
                $item2['name'] = '一级分销';
                $item2['user_id'] = $v->user->inviter_id;
                $item2['commission'] = round($v->distribution->lev_1*$v->total_price, 2);
                $item2['lev'] = $v->distribution->lev_1;
                $data[] = $item2;
            }
        }

        $dislog_model->insert($data);
        return $this->format([]);
    }

    //获取该用户的分销会员
    public function getHomeUser()
    {
        $userId = $this->getUserId('users');
        $user_model = $this->getService('User', true);
        if (request()->lev==0) {
            $user_model = $user_model->where('inviter_id', $userId);
        }
        if (request()->lev==1) {
            $f = $this->getService('User', true)->select('id', 'inviter_id')->where('inviter_id', $userId)->get();
            if ($f->isEmpty()) {
                $user_model = $user_model->where('id', 0);
            } else {
                $ids = [];
                foreach ($f as $v) {
                    $ids[] = $v->id;
                }
                $user_model = $user_model->whereIn('inviter_id', $ids);
            }
        }
        if (request()->lev==2) {
            $f = $this->getService('User', true)->select('id')->where('inviter_id', $userId)->get();
            if ($f->isEmpty()) {
                $user_model = $user_model->where('id', 0);
            } else {
                $ids = [];
                foreach ($f as $v) {
                    $ids[] = $v->id;
                }
                $s = $this->getService('User', true)->select('id')->whereIn('inviter_id', $ids)->get();
                if ($s->isEmpty()) {
                    $user_model = $user_model->where('id', 0);
                } else {
                    $ids2 = [];
                    foreach ($s as $v) {
                        $ids2[] = $v->id;
                    }
                    $user_model = $user_model->whereIn('inviter_id', $ids2);
                }
            }
        }
        $list = $user_model->paginate(request()->per_page??30);
        return $this->format($list);
    }

    // 结算处理分销金额到用户
    public function handleSettlement($order)
    {
        $dislog_model = $this->getService('DistributionLog', true);
        $list = $dislog_model->select('user_id', 'commission')->whereIn('order_id', $order)->get();
        if ($list->isEmpty()) {
            return $this->formatError();
        }
        $ml_service = new MoneyLogService;
        foreach ($list as $v) {
            $ml_service->edit([
                'name'=>'会员分销',
                'money'=>$v->commission,
                'user_id'=>$v->user_id,
            ]);
        }
        $dislog_model->whereIn('order_id', $order)->update(['status'=>1,'updated_at'=>now()]);
        return $this->format([]);
    }
}
