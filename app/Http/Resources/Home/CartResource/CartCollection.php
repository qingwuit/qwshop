<?php

namespace App\Http\Resources\Home\CartResource;

use App\Traits\HelperTrait;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CartCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    use HelperTrait;
    public function toArray($request)
    {
        return [
            'data'=>$this->collection->map(function($item){
                return [
                    'store_id'              =>  $item->store->id,
                    'user_id'               =>  $item->user_id,
                    'store_name'            =>  $item->store->store_name??'',
                    'store_logo'            =>  $item->store->store_logo??'',
                    'checked'               =>  false,
                    'css'                   =>  false,
                    'cart_list'             =>  $item->carts->map(function($cartItem){

                                                    // 是否存在sku 
                                                    $goods_image = empty($cartItem->goods)?'':$this->thumb($cartItem->goods->goods_master_image,150);
                                                    $goods_price = $cartItem->goods->goods_price??'0.00';
                                                    $sku_name = '-';
                                                    if(!empty($cartItem->goods_sku)){
                                                        $goods_price = $cartItem->goods_sku->goods_price??'0.00';
                                                        $sku_name = $cartItem->goods_sku->sku_name??'-';
                                                    }
                                                    return [
                                                        'cart_id'               =>  $cartItem->id,
                                                        'goods_id'              =>  $cartItem->goods_id,
                                                        'sku_id'                =>  $cartItem->sku_id,
                                                        'goods_name'            =>  $cartItem->goods->goods_name??__('goods.goods_not_found'),
                                                        'buy_num'               =>  $cartItem->buy_num,
                                                        'goods_image'           =>  $goods_image,
                                                        'goods_price'           =>  $goods_price,
                                                        'sku_name'              =>  $sku_name,
                                                        'checked'               =>  false,
                                                    ];
                                                }),
                ];
            }),
            // 'data'=>$this->collection,
            'total'=>$this->total(), // 数据总数
            'per_page'=>$this->perPage(), // 每页数量
            'current_page'=>$this->currentPage(), // 当前页码
        ];
    }
}
