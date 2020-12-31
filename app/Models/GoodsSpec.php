<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsSpec extends Model
{
    protected $table = 'goods_specs';
    protected $fillable = ['name','attr_id','store_id'];
}
