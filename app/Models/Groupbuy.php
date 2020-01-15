<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Groupbuy extends Model
{
    protected $table = "groupbuy"; //指定表
    protected $primaryKey = "id"; //指定id字段
    public $timestamps = false;

    // 生成团购数据 $data 为商品信息
    public function add_groupbuy($data){
        $groupbuy_user_mode = new GroupbuyUser();
        // 生成团购数据
        $groupbuy_data = [
            'goods_id'  => $data['goods_id'],
            'seller_id'  => $data['seller_id'],
            'groupbuy_num'  => $data['groupbuy_num'],
            'groupbuy_use'  => 1,
            'add_time'  => time(),
        ];
        $groupbuy_user_data = [
            'order_id'      =>  $data['order_id'],
            'order_no'      =>  $data['order_no'],
            'user_id'       =>  $data['user']['id'],
            'nickname'      =>  $data['user']['nickname'],
        ];
        $groupbuy_info = $this->where('goods_id',$groupbuy_data['goods_id'])->orderBy('id','desc')->first();
        if(empty($groupbuy_info) || ($groupbuy_info['groupbuy_num'] == $groupbuy_info['groupbuy_user'])){ // 如果没有存在数据
            $gb_id = $this->insertGetId($groupbuy_data);
            $groupbuy_user_data['gb_id'] = $gb_id;
            $groupbuy_user_mode->insert($groupbuy_user_data);
        }else{
            $this->where('id',$groupbuy_info['id'])->increments('groupbuy_user');
            $groupbuy_user_data['gb_id'] = $groupbuy_user_data['id'];
            $groupbuy_user_mode->insert($groupbuy_user_data);
        }
    }

    // 过去团信息 列表
    public function get_groupbuy_user(){
        return $this->hasMany('App\Models\GroupbuyUser','gb_id','id');
    }

    // 商品信息
    public function get_goods(){
        return $this->hasOne('App\Models\Goods','id','goods_id');
    }
}
