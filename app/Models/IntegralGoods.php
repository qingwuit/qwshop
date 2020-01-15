<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IntegralGoods extends Model
{
    protected $table = "integral_goods"; //指定表
    protected $primaryKey = "id"; //指定id字段
    public $timestamps = false;

    // 搜索产品
    public function searchIntegralGoods($where=[],$order=[],$keywords="",$page=30){

        $model = $this;

        // 普通条件
        if(!empty($where)){
            $model = $model->where($where);
        }

        // 排序条件
        if(!empty($order)){
            if(!empty($order)){
                foreach($order['list'] as $v){
                    $model = $model->orderBy($v,$order['order_type']);
                }
            }
        }

        // 关键词条件
        if(!empty($keywords)){
            $model = $model->where('goods_name','like','%'.$keywords.'%');
        }

        $goods_res = $model->paginate($page)->toArray();
        

        // 是否有规格，有规格则取规格价格
        foreach($goods_res['data'] as $k=>$v){
           
            $v['image'] = get_format_image($v['goods_master_image'],200);
            // $v['comment_count'] = $store_comment_model->where('goods_id',$v['id'])->count();
            $goods_res['data'][$k] = $v;
        }
        $goods_res['data'] = get_format_image_goods_list($goods_res['data']);
        return $goods_res;
        
    }
}
