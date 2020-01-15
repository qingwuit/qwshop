<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Adv extends Model
{
    protected $table = "adv"; //指定表
    protected $primaryKey = "id"; //指定id字段
    public $timestamps = false;
}
