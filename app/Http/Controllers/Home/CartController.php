<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\Models\Cart;

class CartController extends Controller
{
    // 购物车列表
    public function index(){
        $cart_service = new CartService;
        $rs = $cart_service->getCarts();
        return $rs['status']?$this->success($rs['data']):$this->error($rs['msg']);
    }

    // 添加购物车
    public function store(){
        $cart_service = new CartService;
        $rs = $cart_service->addCart();
        return $rs['status']?$this->success($rs['data'],$rs['msg']):$this->error($rs['msg']);
    }

    // 修改购物车
    public function update($id){
        $cart_service = new CartService;
        $rs = $cart_service->editCart($id);
        return $rs['status']?$this->success($rs['data'],$rs['msg']):$this->error($rs['msg']);
    }

    // 删除购物车商品
    public function destroy(Cart $cart_model,$id){
        $idArray = array_filter(explode(',',$id),function($item){
            return is_numeric($item);
        });

        // 获取当前用户user_id
        $user_service = new UserService;
        if(!$user_info = $user_service->getUserInfo()){
            return $this->format_error(__('carts.add_error').'4'); // 获取用户失败
        }

        $cart_model->whereIn('id',$idArray)->where('user_id',$user_info['id'])->delete();
        return $this->success([],__('base.success'));
    }

    // 获取购物车数量
    public function cart_count(){
        $cart_service = new CartService;
        $rs = $cart_service->getCount();
        return $rs['status']?$this->success($rs['data'],$rs['msg']):$this->error($rs['msg']);
    }
}
