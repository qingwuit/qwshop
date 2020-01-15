<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\GoodsSpec;
use App\Models\Goods;

class Cart extends Model
{
    protected $table = "cart"; //指定表
    protected $primaryKey = "id"; //指定id字段
    public $timestamps = false;


    // 添加入购物车
    public function add_cart($data){
        $cart_info = $this->where(['goods_id'=>$data['goods_id'],'user_id'=>$data['user_id'],'spec_id'=>$data['spec_id']])->first();
        if(!empty($cart_info)){
            if($data['spec_id']>0){
                // 获取属性规格
                $goods_spec_model = new GoodsSpec();
                $goods_spec_info = $goods_spec_model->select('goods_num')->find($data['spec_id']);
            }else{
                $goods_model = new Goods();
                $goods_spec_info = $goods_model->select('goods_num')->find($data['goods_id']);
            }

            if($goods_spec_info['goods_num']<$data['goods_num']){
                return ['status'=>false,'msg'=>'库存不足'];
            }

            $rs = $this->where('id',$cart_info['id'])->increment('goods_num',$data['goods_num']);  // 购物车加上
            if($rs){
                if($data['spec_id']>0){    // 判断是否有规格
                    $goods_spec_model->where('id',$data['spec_id'])->decrement('goods_num',$data['goods_num']);
                }else{
                    $goods_model->where('id',$data['goods_id'])->decrement('goods_num',$data['goods_num']);
                }
            }else{
                return ['status'=>false,'msg'=>'购物车添加错误'];
            }
            
        }else{
            $rs = $this->insert($data);
            if($rs){
                if($data['spec_id']>0){    // 判断是否有规格
                    $goods_spec_model = new GoodsSpec();
                    $goods_spec_model->where('id',$data['spec_id'])->decrement('goods_num',$data['goods_num']);
                }else{
                    $goods_model = new Goods();
                    $goods_model->where('id',$data['goods_id'])->decrement('goods_num',$data['goods_num']);
                }
            }else{
                return ['status'=>false,'msg'=>'购物车添加错误'];
            }
        }

        return ['status'=>true,'msg'=>'加入购物车成功'];
        
    }

    // 编辑购物车商品信息 false 减 true  加  $data['id']  $data['goods_num'] $data['type']
    public function change_cart($data,$type=false){
        $is_change_num = $data['type']??false; // 是否直接改变数量
        

        $cart_info = $this->where('id',$data['id'])->first(); // 获取这个购物车商品的信息

        if(empty($cart_info)){
            return ['status'=>false,'msg'=>'不存在该购物车商品'];
        }


        if($cart_info['spec_id']>0){
            // 获取属性规格
            $goods_spec_model = new GoodsSpec();
            $goods_spec_info = $goods_spec_model->select('goods_num')->find($cart_info['spec_id']);
        }else{
            $goods_model = new Goods();
            $goods_spec_info = $goods_model->select('goods_num')->find($cart_info['goods_id']);
        }
        

        // 如果为true 则表示直接修改数据
        if(!empty($is_change_num)){
            if($cart_info['goods_num']<$data['goods_num']){
                $type = true;
                $update_num = $data['goods_num'] - $cart_info['goods_num'];
            }else{
                $type = false;
                $update_num = $cart_info['goods_num'] - $data['goods_num'];
            }
        }else{
            $update_num = $data['goods_num'];
        }
        
        // 判断是否还有足够的库存
        if($type){
            if($goods_spec_info['goods_num']<$update_num){
                return ['status'=>false,'msg'=>'库存不足'];
            }
            // 修改数据库购物车 商品数量
            $rs = $this->where('id',$cart_info['id'])->increment('goods_num',$update_num);
            if($rs){ // 如果执行成功则减少规格属性内的数量
                if($cart_info['spec_id']>0){    // 判断是否有规格
                    $goods_spec_model->where('id',$cart_info['spec_id'])->decrement('goods_num',$update_num);
                }else{
                    $goods_model->where('id',$cart_info['goods_id'])->decrement('goods_num',$update_num);
                }
                
            }
        }else{
            // 修改数据库购物车 商品数量
            if($cart_info['goods_num']-$update_num<=0){
                $this->del_cart([$cart_info['id']]);
                return ['status'=>true,'msg'=>'修改成功'];
            }
            $rs = $this->where('id',$cart_info['id'])->decrement('goods_num',$update_num);
            if($rs){ // 如果执行成功则添加规格属性内的数量
                if($cart_info['spec_id']>0){    // 判断是否有规格
                    $goods_spec_model->where('id',$cart_info['spec_id'])->increment('goods_num',$update_num);
                }else{
                    $goods_model->where('id',$cart_info['goods_id'])->increment('goods_num',$update_num);
                }
            }
        }

        return ['status'=>true,'msg'=>'修改成功'];
        
    }

    // 删除购物车的数据
    public function del_cart($ids){
        $user_info = auth()->user();
        $cart_list = $this->where('user_id',$user_info['id'])->whereIn('id',$ids)->get(); // 获取到购物车列表
        // 获取属性规格模型
        $goods_spec_model = new GoodsSpec();
        $goods_model = new Goods();
        foreach($cart_list as $v){
            $rs = $this->where('id',$v['id'])->delete();
            if(!$rs){
                return false;
            }
            if($v['spec_id']>0){
                $goods_spec_model->where('id',$v['spec_id'])->increment('goods_num',$v['goods_num']); // 修改规格属性的数据
            }else{
                $goods_model->where('id',$v['goods_id'])->increment('goods_num',$v['goods_num']); // 修改商品的数据
            }
            
        }

        return true;
    }
}
