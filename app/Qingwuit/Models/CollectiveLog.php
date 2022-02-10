<?php

namespace App\Qingwuit\Models;

use App\Qingwuit\Traits\TimeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CollectiveLog extends Model
{
    use HasFactory,SoftDeletes,TimeTrait;

    protected $guarded = [];

    public function user()
    {
        return $this->hasOne('App\Qingwuit\Models\User', 'id', 'user_id')->withTrashed();
    }

    public function orders()
    {
        return $this->hasMany('App\Qingwuit\Models\Order', 'collective_id', 'id')->withTrashed();
    }
}
