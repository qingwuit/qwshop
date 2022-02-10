<?php

namespace App\Qingwuit\Models;

use App\Qingwuit\Traits\TimeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Area extends Model
{
    use HasFactory,SoftDeletes,TimeTrait;

    protected $guarded = [];

    public function hasChildren()
    {
        return $this->hasOne('App\Qingwuit\Models\Area', 'pid', 'code');
    }
}
