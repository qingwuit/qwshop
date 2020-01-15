<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\GoodsClass;
use App\Tools\Uploads;

class GoodsClassController extends BaseController
{
    public function index(Request $req,GoodsClass $goods_class_model){
        $list = $goods_class_model->orderBy('is_sort','asc')->get();
        $list = getChild($list);
        return $this->success_msg('Success',$list);
    }

    public function add(Request $req,GoodsClass $goods_class_model){
        if(!$req->isMethod('post')){
    		$goods_class_list = $goods_class_model->orderBy('is_sort','desc')->get();
            $list = getTree($goods_class_list);

            $arr = [];
            foreach($list as $v){
                $v['name'] = str_repeat('——',$v['lev']).' '.$v['name'];
                $arr[] = $v;
            }

    		return $this->success_msg('Success',$arr);
    	}

    	$data = [
    		'pid' => intval($req->pid),
    		'name' => $req->name,
    		'thumb' => empty($req->thumb)?'':$req->thumb,
    		'rate' => $req->rate,
    		'tags' => $req->tags??'',
    		'is_sort' => intval($req->is_sort),
    	];

    	$goods_class_model->insert($data);
    	return $this->success_msg();
    }

    public function edit(Request $req,GoodsClass $goods_class_model,$id){
        if(!$req->isMethod('post')){
            $info = $goods_class_model->find($id);
    		$goods_class_list = $goods_class_model->orderBy('is_sort','desc')->where('id','<>',$id)->get();
            $list = getTree($goods_class_list);

            $arr = [];
            foreach($list as $v){
                $v['name'] = str_repeat('——',$v['lev']).' '.$v['name'];
                $arr[] = $v;
            }
            $resp = [
                'info'=>$info,
                'list'=>$list,
            ];

    		return $this->success_msg('Success',$resp);
    	}

    	$data = [
    		'pid' => intval($req->pid),
    		'name' => $req->name,
    		'thumb' => empty($req->thumb)?'':$req->thumb,
            'rate' => $req->rate,
            'tags' => $req->tags??'',
    		'is_sort' => intval($req->is_sort),
    	];

    	$goods_class_model->where('id',$id)->update($data);
    	return $this->success_msg();
    }

    public function del(Request $req,GoodsClass $goods_class_model){
        $id = $req->id;
        $ids = explode(',',$id);
        $goods_class_model->destroy($ids);
        return $this->success_msg();
    }

    public function goods_class_upload(){
        $uploads = new Uploads;
        $rs = $uploads->uploads(['is_thumb'=>1,'filepath'=>'goods_class/']);
        if($rs['status']){
            return $this->success_msg('Success',$rs['path']);
        }else{
            return $this->error_msg($rs['msg']);
        }
    }

}
