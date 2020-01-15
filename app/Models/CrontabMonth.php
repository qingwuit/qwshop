<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrontabMonth extends Model
{
    protected $table = "crontab_month"; //指定表
    protected $primaryKey = "id"; //指定id字段
    public $timestamps = false;
}
