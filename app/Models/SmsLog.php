<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsLog extends Model
{
    protected $table = "sms_log"; //指定表
    protected $primaryKey = "id"; //指定id字段
    public $timestamps = false;
}
