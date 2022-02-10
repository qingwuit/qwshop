<?php
namespace App\Qingwuit\Services;

use App\Http\Resources\OrderCommentHomeCollection;
use Illuminate\Support\Facades\DB;

class OrderCommentService extends BaseService
{

    // 评论统计
    public function commentStatistics($goodsId)
    {
        $all = $this->getService('OrderComment', true)->where('goods_id', $goodsId)->count();
        $good = $this->getService('OrderComment', true)->whereRaw('(score+agree+speed+service)>=15')->where('goods_id', $goodsId)->count();
        $commonly = $this->getService('OrderComment', true)->whereRaw('(score+agree+speed+service)<15')->whereRaw('(score+agree+speed+service)>10')->where('goods_id', $goodsId)->count();
        $bad = $this->getService('OrderComment', true)->whereRaw('(score+agree+speed+service)<=10')->where('goods_id', $goodsId)->count();
        $data = [
            'all'=>$all,
            'good'=>$good??0,
            'commonly'=>$commonly??0,
            'bad'=>$bad??0,
            'rate'=>$all==0?100:round((($good??0)/$all)*100, 2),
        ];
        foreach ($data as &$v) {
            $v = $v>999?'999+':$v;
        }
        return $this->format($data);
    }

    // 获取评论列表
    public function commentList($id, $whereName="goods_id")
    {
        $model = $this->getService('OrderComment', true)->select(DB::raw("*,(score+agree+speed+service) as sum_rate"))->with('user')->where($whereName, $id)->orderBy('created_at', 'desc');
        if (request('is_type')==1) {
            $model = $model->having('sum_rate', '>=', 15);
        }
        if (request('is_type')==2) {
            $model = $model->having('sum_rate', '<', 15)->having('sum_rate', '>', 10);
        }
        if (request('is_type')==3) {
            $model = $model->having('sum_rate', '<=', 10);
        }
        $list = $model->paginate(request()->per_page??30);
        return $this->format(new OrderCommentHomeCollection($list));
    }

    // 定时任务系统添加评论
    public function systemAdd($ids=[])
    {
        $order_list = $this->getService('Order', true)->with('order_goods')->whereIn('id', $ids)->get();
        if ($order_list->isEmpty()) {
            return $this->formatError('order_list is empty obj systemAdd.');
        }
        $data = [];
        $score = 5.00;
        $agree = 5.00;
        $service = 5.00;
        $speed = 5.00;
        $content = '非常好！';
        $image = [];
        foreach ($order_list as $v) {
            foreach ($v['order_goods'] as $vo) {
                $comment = [];
                $comment['user_id'] = $v['user_id'];
                $comment['goods_id'] = $vo['goods_id'];
                $comment['order_id'] = $v['id'];
                $comment['store_id'] = $v['store_id'];
                $comment['score'] = $score;
                $comment['agree'] = $agree;
                $comment['service'] = $service;
                $comment['service'] = $speed;
                $comment['content'] = $content;
                $comment['image'] = empty($image)?'':implode(',', $image);
                $comment['created_at'] = now();
                $data[] = $comment;
            }
        }
        $rs = $this->getService('OrderComment', true)->insert($data);
        $this->getService('Order', true)->whereIn('id', $ids)->update(['order_status'=>6,'comment_time'=>now()]);
        return $this->format($rs);
    }
}
