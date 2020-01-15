<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Roles;
use App\Models\Menus;
use App\Models\Hooks;
use Illuminate\Support\Facades\DB;

/**
 * RolesController class
 *
 * @author hg <www.qingwuit.com>
 */

class RolesController extends BaseController
{	
	// 列表
    public function index(Roles $roles_model){
    	$list = $roles_model->orderBy('id','desc')->paginate(20);
    	return $this->success_msg('Success',$list);
    }

    // 添加
    public function add(Request $req,Roles $roles_model,Menus $menus_model,Hooks $hooks_model){

    	if(!$req->isMethod('post')){
            $data['menus_list'] = getTree($menus_model->get());
			$data['hooks_list'] = $hooks_model->get();
			return $this->success_msg('Success',$data);
		}

		try{

			DB::beginTransaction();

			$roles = $roles_model->create(['name'=>$req->name,'content'=>$req->content]);

			// 菜单
			if(!empty($req->menus)){
				// $menus_info = [];
				// $menus_permission = [];
				// foreach($req->menus as $v){
				// 	$arr = [];
				// 	$arr = explode(',',$v);
				// 	if(!in_array($arr[0],$menus_info)){
				// 		$arr[] = $arr[0];
				// 		if(!isset($menus_permission[$arr[0]])){
				// 			$menus_permission[$arr[0]] = [];
				// 		}
				// 		if(!in_array($arr[1],$menus_permission[$arr[0]])){
				// 			$menus_permission[$arr[0]][] = $arr[1];
				// 		}
				// 	}
				// }

				// foreach($menus_permission as $k=>$v){
				// 	$count = count($v);
				// 	if($count>=5 || ($count == 1 && $v[0] == 'all') || ($count == 4 && !in_array('all',$v)) ){
				// 		$v = ['commands'=>'all'];
				// 	}else{
				// 		if(($search_key = array_search('all',$v)) !== false){
				// 			unset($v[$search_key]);
				// 		}
				// 		$v = ['commands'=>implode(',',$v)];
				// 	}

				// 	$menus_permission[$k] = $v;
				// }
				$roles->menus()->attach($req->menus);
			}

			// 钩子
			if(!empty($req->hooks)){
				$roles->hooks()->attach($req->hooks);
			}
			
			DB::commit();
		}catch(\Exception $e){
			DB::rollBack();
			return $this->error_msg($e->getMessage());
		}

    	return $this->success_msg();

    }

    // 编辑
    public function edit(Request $req,Roles $roles_model,Menus $menus_model,Hooks $hooks_model,$id){

		// 得到对应模型
		$roles_info = $roles_model->with(['menus','hooks'])->find($id);

		// 判断是否POST
		if(!$req->isMethod('post')){
			$data = $roles_info;
			$data['menus_list'] = getTree($menus_model->orderBy('is_type','asc')->get());
			$data['hooks_list'] = $hooks_model->get();
			return $this->success_msg('Success',$data);
		}

		try{

			DB::beginTransaction();

			$roles = $roles_model->where('id',$id)->update(['name'=>$req->name,'content'=>$req->content]);

			// 菜单
			if(!empty($req->menus)){
				// $menus_info = [];
				// $menus_permission = [];
				// foreach($req->menus as $v){
				// 	$arr = [];
				// 	$arr = explode(',',$v);
				// 	if(!in_array($arr[0],$menus_info)){
				// 		$arr[] = $arr[0];
				// 		if(!isset($menus_permission[$arr[0]])){
				// 			$menus_permission[$arr[0]] = [];
				// 		}
				// 		if(!in_array($arr[1],$menus_permission[$arr[0]])){
				// 			$menus_permission[$arr[0]][] = $arr[1];
				// 		}
				// 	}
				// }

				// foreach($menus_permission as $k=>$v){
				// 	$count = count($v);
				// 	if($count>=5 || ($count == 1 && $v[0] == 'all') || ($count == 4 && !in_array('all',$v)) ){
				// 		$v = ['commands'=>'all'];
				// 	}else{
				// 		if(($search_key = array_search('all',$v)) !== false){
				// 			unset($v[$search_key]);
				// 		}
				// 		$v = ['commands'=>implode(',',$v)];
				// 	}

				// 	$menus_permission[$k] = $v;
				// }
				$roles_info->menus()->sync($req->menus);
			}else{
				$roles_info->menus()->sync([]);
			}

			// 钩子
			if(!empty($req->hooks)){
				$roles_info->hooks()->sync($req->hooks);
			}else{
				$roles_info->hooks()->sync([]);
			}
			
			DB::commit();
		}catch(\Exception $e){
			DB::rollBack();
			return $this->error_msg($e->getMessage());
		}

    	return $this->success_msg();
    	
    }

    // 删除
    public function del(Request $req,Roles $roles_model){

		$id = $req->id;
		$ids = explode(',',$id);

		if(in_array(1,$ids)){
			return $this->error_msg('超级管理员，不能删除.');
		}
		
		$roles = $roles_model->whereIn('id',$ids)->get();

		// 循环去掉中间关联
		foreach($roles as $v){
			$v->menus()->sync([]);
			$v->hooks()->sync([]);
		}

		$roles_model->destroy($ids);

    	return $this->success_msg();
    	
    }


}
