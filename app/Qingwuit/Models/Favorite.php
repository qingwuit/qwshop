<?php

namespace App\Qingwuit\Models;

use App\Qingwuit\Traits\TimeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Favorite extends Model
{
    use HasFactory,SoftDeletes,TimeTrait;

    protected $guarded = [];

    public function goods()
    {
        return $this->hasOne('App\Qingwuit\Models\Goods', 'id', 'out_id')->withTrashed();
    }

    public function store()
    {
        return $this->hasOne('App\Qingwuit\Models\Store', 'id', 'out_id')->withTrashed();
    }
}
