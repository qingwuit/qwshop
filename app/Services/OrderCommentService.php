<?php
namespace App\Services;

use App\Models\Order;
use App\Models\OrderComment;

class OrderCommentService extends BaseService{
    
    /**
     * 添加评论 function
     *
     * @param mixed request()->order_id
     * @param mixed request()->score
     * @param mixed request()->agree
     * @param mixed request()->service
     * @param mixed request()->speed
     * @param mixed request()->content
     * @param mixed request()->image
     * @return void
     * @Description
     * @author hg <www.qingwuit.com>
     */
    public function add($ids=[]){
        // 判断订单是否已经评论过了
        $order_model = new Order();
        $user_service = new UserService();
        
        
        if(!empty($ids)){
            $idArray = $ids;
            $order_list = $order_model->with('order_goods')->whereIn('id',$idArray)->where('order_status',4)->get();
        }else{
            $user_info = $user_service->getUserInfo();
            $idArray = array_filter(explode(',',request()->order_id),function($item){
                return is_numeric($item);
            });
            $order_list = $order_model->with('order_goods')->whereIn('id',$idArray)->where('user_id',$user_info['id'])->where('order_status',4)->get();
        }
        
        if(empty($order_list)){
            return $this->format_error(__('orders.order_comment_error'));
        }

        $data = [];
        $score = request()->score??5.00;
        $agree = request()->agree??5.00;
        $service = request()->service??5.00;
        $speed = request()->speed??5.00;
        $content = request()->content??'非常好！';
        $image = request()->image??'';
        foreach($order_list as $v){
            foreach($v['order_goods'] as $vo){
                $comment = [];
                $comment['user_id'] = $user_info['id'];
                $comment['goods_id'] = $vo['goods_id'];
                $comment['order_id'] = $v['id'];
                $comment['score'] = $score;
                $comment['agree'] = $agree;
                $comment['service'] = $service;
                $comment['service'] = $speed;
                $comment['content'] = $content;
                $comment['image'] = $image;
                $data[] = $comment;
            }
        }

        if(!empty($ids)){
            $order_model->whereIn('id',$idArray)->update(['order_status'=>6]);
        }else{
            $order_model->whereIn('id',$idArray)->where('user_id',$user_info['id'])->update(['order_status'=>6]);
        }
        $rs = OrderComment::insert($data);
        return $this->format($rs,__('orders.order_comment_success'));
    }

    /**
     * 商家回复 function
     *
     * @param string request()->reply|request()->content
     * @return void
     * @Description
     * @author hg <www.qingwuit.com>
     */
    public function edit($id,$auth='user'){

        if($auth == 'seller'){
            $store_id = $this->get_store(true);
            $oc_model = OrderComment::where('id',$id)->where('store_id',$store_id)->first();
            $oc_model->reply = request()->reply??'';
            $oc_model->save();
            return $this->format(__('orders.order_comment_success'));
        }
        if($auth == 'user'){
            $user_service = new UserService();
            $user_info = $user_service->getUserInfo();
            $oc_model = OrderComment::where('id',$id)->where('user_id',$user_info['id'])->first();
            $score = request()->score;
            $agree = request()->agree;
            $service = request()->service;
            $speed = request()->speed;
            $content = request()->content;
            $image = request()->image;
            $oc_model->score = $score;
            $oc_model->agree = $agree;
            $oc_model->service = $service;
            $oc_model->speed = $speed;
            $oc_model->content = $content;
            $oc_model->image = $image;
            $oc_model->save();
            return $this->format(__('orders.order_comment_success'));
        }
        
    }
}
