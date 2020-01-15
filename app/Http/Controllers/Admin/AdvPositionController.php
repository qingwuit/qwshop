<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AdvPosition;
use App\Models\Adv;

class AdvPositionController extends BaseController
{
    public function index(AdvPosition $adv_position_model){
        $list = $adv_position_model->paginate(20);
        return $this->success_msg('Success',$list);
    }

    public function add(Request $req,AdvPosition $adv_position_model){
        if(!$req->isMethod('post')){
    		return $this->success_msg('Success',[]);
    	}

    	$data = [
    		'ap_name' => $req->ap_name,
    		'ap_isuse' => $req->ap_isuse,
    		'ap_width' => intval($req->ap_width),
    		'ap_height' => intval($req->ap_height),
    	];

    	$adv_position_model->insert($data);
    	return $this->success_msg();
    }

    public function edit(Request $req,AdvPosition $adv_position_model,$id){
        if(!$req->isMethod('post')){
            $info = $adv_position_model->find($id);
    		return $this->success_msg('Success',$info);
    	}

    	$data = [
    		'ap_name' => $req->ap_name,
    		'ap_isuse' => $req->ap_isuse,
    		'ap_width' => intval($req->ap_width),
    		'ap_height' => intval($req->ap_height),
    	];

    	$adv_position_model->where('id',$id)->update($data);
    	return $this->success_msg();
    }

    public function del(Request $req,AdvPosition $adv_position_model,Adv $adv_model){
        $id = $req->id;
		$ids = explode(',',$id);
		$adv_model->whereIn('ap_id',$ids)->delete();
        $adv_position_model->destroy($ids);
        return $this->success_msg();
    }
}
