<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class RoleLinkMenu extends Pivot
{
    protected $table = "role_link_menu"; //指定表
    protected $primaryKey = "id"; //指定id字段
    public $timestamps = false;
    public $incrementing = true;
}
