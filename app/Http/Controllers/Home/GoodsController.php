<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Goods;
use App\Models\GoodsClass;
use App\Models\Store;
use App\Models\GoodsBrand;
use App\Models\Groupbuy;
use App\Models\GroupbuyUser;
use App\Models\StoreComment;

class GoodsController extends NotokenController
{
    // 商品详情
    public function get_goods_info(Request $req,Goods $goods_model,GoodsClass $goods_class_model,Store $store_model,StoreComment $store_comment_model,Groupbuy $groupbuy_model,GroupbuyUser $groupbuy_user_model){
        $goods_id = intval($req->goods_id);
        $data = $goods_model->getGoodsInfoById($goods_id);
        $data['class_list'] = $goods_class_model->getBreadCrumbById($data['cid']);
        $data['store_info'] = $store_model->where(['user_id'=>$data['user_id']])->first();
        $data['comment_num'] = $store_comment_model->where('goods_id',$goods_id)->count();
        $data['groupbuy_use'] = 0; // 团购 参加人数
        if($data['is_groupbuy'] == 1){
            $groupbuy_info = $groupbuy_model->where('goods_id',$goods_id)->orderBy('id','desc')->first();
            if(!empty($groupbuy_info)){
                $data['groupbuy_use'] = $groupbuy_info['groupbuy_use'];
            }
        }

        return $this->success_msg('ok',$data);
    }

    // 商家详情的 销量列表 也就是商家的销量列表
    public function get_sale_list(Request $req,Goods $goods_model){
        $user_id = $req->user_id;
        $goods_list = $goods_model->where('user_id',$user_id)->where(['goods_status'=>1,'goods_verify'=>1])->orderBy('goods_sale_num','desc')->take(6)->with('spec_once')->get()->toArray();
        // 是否有规格，有规格则取规格价格
        foreach($goods_list as $k=>$v){
            if(!empty($v['spec_once'])){
                $v['goods_price'] = $v['spec_once']['goods_price'];
                $v['goods_market_price'] = $v['spec_once']['goods_market_price'];
            }
            $v['image'] = get_format_image($v['goods_master_image'],200);
            $goods_list[$k] = $v;
        }
        return $this->success_msg('ok',$goods_list);
    }

    // 获取搜索商品
    public function search_goods(Request $req,Goods $goods_model,GoodsClass $goods_class_model){
        $where = [];
        $keywords = '';
        
        // 品牌判断
        if(!empty($req->goods_brand) && isset($req->goods_brand)){
            $where['bid'] = $req->goods_brand;
        }

        // 团购产品
        if(!empty($req->is_groupbuy) && isset($req->is_groupbuy)){
            $where['is_groupbuy'] = 1;
        }

        $order = [];

        // 条件赛选判断  0 默认 1 价格 2 销量 3 评论
        if(isset($req->goods_type) && isset($req->order_type)){
            switch($req->goods_type){
                case 0:
                    $order['list'] = ['goods_sale_num','goods_collect'];
                    break;
                case 1:
                    $order['list'] = ['goods_price'];
                    break;
                case 2:
                    $order['list'] = ['goods_sale_num'];
                    break;
                case 3:
                    $order['list'] = ['comment_count'];
                    break;
            }
            $order['order_type'] = empty($req->order_type)?'desc':'asc';
        }

        // 关键字
        if(!empty($req->keywords) && isset($req->keywords)){
            $keywords = $req->keywords;
        }
        
        $class_id = 0;
        if(isset($req->class_info) && count($req->class_info)>0){
            $class_id = $req->class_info[count($req->class_info)-1];
        }

        $data = $goods_class_model->searchGoods($class_id,$where,$order,$keywords);

        return $this->success_msg('ok',$data);
    }

    // 获取产品列表 品牌列表
    public function get_brand_list(GoodsBrand $goods_brand_model){
        $brand_list = $goods_brand_model->get();
        return $this->success_msg('ok',$brand_list);
    }
}
