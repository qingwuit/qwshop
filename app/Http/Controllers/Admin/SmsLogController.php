<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SmsLog;

class SmsLogController extends BaseController
{
    // 列表
    public function index(SmsLog $sms_log_model){
    	$list = $sms_log_model->orderBy('id','desc')->paginate(20);
    	return $this->success_msg('Success',$list);
    }

    // 删除
    public function del(Request $req,SmsLog $sms_log_model){
		$id = $req->id;
		$ids = explode(',',$id);
		$sms_log_model->destroy($ids);
    	return $this->success_msg();
    	
    }
}