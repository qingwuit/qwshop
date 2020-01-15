<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FreightTemplate extends Model
{
    protected $table = "freight_template"; //指定表
    protected $primaryKey = "id"; //指定id字段
    public $timestamps = false;

    /**
     * 获取订单最高的运费，如果多个商品不同则优先取最高
     *
     * @param [type] $list  // 模版列表
     * @param [type] $region_id  // 地区ID
     * @return void
     * @Description
     * @author hg <www.qingwuit.com>
     */
    public function high_freight($list,$region_id){
        $freight_money = 0;
        foreach($list as $v){
            $info = json_decode($v['content'],true);
            // if($v['price']>$freight_money){
            //     $freight_money = $info['price'];
            // }
            $a = 0; // 判断是否有存在地区
            foreach($info as $vo){
                foreach($vo['content'] as $voo){
                    if($voo[2] == $region_id){
                        $a = 1;
                        if($vo['price']>$freight_money){
                            $freight_money = $vo['price'];
                        }
                        break;
                    }
                }
            }

            // 不存在则取默认值
            if($a == 0){
                if($v['price']>$freight_money){
                    $freight_money = $v['price'];
                }
            }

        }

        return $freight_money;
    }

}
