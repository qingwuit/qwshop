<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = "address"; //指定表
    protected $primaryKey = "id"; //指定id字段
    public $timestamps = false;
}
