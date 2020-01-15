<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Users;

class MoneyLog extends Model
{
    protected $table = "money_log"; //指定表
    protected $primaryKey = "id"; //指定id字段
    public $timestamps = false;

    public function addLog($data){
        $data['add_time'] = time();
        return $this->insert($data);
    }
}
