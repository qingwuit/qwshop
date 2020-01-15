<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agreement extends Model
{
    protected $table = "agreement"; //指定表
    protected $primaryKey = "id"; //指定id字段
    public $timestamps = false;

    // 根据英文名获取数据
    public function get_agreement_by_ename($ename){
        return $this->where('ename',$ename)->first();
    }
}
