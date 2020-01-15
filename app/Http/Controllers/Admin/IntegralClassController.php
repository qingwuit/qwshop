<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\IntegralClass;

class IntegralClassController extends BaseController
{
    public function index(IntegralClass $integral_class_model){
        $list = $integral_class_model->orderBy('id','desc')->paginate(20);
        return $this->success_msg('Success',$list);
    }

    public function add(Request $req,IntegralClass $integral_class_model){
        if(!$req->isMethod('post')){
    		return $this->success_msg('Success',[]);
    	}
        $user_info = auth()->user();
    	$data = [
    		'name' => $req->name,
    	];

    	$integral_class_model->insert($data);
    	return $this->success_msg();
    }

    public function edit(Request $req,IntegralClass $integral_class_model,$id){
        if(!$req->isMethod('post')){
            $info = $integral_class_model->find($id);
    		return $this->success_msg('Success',$info);
    	}

    	$data = [
    		'name' => $req->name,
    	];

    	$integral_class_model->where('id',$id)->update($data);
    	return $this->success_msg();
    }

    public function del(Request $req,IntegralClass $integral_class_model){
        $id = $req->id;
        $ids = explode(',',$id);
        $integral_class_model->destroy($ids);
        return $this->success_msg();
    }
}
