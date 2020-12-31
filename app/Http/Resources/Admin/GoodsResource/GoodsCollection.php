<?php

namespace App\Http\Resources\Admin\GoodsResource;

use Illuminate\Http\Resources\Json\ResourceCollection;

class GoodsCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data'=>$this->collection->map(function($item){
                return [
                    'id'                    =>  $item->id,
                    'brand_name'            =>  $item->goods_brand->map(function($goodsItem){
                                                    return $goodsItem->name;
                                                }),
                    'class_name'            =>  $item->goods_class->map(function($goodsItem){
                                                    return $goodsItem->name;
                                                }),
                    'goods_no'              =>  $item->goods_no,
                    'goods_status'          =>  $item->goods_status,
                    'goods_verify'          =>  $item->goods_verify,
                    'is_recommend'          =>  $item->is_recommend, // 总后台可以不要
                    'is_matser'             =>  $item->is_matser,
             
                    'created_at'            =>  $item->created_at->format('Y-m-d H:i:s'),
                    'updated_at'            =>  $item->updated_at->format('Y-m-d H:i:s'),
                ];
            }),
            'total'=>$this->total(), // 数据总数
            'per_page'=>$this->perPage(), // 每页数量
            'current_page'=>$this->currentPage(), // 当前页码
        ];
    }
}
