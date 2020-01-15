<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\GoodsBrand;
use App\Tools\Uploads;

class GoodsBrandController extends BaseController
{
    public function index(Request $req,GoodsBrand $goods_brand_model){
        $list = $goods_brand_model->orderBy('is_sort','desc')->paginate(20);
        return $this->success_msg('Success',$list);
    }

    public function add(Request $req,GoodsBrand $goods_brand_model){
        if(!$req->isMethod('post')){
    		return $this->success_msg('Success',[]);
    	}

    	$data = [
    		'name' => $req->name,
    		'thumb' => empty($req->thumb)?'':$req->thumb,
    		'is_sort' => intval($req->is_sort),
    	];

    	$goods_brand_model->insert($data);
    	return $this->success_msg();
    }

    public function edit(Request $req,GoodsBrand $goods_brand_model,$id){
        if(!$req->isMethod('post')){
            $info = $goods_brand_model->find($id);
    		return $this->success_msg('Success',$info);
    	}

    	$data = [
    		'name' => $req->name,
    		'thumb' => empty($req->thumb)?'':$req->thumb,
    		'is_sort' => intval($req->is_sort),
    	];

    	$goods_brand_model->where('id',$id)->update($data);
    	return $this->success_msg();
    }

    public function del(Request $req,GoodsBrand $goods_brand_model){
        $id = $req->id;
        $ids = explode(',',$id);
        $goods_brand_model->destroy($ids);
        return $this->success_msg();
    }

    public function goods_barnd_upload(){
        $uploads = new Uploads;
        $rs = $uploads->uploads(['is_thumb'=>1,'filepath'=>'goods_barnd/']);
        if($rs['status']){
            return $this->success_msg('Success',$rs['path']);
        }else{
            return $this->error_msg($rs['msg']);
        }
    }
}
