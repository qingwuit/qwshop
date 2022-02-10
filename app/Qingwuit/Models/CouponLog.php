<?php

namespace App\Qingwuit\Models;

use App\Qingwuit\Traits\TimeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CouponLog extends Model
{
    use HasFactory,SoftDeletes,TimeTrait;

    protected $guarded = [];

    protected $dates = ['start_time','end_time'];

    public function user()
    {
        return $this->hasOne('App\Qingwuit\Models\User', 'id', 'user_id')->withTrashed();
    }
}
