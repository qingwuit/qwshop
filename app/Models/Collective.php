<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Collective extends Model
{
    use SoftDeletes;
    public function goods(){
        return $this->hasOne("App\Models\Goods",'id','goods_id')->withTrashed();
    }
}
