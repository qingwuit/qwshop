<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashLog extends Model
{
    protected $table = "cash_log"; //指定表
    protected $primaryKey = "id"; //指定id字段
    public $timestamps = false;

    public function addCash($data){
        $data['add_time'] = time();
        return $this->insert($data);
    }

    // 修改提现日志
    public function editCash($id,$where){
        return $this->where('id',$id)->update($where);
    }
}
