<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Agreement;

class AgreementController extends BaseController
{
    public function index(Agreement $agreement_model){
        $list = $agreement_model->orderBy('id','desc')->paginate(20);
        return $this->success_msg('Success',$list);
    }

    public function add(Request $req,Agreement $agreement_model){
        if(!$req->isMethod('post')){
    		return $this->success_msg('Success',[]);
    	}

    	$data = [
    		'name' => $req->name,
    		'ename' => $req->ename,
    		'content' => $req->content,
    	];

    	$agreement_model->insert($data);
    	return $this->success_msg();
    }

    public function edit(Request $req,Agreement $agreement_model,$id){
        if(!$req->isMethod('post')){
            $info = $agreement_model->find($id);
    		return $this->success_msg('Success',$info);
    	}

    	$data = [
    		'name' => $req->name,
    		'ename' => $req->ename,
    		'content' => $req->content,
    	];

    	$agreement_model->where('id',$id)->update($data);
    	return $this->success_msg();
    }

    public function del(Request $req,Agreement $agreement_model){
        $id = $req->id;
		$ids = explode(',',$id);
        $agreement_model->destroy($ids);
        return $this->success_msg();
    }
}
