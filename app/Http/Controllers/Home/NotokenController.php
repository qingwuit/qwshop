<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NotokenController extends Controller
{
    // 成功返回数据
    protected function success_msg($msg="Success",$data=[]){
        return ['code'=>200,'msg'=>$msg,'data'=>$data];
    }

    // 失败返回数据
    protected function error_msg($msg="Error",$data=[]){
        return ['code'=>500,'msg'=>$msg,'data'=>$data];
    }

    // 自定义返回数据
    protected function auto_msg($code,$msg="Other",$data=[]){
        return ['code'=>$code,'msg'=>$msg,'data'=>$data];
    }
}
