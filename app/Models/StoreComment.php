<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Goods;
use App\Models\Store;

class StoreComment extends Model
{
    protected $table = "store_comment"; //指定表
    protected $primaryKey = "id"; //指定id字段
    public $timestamps = false;

    // 根据商品ID获取列表
    public function getCommentListByGoodsId($goods_id,$pages=20){
        return $this->where('goods_id',$goods_id)->where('is_type',0)->paginate($pages);
    }

    // 根据评论ID获取详情
    public function getCommentById($comment_id){
        $goods_model = new Goods();
        $data['comment_info'] = $this->where('id',$comment_id)->first();
        $data['goods_info'] = $goods_model->getGoodsInfoById($data['comment_info']['goods_id']);
        return $data;
    }

    // 添加评论
    public function addComment($data){
        $store_model = new Store();
        $score = $this->sumStoreComment($data['store_id']);
        $store_model->where('id',$data['store_id'])->update($score);

        // 计算平均分让后放入商家表
        return $this->insert($data);
    }

    // 计算评分 商品
    public function sumGoodsComment($id){
        $data['score'] = 0;
        $data['agree'] = 0;
        $data['service'] = 0;
        $data['speed'] = 0;
        $count = $this->where('goods_id',$id)->count();

        $agree = $this->where('goods_id',$id)->sum('agree');
        $service = $this->where('goods_id',$id)->sum('service');
        $speed = $this->where('goods_id',$id)->sum('speed');

        $data['agree'] = $agree==0?5.0:round($agree/$count,1);
        $data['service'] = $service==0?5.0:round($service/$count,1);
        $data['speed'] = $speed==0?5.0:round($speed/$count,1);
        $data['score'] = round(($data['agree']+$data['service']+$data['speed'])/3,1);
        return $data;
    }

    // 计算评分 店铺
    public function sumStoreComment($id){
        $data['store_score'] = 0;
        $data['store_agree'] = 0;
        $data['store_service'] = 0;
        $data['store_speed'] = 0;
        $count = $this->where('store_id',$id)->count();
        $agree = $this->where('store_id',$id)->sum('agree');
        $service = $this->where('store_id',$id)->sum('service');
        $speed = $this->where('store_id',$id)->sum('speed');
        $data['store_agree'] = $agree==0?5.0:round($agree/$count,1);
        $data['store_service'] = $service==0?5.0:round($service/$count,1);
        $data['store_speed'] = $speed==0?5.0:round($speed/$count,1);
        $data['store_score'] = round(($data['store_agree']+$data['store_service']+$data['store_speed'])/3,1);
        return $data;
    }
}
