<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsClass extends Model
{
    use SoftDeletes;
    protected $table = 'goods_classes';
}
