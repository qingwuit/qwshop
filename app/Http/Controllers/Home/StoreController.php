<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Goods;
use App\Models\Store;
use App\Models\StoreGoodsClass;

class StoreController extends NotokenController
{
    // 获取商家信息
    public function get_store_info(Request $req,Store $store_model){
        $user_id = $req->user_id;
        $store_info = $store_model->where('user_id',$user_id)->first();
        return $this->success_msg('ok',$store_info);
    }

    // 获取商家首页信息
    public function get_store_index_info(Request $req,StoreGoodsClass $store_goods_class_model,Goods $goods_model){
        $user_id = $req->user_id;
        $data['top_goods_list'] = $goods_model->getGoodsListByIdOrIsTop($user_id);
        $store_goods_class_arr = $store_goods_class_model->where('user_id',$user_id)->get()->toArray();
        $store_goods_class_ids = [];
        foreach($store_goods_class_arr as $v){
            $store_goods_class_ids[] = $v['id'];
        }
        $data['store_goods_class_arr'] = $store_goods_class_arr;
        $data['store_goods_class_list'] = $store_goods_class_model->getGoodsListByStoreGoodsClass($store_goods_class_ids);
        return $this->success_msg('ok',$data);
    }

    // 获取商家自定义栏目
    public function get_store_class(Request $req,StoreGoodsClass $store_goods_class_model){
        $user_id = $req->user_id;
        $data = $store_goods_class_model->where('user_id',$user_id)->get()->toArray();
        return $this->success_msg('ok',$data);
    }

    // 获取商家自定义栏目商品
    public function get_store_class_goods(Request $req,StoreGoodsClass $store_goods_class_model,Goods $goods_model){
        $user_id = $req->user_id;
        $class_id = $req->class_id;
        $class_info = $store_goods_class_model->where('user_id',$user_id)->where('id',$class_id)->first();
        if(empty($class_info)){
            $class_info = ['name'=>'全部商品','id'=>0];
        }
        $data['store_goods_class_list'] = $store_goods_class_model->getGoodsListByStoreGoodsClass([$class_info['id']],24,true);
        $data['class_info'] = $class_info;
        return $this->success_msg('ok',$data);
        
    }
}
