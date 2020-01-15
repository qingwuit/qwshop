<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Models\AdvPosition;
use App\Models\IntegralClass;
use App\Models\IntegralGoods;

class IntegralController extends NotokenController
{
    // 获取积分商城首页信息
    public function get_index_info(AdvPosition $adv_position_model,IntegralGoods $integral_goods_model,IntegralClass $integral_class_model){
        $data['banner'] = $adv_position_model->get_adv_list('PC_积分商城首页'); // 获取幻灯片
        $data['hot_goods'] = $integral_goods_model->where(['goods_status'=>1,'is_hot'=>1])->take(4)->get()->toArray();
        $data['hot_goods'] = get_format_image_goods_list($data['hot_goods'],200);

        $class_list = $integral_class_model->get()->toArray();
        foreach($class_list as $v){
            $v['goods_list'] = $integral_goods_model->where(['cid'=>$v['id'],'goods_status'=>1])->get()->toArray();
            foreach($v['goods_list'] as $key=>$vo){
                $vo['goods_master_image'] = get_format_image($vo['goods_images'],200); // 获取缩略图
                $v['goods_list'][$key] = $vo;
            }
        }
        $data['class_list'] = $class_list;
        return $this->success_msg('ok',$data);
    }

    // 获取积分商城商品详情
    public function get_integral_goods_info(IntegralGoods $integral_goods_model,IntegralClass $integral_class_model,$id){
        $info = $integral_goods_model->where('id',$id)->first();

        $info['class_list'] = $integral_class_model->where('id',$info['cid'])->get();
        // 商品图片显示
        $info['goods_images'] = explode(',',$info['goods_images']);
        $info['image'] = get_format_image($info['goods_master_image'],200);
        $goods_images = array_diff($info['goods_images'],[$info['goods_master_image']]); // 用差集取出非相同的图片
        $info['goods_images'] = array_merge([$info['goods_master_image']],$goods_images);
        // $goods_info['goods_images'][0] = $goods_info['goods_master_image'];
        $info['goods_images_thumb'] = get_format_image($info['goods_images']); // 获取缩略图
        $info['goods_images_thumb_200'] = get_format_image($info['goods_images'],200); // 获取缩略图
        return $this->success_msg('ok',$info);
    }

    // 获取搜索商品
    public function search_integral_goods(Request $req,IntegralGoods $integral_goods_model){
        $where = [];
        $keywords = '';
        
        // 分类判断
        if(!empty($req->goods_class_id) && isset($req->goods_class_id)){
            $where['cid'] = $req->goods_class_id;
        }

        // 条件赛选判断  0 默认 1 价格 2 销量 
        if(isset($req->goods_type) && isset($req->order_type)){
            switch($req->goods_type){
                case 0:
                    $order['list'] = ['goods_sale_num','goods_price'];
                    break;
                case 1:
                    $order['list'] = ['goods_price'];
                    break;
                case 2:
                    $order['list'] = ['goods_sale_num'];
                    break;
               
            }
            $order['order_type'] = empty($req->order_type)?'desc':'asc';
        }

        // 关键字
        if(!empty($req->keywords) && isset($req->keywords)){
            $keywords = $req->keywords;
        }
        

        $data = $integral_goods_model->searchIntegralGoods($where,$order,$keywords);

        return $this->success_msg('ok',$data);
    }

    // 获取积分商品分类
    public function get_integral_goods_class(IntegralClass $integral_class_model){
        return $this->success_msg('ok',$integral_class_model->get());
    }
}
