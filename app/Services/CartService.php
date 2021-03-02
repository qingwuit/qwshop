<?php
namespace App\Services;

use App\Http\Resources\Home\CartResource\CartCollection;
use App\Models\Cart;
use App\Models\Goods;
use App\Models\GoodsSku;
use Illuminate\Support\Facades\DB;

class CartService extends BaseService{
    

    // 获取购物车列表
    public function getCarts(){
        $cart_model = new Cart();

        // 获取当前用户user_id
        $user_service = new UserService;
        if(!$user_info = $user_service->getUserInfo()){
            return $this->format_error(__('carts.add_error').'4'); // 获取用户失败
        }
        
        $cart_list = $cart_model->where(['user_id'=>$user_info['id']])
                                // 获取店铺信息
                                ->with(['store'=>function($q){
                                    return $q->select('id','store_name','store_logo');
                                },'carts'=>function($q) use($user_info){// 获取同一店铺的购物车数据
                                    return $q->where('user_id',$user_info['id'])->with(['goods'=>function($query){
                                        $query->select('id','goods_name','goods_master_image','goods_price');
                                    },'goods_sku'=>function($query){
                                        $query->select('id','sku_name','goods_image','goods_price');
                                    }]);
                                }])
                                ->groupBy('store_id')
                                ->paginate(request()->per_page??30);
        return $this->format(new CartCollection($cart_list));
    }

    // 获取购物车数量
    public function getCount(){
        $cart_model = new Cart();

        // 获取当前用户user_id
        $user_service = new UserService;
 
        if(!$user_info = $user_service->getUserInfo()){
            return $this->format_error(__('carts.add_error').'4'); // 获取用户失败
        }
        
        $cart_count = $cart_model->where(['user_id'=>$user_info['id']])
                                ->groupBy('store_id')
                                ->count();
        return $this->format($cart_count);
    }

    // 加入购物车
    public function addCart(){
        $goods_id = abs(intval(request()->goods_id));
        $sku_id = intval(request()->sku_id??0);
        $buy_num = abs(intval(request()->buy_num??1));

        // 判断是否非法上传
        if(empty($goods_id) || empty($buy_num)){
            return $this->format_error(__('carts.add_error'));
        }

        // 获取SKU信息
        $sku_info = [];
        if(!empty($sku_id)){
            $goods_sku_model = new GoodsSku();
            $sku_info = $goods_sku_model->find($sku_id);
            if($sku_info->goods_id != $goods_id){
                $sku_info = [];
                return $this->format_error(__('carts.add_error').'2');
            }
        }

        // 获取当前用户user_id
        $user_service = new UserService;
        if(!$user_info = $user_service->getUserInfo()){
            return $this->format_error(__('carts.add_error').'4'); // 获取用户失败
        }

        // 获取商品店铺信息
        $goods_model = new Goods();
        $goods_info = $goods_model->select('id','store_id')->with('goods_skus')->where('id',$goods_id)->first();
        
        if(!empty(count($goods_info->goods_skus)) && $sku_id == 0){
            return $this->format_error(__('carts.not_chose_sku')); // 未选择SKU
        }

        // 判断购物车有没有同款商品
        $cart_model = new Cart();
        $cart_info = $cart_model->where([
            'user_id'=>$user_info['id'],
            'goods_id'=>$goods_id,
            'sku_id'=>$sku_id,
            // 'buy_num'=>$buy_num,

        ])->first();

        // 如果数据库不存在
        try{
            DB::beginTransaction(); // 事务开始
            if(empty($cart_info)){
                // 加入购物车
                    $cart_model->user_id = $user_info['id'];
                    $cart_model->goods_id = $goods_id;
                    $cart_model->sku_id = $sku_id;
                    $cart_model->buy_num = $buy_num;
                    $cart_model->store_id = $goods_info->store_id;
                    $cart_model->save();
                
            }else{
                $cart_info->buy_num += $buy_num;
                $cart_info->save();
            }
            DB::commit(); // 事务提交
        }catch(\Exception $e){
            DB::rollBack(); // 事务回滚
            return $this->format_error(__('carts.add_error').'3');
        }

        return $this->format([],__('carts.add_success'));
        
    }

    // 修改购物车状态
    public function editCart($id){
        $is_type = intval(request()->is_type??0);
        $buy_num = abs(intval(request()->buy_num??0));

        // 获取当前用户user_id
        $user_service = new UserService;
        if(!$user_info = $user_service->getUserInfo()){
            return $this->format_error(__('carts.add_error').'4'); // 获取用户失败
        }

        // 判断购物车有没有同款商品
        $cart_model = new Cart();
        $cart_info = $cart_model->where([
            'user_id'=>$user_info['id'],
            'id'=>$id,
        ])->first();

        if(empty($cart_info)){
            return $this->format_error(__('carts.add_error').'5'); // 获取用户失败
        }

        // 判断是否修改数量大于0
        if(!empty($buy_num) && $buy_num>1){
            $cart_info->buy_num = $buy_num;
            $cart_info->save();
            return $this->format([],__('carts.edit_success'));
        }

        // 判断是增加还是减少
        if(empty($is_type)){
            if($cart_info->buy_num <= 1){
                $cart_model->where('user_id',$user_info['id'])->where('id',$id)->delete();
            }else{
                $cart_info->buy_num -= 1; // 加减购物车只能为1
                $cart_info->save();
            }
            return $this->format([],__('carts.edit_success'));
        }else{
            $cart_info->buy_num += 1; // 加减购物车只能为1
            $cart_info->save();
            return $this->format([],__('carts.edit_success'));
        }
    }

    
}
