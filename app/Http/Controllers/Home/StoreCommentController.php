<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\StoreComment;
use App\Tools\Uploads;

class StoreCommentController extends BaseController
{
    public function get_comment_list(StoreComment $store_comment_model){
        $user_info = auth()->user();
        $list = $store_comment_model->where('user_id',$user_info['id'])->paginate(20);
        return $this->success_msg('ok',$list);
    }

    // 根据商品ID 获取评论列表
    public function get_comment_list_by_goods(Request $req,StoreComment $store_comment_model){
        $store_comment_model2 = $store_comment_model;
        $store_comment_model3 = $store_comment_model;
        $store_comment_model4 = $store_comment_model;
        $store_comment_model5 = $store_comment_model;
        $store_comment_model = $store_comment_model->where('goods_id',$req->goods_id);
        if($req->comment_type == 1){
            $store_comment_model = $store_comment_model->where('score','>=',5);
        }elseif($req->comment_type == 2){
            $store_comment_model = $store_comment_model->where('score','<',5)->where('score','>=',3);
        }elseif($req->comment_type == 3){
            $store_comment_model = $store_comment_model->where('score','<',3);
        }
        
        $data['all'] = $store_comment_model3->where('goods_id',$req->goods_id)->count();
        $data['good'] = $store_comment_model3->where('goods_id',$req->goods_id)->where('score','>=',5)->count();
        $data['secondary'] = $store_comment_model4->where('goods_id',$req->goods_id)->where('score','<',5)->where('score','>=',3)->count();
        $data['bad'] = $store_comment_model5->where('goods_id',$req->goods_id)->where('score','<',3)->count();

        $data['comment_list'] =  $store_comment_model->paginate(20)->toArray();
        $num = $store_comment_model2->where('goods_id',$req->goods_id)->sum('score');

        if($num == 0 || $data['comment_list']['total']==0){
            $data['bfb'] = 100;
            $data['star'] = 5;
        }else{
            $data['bfb'] = round($num/$data['comment_list']['total']/5,3)*100;
            $data['star'] = round($num/$data['comment_list']['total'],1);
        }
        
        foreach($data['comment_list']['data'] as $k=>$v){
            if(empty($v['comment_images'])){
                $v['comment_images'] = [];
            }else{
                $v['comment_images'] = explode(',',$v['comment_images']);
            }
            $data['comment_list']['data'][$k] = $v;
        }

        return $this->success_msg('ok',$data);
    }

    public function add_comment(Request $req,StoreComment $store_comment_model,Order $order_model){
        $user_info = auth()->user();
        $goods_list = $req->goods_list;
        $comment_image = '';

        if(!empty($req->comment_list)){
            foreach($req->comment_list as $v){
                if(empty($comment_image)){
                    $comment_image = $v['url'];
                }else{
                    $comment_image .= ','.$v['url'];
                }
                
            }
        }

        $order_info = $order_model->where('id',$req->order_id)->first();
        if($order_info['comment_status'] == 1){
            return $this->success_msg('已经评论过了');
        }else{
            $order_model->where('id',$req->order_id)->update(['comment_status'=>1,'comment_time'=>time()]);
        }

        foreach($goods_list as $v){
            $data['content'] = $req->content;
            $data['score'] = round(($req->agree+$req->service+$req->speed)/3,1);
            $data['agree'] = $req->agree;
            $data['service'] = $req->service;
            $data['speed'] = $req->speed;
            $data['store_id'] = $req->store_id;
            $data['seller_id'] = $req->seller_id;
            $data['store_name'] = $req->store_name;
            $data['user_id'] = $user_info['id'];
            $data['nickname'] = $user_info['nickname'];
            $data['avatar'] = $user_info['avatar'];
            $data['goods_id'] = $v['goods_id'];
            $data['goods_name'] = $v['goods_name'];
            $data['goods_image'] = $v['image'];
            $data['comment_images'] = $comment_image;
            $data['add_time'] = time();
            $store_comment_model->addComment($data);
        }

        return $this->success_msg('ok');
        

    }

    // 评论图片
    public function comment_image(){
        $uploads = new Uploads;
        $rs = $uploads->uploads(['filepath'=>'comment/','is_thumb'=>1]);
        if($rs['status']){
            return $this->success_msg('Success',$rs['path']);
        }else{
            return $this->error_msg($rs['msg']);
        }
    }
}
