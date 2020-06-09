<?php
namespace App\Traits;

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
}