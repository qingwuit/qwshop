<?php

namespace App\Http\Resources\Seller\GoodsResource;

use App\Models\GoodsClass;
use App\Services\GoodsService;
use Illuminate\Http\Resources\Json\ResourceCollection;

class GoodsTabSellerCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $goods_service = new GoodsService();
        return [
            'data'=>$this->collection->map(function($item){
                $goods_class = new GoodsClass();
                return [
                    'id'                    =>  $item->id,
                    'goods_name'            =>  $item->goods_name,
                    'goods_master_image'    =>  $item->goods_master_image,
                    'brand_name'            =>  $item->goods_brand->name,
                    'class_name'            =>  $item->goods_class->name,
                    'class_id'              =>  [$goods_class->where('id',$item->goods_class->pid)->first()['pid'],$item->goods_class->pid,$item->goods_class->id],
                    'goods_no'              =>  $item->goods_no,
                    'goods_status'          =>  $item->goods_status,
                    'goods_verify'          =>  $item->goods_verify,
                    'is_recommend'          =>  $item->is_recommend, // 总后台可以不要
             
                    'created_at'            =>  $item->created_at->format('Y-m-d H:i:s'),
                    'updated_at'            =>  $item->updated_at->format('Y-m-d H:i:s'),
                ];
            }),
            'tatal'=>$this->total(), // 总页码
            'per_page'=>$this->perPage(), // 每页数量
            'current_page'=>$this->currentPage(), // 当前页码
            // 统计
            'count'=>$goods_service->get_count(),
        ];
    }
}
