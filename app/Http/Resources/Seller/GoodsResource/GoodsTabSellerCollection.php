<?php

namespace App\Http\Resources\Seller\GoodsResource;

use App\Models\GoodsClass;
use App\Services\GoodsService;
use App\Traits\HelperTrait;
use Illuminate\Http\Resources\Json\ResourceCollection;

class GoodsTabSellerCollection extends ResourceCollection
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
        $goods_service = new GoodsService();
        return [
            'data'=>$this->collection->map(function($item){
                
                $goods_class = new GoodsClass();
                $goods_price = $item->goods_price;
                $goods_stock = $item->goods_stock;

                // 判断是否存在sku
                if(isset($item->goods_skus) && count($item->goods_skus)>0){
                    $goods_stock = 0;
                    foreach($item->goods_skus as $v){
                        $goods_stock += $v['goods_stock'];
                    }
                    if(count($item->goods_skus)>1){
                        $goods_price = $item->goods_skus[0]['goods_price'].' ~ '.$item->goods_skus[count($item->goods_skus)-1]['goods_price'];
                    }else{
                        $goods_price = $item->goods_skus[0]['goods_price'];
                    }
                }
                return [
                    'id'                    =>  $item->id,
                    'goods_name'            =>  $item->goods_name,
                    'goods_price'           =>  $goods_price,
                    'goods_stock'           =>  $goods_stock,
                    'goods_sale'            =>  $item->goods_sale,
                    'goods_master_image'    =>  $this->thumb($item->goods_master_image,150),
                    'brand_name'            =>  $item->goods_brand->name,
                    'class_name'            =>  $item->goods_class->name,
                    'class_id'              =>  [$goods_class->withTrashed()->where('id',$item->goods_class->pid)->first()['pid'],$item->goods_class->pid,$item->goods_class->id],
                    'goods_no'              =>  $item->goods_no,
                    'goods_status'          =>  $item->goods_status,
                    'goods_verify'          =>  $item->goods_verify,
                    'is_recommend'          =>  $item->is_recommend, // 总后台可以不要
             
                    'created_at'            =>  $item->created_at->format('Y-m-d H:i:s'),
                    'updated_at'            =>  $item->updated_at->format('Y-m-d H:i:s'),
                ];
            }),
            'total'=>$this->total(), // 数据总数
            'per_page'=>$this->perPage(), // 每页数量
            'current_page'=>$this->currentPage(), // 当前页码
            // 统计
            'count'=>$goods_service->getCount(),
        ];
    }
}
