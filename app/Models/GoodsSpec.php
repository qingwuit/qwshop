<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsSpec extends Model
{
    use SoftDeletes;
    protected $table = 'goods_specs';
    protected $fillable = ['name','attr_id','store_id'];
}
