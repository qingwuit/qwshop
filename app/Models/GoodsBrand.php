<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsBrand extends Model
{
    use SoftDeletes;
    protected $table = 'goods_brands';
}
