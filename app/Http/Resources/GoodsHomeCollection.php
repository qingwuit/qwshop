<?php

namespace App\Http\Resources;

use App\Qingwuit\Services\ToolService;
use Illuminate\Http\Resources\Json\ResourceCollection;

class GoodsHomeCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $tool = new ToolService();
        return $this->collection->map(function ($item) use ($tool) {
            $goods_price = $item->goods_price;
            $goods_market_price = $item->goods_market_price;
            $goods_stock = $item->goods_stock;

            // 判断是否存在sku
            if (isset($item->goods_skus) && count($item->goods_skus) > 0) {
                $goods_stock = 0;
                $skus = [];
                foreach ($item->goods_skus as $v) {
                    if (empty($v['deleted_at'])) $goods_stock += $v['goods_stock'];
                    if (empty($v['deleted_at'])) $skus[] = $v;
                }

                if (!empty($skus)) {
                    $goods_price = $skus[0]['goods_price'];
                    $goods_market_price = $skus[0]['goods_market_price'];
                }
            }

            if (!empty($item->collective)) {
                $goods_price = round($goods_price * (1 - $item->collective->discount / 100), 2);
            }

            return [
                'id'                    =>  $item->id,
                'goods_name'            =>  $item->goods_name,
                'goods_subname'         =>  $item->goods_subname,
                'goods_price'           =>  $goods_price,
                'goods_market_price'    =>  $goods_market_price,
                'goods_stock'           =>  $goods_stock,
                'goods_sale'            =>  $item->goods_sale,
                'goods_master_image'    =>  $tool->thumb($item->goods_master_image),
                'need'                  =>  !isset($item->collective) ? 0 : $item->collective->need,
                'order_comment_count'   =>  !isset($item->order_comment_count) ? 0 : $item->order_comment_count,
                'store_name'            =>  !isset($item->store) ? '' : $item->store->store_name,
            ];
        });
    }
}
