<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Goods;

class GoodsController extends BaseController
{
    public function index(Request $req,Goods $goods_model){

        if(!empty($req->goods_name)){
            $goods_model = $goods_model->where('goods_name','like','%'.$req->goods_name.'%');
        }

        $list = $goods_model->with('spec')->orderBy('id','desc')->paginate(20);
        return $this->success_msg('Success',$list);
    }

    // 指定上架
    public function goods_status(Request $req,Goods $goods_model){
        $id = $req->id;
        $goods_info = $goods_model->find($id);
        $goods_status = $goods_info['goods_status']==1?0:1;
        $goods_model->where('id',$id)->update(['goods_status'=>$goods_status]);
        return $this->success_msg();
    }

    // 指定首页
    public function goods_index(Request $req,Goods $goods_model){
        $id = $req->id;
        $goods_info = $goods_model->find($id);
        $goods_status = $goods_info['is_index']==1?0:1;
        $goods_model->where('id',$id)->update(['is_index'=>$goods_status]);
        return $this->success_msg();
    }

    // 指定审核通过
    public function goods_verify_change(Request $req,Goods $goods_model){
        $id = $req->id;
        $status = $req->status??0;
        $goods_model->where('id',$id)->update(['goods_verify'=>$status]);
        return $this->success_msg();
    }

    // 待审核列表
    public function goods_verify(Goods $goods_model){
        $list = $goods_model->with('spec')->where('goods_verify',2)->orderBy('id','desc')->paginate(20);
        return $this->success_msg('Success',$list);
    }

    

    
}
