<?php

namespace App\Http\Resources;

use App\Qingwuit\Services\ToolService;
use Illuminate\Http\Resources\Json\ResourceCollection;

class IntegralGoodsCollection extends ResourceCollection
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
        return [
            'data'=>$this->collection->map(function ($item) use ($tool) {
                return [
                    'id'                    =>  $item->id,
                    'goods_name'            =>  $item->goods_name,
                    'goods_subname'         =>  $item->goods_subname,
                    'goods_price'           =>  $item->goods_price,
                    'goods_market_price'    =>  $item->goods_market_price,
                    'goods_stock'           =>  $item->goods_stock,
                    'class_name'            =>  empty($item->class)?'-':$item->class->name,
                    'goods_sale'            =>  $item->goods_sale,
                    'goods_status'          =>  $item->goods_status,
                    'is_recommend'          =>  $item->is_recommend,
                    'goods_master_image'    =>  $tool->thumb($item->goods_master_image),
                    'created_at'            =>  $item->created_at->format('Y-m-d H:i:s'),
                ];
            }),
            'total'=>$this->total(), // 数据总数
            'last_page'=>$this->lastPage(), // 最后页面
            'per_page'=>$this->perPage(), // 每页数量
            'current_page'=>$this->currentPage(), // 当前页码
        ];
    }
}
