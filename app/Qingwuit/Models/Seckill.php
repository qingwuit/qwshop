<?php

namespace App\Qingwuit\Models;

use App\Qingwuit\Traits\TimeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Seckill extends Model
{
    use HasFactory,SoftDeletes,TimeTrait;

    protected $guarded = [];

    protected $dates = ['start_time','end_time'];

    public function goods()
    {
        return $this->hasOne('App\Qingwuit\Models\Goods', 'id', 'goods_id')->withTrashed();
    }
}
