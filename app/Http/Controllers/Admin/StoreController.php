<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\Roles;
use App\Models\Users;

class StoreController extends BaseController
{
    public function index(Request $req,Store $store_model){

        $store_verify = 1; // 默认取审核通过的店铺
        $where = [
            'store_verify' => $store_verify,
        ];

        if(isset($req->store_verify)){
            $where['store_verify'] = $req->store_verify;
        }

        $list = $store_model->where($where)->orderBy('id','desc')->paginate(20);
        
		return $this->success_msg('Success',$list);
	}


	public function del(Request $req,Store $store_model){
		$id = $req->id;
		$ids = explode(',',$id);
        $store_model->destroy($ids);
        return $this->success_msg();
    }
    
    // 修改店铺信息  审核之类的
    public function store_pass(Request $req,Store $store_model,Roles $roles_model,Users $users_model){
        $info = $req->only(['store_verify','store_status','store_close_info','id']);
        $store_model->where('id',$info['id'])->update($info);

        // 增加权限
        $store_info = $store_model->find($info['id']);
        $users = $users_model->find($store_info['user_id']);

        if($store_info['user_id'] != 1){
            if($info['store_status'] == 1 && $info['store_verify'] == 1){
                $users->roles()->sync([30]);
            }else{
                $users->roles()->sync([]);
            }
        }

        return $this->success_msg('Success',[]);
    }
}
