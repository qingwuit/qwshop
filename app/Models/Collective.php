<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collective extends Model
{
    public function goods(){
        return $this->hasOne("App\Models\Goods",'id','goods_id');
    }
}
