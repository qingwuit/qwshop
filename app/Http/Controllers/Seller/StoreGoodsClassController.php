<?php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use App\Models\StoreGoodsClass;

class StoreGoodsClassController extends BaseController
{
    public function index(StoreGoodsClass $store_goods_class_model){
        $user_info = auth()->user();
        $list = $store_goods_class_model->where('user_id',$user_info['id'])->orderBy('is_sort','desc')->paginate(20);
        return $this->success_msg('Success',$list);
    }

    public function add(Request $req,StoreGoodsClass $store_goods_class_model){
        if(!$req->isMethod('post')){
    		return $this->success_msg('Success',[]);
    	}
        $user_info = auth()->user();
    	$data = [
    		'user_id' => $user_info['id'],
    		'name' => $req->name,
    		'is_sort' => $req->is_sort,
    	];

    	$store_goods_class_model->insert($data);
    	return $this->success_msg();
    }

    public function edit(Request $req,StoreGoodsClass $store_goods_class_model,$id){
        if(!$req->isMethod('post')){
            $info = $store_goods_class_model->find($id);
    		return $this->success_msg('Success',$info);
    	}

    	$data = [
    		'name' => $req->name,
    		'is_sort' => $req->is_sort,
    	];

    	$store_goods_class_model->where('id',$id)->update($data);
    	return $this->success_msg();
    }

    public function del(Request $req,StoreGoodsClass $store_goods_class_model){
        $id = $req->id;
        $ids = explode(',',$id);
        $store_goods_class_model->destroy($ids);
        return $this->success_msg();
    }
}
