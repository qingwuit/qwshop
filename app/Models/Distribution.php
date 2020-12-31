<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Distribution extends Model
{
    public function goods(){
        return $this->hasOne("App\Models\Goods",'id','goods_id');
    }

    public function distribution_logs(){
        return $this->hasOne("App\Models\DistributionLog",'distribution_id','id');
    }
}
