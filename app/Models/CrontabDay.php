<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrontabDay extends Model
{
    protected $table = "crontab_day"; //指定表
    protected $primaryKey = "id"; //指定id字段
    public $timestamps = false;
}
