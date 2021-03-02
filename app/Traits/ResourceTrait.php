<?php
namespace App\Traits;

use App\Models\Store;
use Illuminate\Support\Facades\DB;

trait ResourceTrait{

    // service 返回格式化
    protected function format($data=[],$msg='ok'){
        return ['status'=>true,'data'=>$data,'msg'=>$msg];
    }
    protected function format_error($msg='error',$data=[]){
        return ['status'=>false,'data'=>$data,'msg'=>$msg];
    }


    // Controller 返回格式化
    
    // 成功返回数据
    protected function success($data=[],$msg="ok"){
        return ['code'=>200,'msg'=>$msg,'data'=>$data];
    }

    // 失败返回数据
    protected function error($msg="fail",$data=[]){
        return ['code'=>500,'msg'=>$msg,'data'=>$data];
    }

    // 自定义返回数据
    protected function auto($code,$msg="Other",$data=[]){
        return ['code'=>$code,'msg'=>$msg,'data'=>$data];
    }

    // 商家端 获取店铺ID 
    /*$user_id 用户表ID $only_id 是否只返回ID $select 自定义字段返回 默认返回全部*/
    protected function get_store($only_id = false,$select = ''){
        $stores_model = new Store();
        if(!empty($select)){
            $stores_model = $stores_model->select(DB::raw($select));
        }
        $store_info = $stores_model->where('user_id',auth()->id())->first();
        if($only_id){
            return $store_info['id'];
        }

        return $store_info;
    }
}