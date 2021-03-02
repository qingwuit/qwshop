<?php

namespace App\Http\Resources\Seller\DistributionResource;

use App\Traits\HelperTrait;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DistributionCollection extends ResourceCollection
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
                    'id'                    =>  $item->id,
                    'lev_1'                 =>  $item->lev_1,
                    'lev_2'                 =>  $item->lev_2,
                    'lev_3'                 =>  $item->lev_3,
                    'goods_id'              =>  $item->goods_id,
                    'total_commission'      =>  empty($item->total_commission)?0.00:$item->total_commission,
                    'total_money'           =>  empty($item->total_money)?0.00:$item->total_money,
                    'created_at'            =>  $item->created_at->format('Y-m-d H:i:s'),
                    'updated_at'            =>  $item->updated_at->format('Y-m-d H:i:s'),
                    'goods'                 =>  [
                                                    'goods_name'                =>  $item->goods->goods_name??__('goods.goods_not_found'),
                                                    'goods_master_image'        =>  empty($item->goods)?'':$this->thumb($item->goods->goods_master_image,150),
                                                ],
                ];
            }),
            'total'=>$this->total(), // 数据总数
            'per_page'=>$this->perPage(), // 每页数量
            'current_page'=>$this->currentPage(), // 当前页码
        ];
    }
}
