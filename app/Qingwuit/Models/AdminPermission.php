<?php

namespace App\Qingwuit\Models;

use App\Qingwuit\Traits\TimeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminPermission extends Model
{
    use HasFactory,SoftDeletes,TimeTrait;

    protected $guarded = [];

    public function permission_groups()
    {
        return $this->hasOne('App\Qingwuit\Models\AdminPermissionGroup', 'id', 'pid');
    }
}
