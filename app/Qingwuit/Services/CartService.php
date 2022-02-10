<?php
namespace App\Qingwuit\Services;

use App\Http\Resources\CartHomeCollection;
use Illuminate\Support\Facades\DB;

class CartService extends BaseService
{
    // 获取购物车列表
    public function getCart()
    {
        // 获取当前用户user_id
        $userId = $this->getUserId('users');
        $cartList = $this->getService('Cart', true)->where(['user_id'=>$userId])
                                // 获取店铺信息
                                ->with(['store'=>function ($q) {
                                    return $q->select('id', 'store_name', 'store_logo');
                                },'carts'=>function ($q) use ($userId) {// 获取同一店铺的购物车数据
                                    return $q->where('user_id', $userId)->with(['goods'=>function ($query) {
                                        $query->select('id', 'goods_name', 'goods_master_image', 'goods_price');
                                    },'goods_sku'=>function ($query) {
                                        $query->select('id', 'sku_name', 'goods_image', 'goods_price');
                                    }]);
                                }])
                                ->groupBy('store_id')
                                ->paginate(request()->per_page??30);
        return $this->format(new CartHomeCollection($cartList));
    }

    // 获取购物车数量
    public function getCount()
    {
        // 获取当前用户user_id
        try {
            $userId = $this->getUserId('users');
        } catch (\Exception $e) {
            return $this->format(0, $e->getMessage());
        }
        $cartCount = $this->getService('Cart', true)->where(['user_id'=>$userId])
                                ->groupBy('store_id')
                                ->count();
        return $this->format($cartCount);
    }

    // 加入购物车
    public function addCart()
    {
        $goods_id = abs(intval(request()->goods_id));
        $sku_id = intval(request()->sku_id??0);
        $buy_num = abs(intval(request()->buy_num??1));

        // 判断是否非法上传
        if (empty($goods_id) || empty($buy_num)) {
            return $this->formatError(__('tip.error'));
        }

        // 获取SKU信息
        $sku_info = [];
        if (!empty($sku_id)) {
            $sku_info = $this->getService('GoodsSku', true)->find($sku_id);
            if ($sku_info->goods_id != $goods_id) {
                $sku_info = [];
                return $this->formatError(__('tip.error').'2');
            }
        }

        // 获取当前用户user_id
        $userId = $this->getUserId('users');

        // 获取商品店铺信息
        $goods_info = $this->getService('Goods', true)->select('id', 'store_id')->with('goods_skus')->where('id', $goods_id)->first();
        
        if (!empty(count($goods_info->goods_skus)) && $sku_id == 0) {
            return $this->formatError(__('tip.goods.checkSku')); // 未选择SKU
        }

        // 判断购物车有没有同款商品
        $cart_info = $this->getService('Cart', true)->where([
            'user_id'=>$userId,
            'goods_id'=>$goods_id,
            'sku_id'=>$sku_id,
        ])->first();

        // 如果数据库不存在
        try {
            DB::beginTransaction(); // 事务开始
            if (empty($cart_info)) {
                // 加入购物车
                $cartModel = $this->getService('Cart', true);
                $cartModel->user_id = $userId;
                $cartModel->goods_id = $goods_id;
                $cartModel->sku_id = $sku_id;
                $cartModel->buy_num = $buy_num;
                $cartModel->store_id = $goods_info->store_id;
                $cartModel->save();
            } else {
                $cart_info->buy_num += $buy_num;
                $cart_info->save();
            }
            DB::commit(); // 事务提交
        } catch (\Exception $e) {
            DB::rollBack(); // 事务回滚
            return $this->formatError($e->getMessage());
        }

        return $this->format([], __('tip.success'));
    }

    // 修改购物车状态
    public function editCart($id)
    {
        $is_type = intval(request()->is_type??0);
        $buy_num = abs(intval(request()->buy_num??0));

        // 获取当前用户user_id
        $userId = $this->getUserId('users');

        // 判断购物车有没有同款商品
        $cart_info = $this->getService('Cart', true)->where([
            'user_id'=>$userId,
            'id'=>$id,
        ])->first();

        if (empty($cart_info)) {
            return $this->formatError(__('tip.error').'5'); // 获取用户失败
        }

        // 判断是否修改数量大于0
        if (!empty($buy_num) && $buy_num>1) {
            $cart_info->buy_num = $buy_num;
            $cart_info->save();
            return $this->format([], __('tip.success'));
        }

        // 判断是增加还是减少
        if (empty($is_type)) {
            if ($cart_info->buy_num <= 1) {
                $this->getService('Cart', true)->where('user_id', $userId)->where('id', $id)->delete();
            } else {
                $cart_info->buy_num -= 1; // 加减购物车只能为1
                $cart_info->save();
            }
            return $this->format([], __('tip.success'));
        } else {
            $cart_info->buy_num += 1; // 加减购物车只能为1
            $cart_info->save();
            return $this->format([], __('tip.success'));
        }
    }
}
