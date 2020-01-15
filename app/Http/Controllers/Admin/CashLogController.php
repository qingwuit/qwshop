<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CashLog;

class CashLogController extends BaseController
{
    public function index(Request $req,CashLog $cash_log_model){
        $list = $cash_log_model->orderBy('status','desc')->orderBy('id','desc')->paginate(20);
        return $this->success_msg('ok',$list);
    }

    public function change_status(Request $req,CashLog $cash_log_model){
        $id = $req->id;
        $info = $cash_log_model->where('id',$id)->first();
        if($info['status'] == 1){
            $cash_log_model->where('id',$id)->update(['status'=>0]);
        }else{
            $cash_log_model->where('id',$id)->update(['status'=>1]);
        }
        return $this->success_msg('ok');
    }

    public function del(Request $req,CashLog $cash_log_model){
        $id = $req->id;
        $ids = explode(',',$id);
        $cash_log_model->destroy($ids);
        return $this->success_msg();
    }
}
