<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserLinkRole extends Pivot
{
    protected $table = "user_link_role"; //指定表
    protected $primaryKey = "id"; //指定id字段
    public $timestamps = false;
    public $incrementing = true;
}
