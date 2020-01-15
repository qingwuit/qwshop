<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttrSpec extends Model
{
    protected $table = "attr_spec"; //指定表
    protected $primaryKey = "id"; //指定id字段
    public $timestamps = false;
}
