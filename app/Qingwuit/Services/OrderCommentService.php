<?php
namespace App\Qingwuit\Services;

use App\Http\Resources\OrderCommentHomeCollection;
use Illuminate\Support\Facades\DB;

class OrderCommentService extends BaseService{

    // 评论统计
    public function commentStatistics($goodsId){
        $all = $this->getService('OrderComment',true)->where('goods_id',$goodsId)->count();
        $good = $this->getService('OrderComment',true)->whereRaw('(score+agree+speed+service)>=15')->where('goods_id',$goodsId)->count();
        $commonly = $this->getService('OrderComment',true)->whereRaw('(score+agree+speed+service)<15')->whereRaw('(score+agree+speed+service)>10')->where('goods_id',$goodsId)->count();
        $bad = $this->getService('OrderComment',true)->whereRaw('(score+agree+speed+service)<=10')->where('goods_id',$goodsId)->count();
        $data = [
            'all'=>$all,
            'good'=>$good??0,
            'commonly'=>$commonly??0,
            'bad'=>$bad??0,
            'rate'=>$all==0?100:round((($good??0)/$all)*100,2),
        ];
        foreach($data as &$v){
            $v = $v>999?'999+':$v;
        }
        return $this->format($data);
    }

    // 获取评论列表
    public function commentList($id,$whereName="goods_id"){
        $model = $this->getService('OrderComment',true)->select(DB::raw("*,(score+agree+speed+service) as sum_rate"))->with('user')->where($whereName,$id)->orderBy('created_at','desc');
        if(request('is_type')==1){
            $model = $model->having('sum_rate','>=',15);
        }
        if(request('is_type')==2){
            $model = $model->having('sum_rate','<',15)->having('sum_rate','>',10);
        }
        if(request('is_type')==3){
            $model = $model->having('sum_rate','<=',10);
        }
        $list = $model->paginate(request()->per_page??30);
        return $this->format(new OrderCommentHomeCollection($list));
    }
}