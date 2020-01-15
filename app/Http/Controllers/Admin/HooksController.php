<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Hooks;

class HooksController extends BaseController
{

    public function index(Request $req,Hooks $hooks_model){
        $list = $hooks_model->orderBy('id','desc')->paginate(20);
        return $this->success_msg('Success',$list);
    }

    public function add(Request $req,Hooks $hooks_model){
    	
    	$data = [
    		'name' => $req->name,
    		'controller_action' => $req->controller_action,
    		'apis' => $req->apis,
    		'is_type' => $req->is_type,
    		'content' => $req->content??'',
    	];

    	$hooks_model->insert($data);
    	return $this->success_msg();
    }


    public function edit(Request $req,Hooks $hooks_model,$id){
        if(!$req->isMethod('post')){
            $hooks_info = $hooks_model->find($id);
            return $this->success_msg('Success',$hooks_info);
        }

        $data = [
            'name' => $req->name,
            'controller_action' => $req->controller_action,
    		'apis' => $req->apis,
    		'is_type' => $req->is_type,
            'content' => $req->content??'',
        ];

        $hooks_model->where('id',$id)->update($data);
        return $this->success_msg();
    }

    public function del(Request $req,Hooks $hooks_model){
        $id = $req->id;
        $ids = explode(',',$id);
        $hooks_model->destroy($ids);
        return $this->success_msg();
    }

}
