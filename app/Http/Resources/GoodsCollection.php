<?php

namespace App\Http\Resources;

use App\Qingwuit\Models\GoodsClass;
use App\Qingwuit\Services\ToolService;
use Illuminate\Http\Resources\Json\ResourceCollection;

class GoodsCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data'=>$this->collection->map(function ($item) {
                $tool = new ToolService();
                $goods_class = new GoodsClass();
                $goods_price = $item->goods_price;
                $goods_stock = $item->goods_stock;

                // 判断是否存在sku
                if (isset($item->goods_skus) && count($item->goods_skus)>0) {
                    $goods_stock = 0;
                    $skus = [];
                    $prices= []; // 得到最高和最低
                    foreach ($item->goods_skus as $v) {
                        if(empty($v['deleted_at'])) $goods_stock += $v['goods_stock'];
                        if(empty($v['deleted_at'])) $skus[] = $v;
                        if(empty($v['deleted_at'])) $prices[] = $v->goods_price;
                    }
                    sort($prices);
                    if(!empty($skus)){
                        if (count($skus)>1) {
                            $goods_price =$prices[0].' ~ '.$prices[count($prices)-1];
                        } else {
                            $goods_price =$prices[0];
                        }
                    }
                }
                return [
                    'id'                    =>  $item->id,
                    'goods_name'            =>  $item->goods_name,
                    'goods_price'           =>  $goods_price,
                    'goods_stock'           =>  $goods_stock,
                    'goods_sale'            =>  $item->goods_sale,
                    'goods_master_image'    =>  $tool->thumb($item->goods_master_image, 150),
                    'brand_name'            =>  $item->goods_brand->name??'',
                    'class_name'            =>  $item->goods_class->name??'',
                    // 'class_id'              =>  [$goods_class->withTrashed()->where('id',$item->goods_class->pid)->first()['pid'],$item->goods_class->pid,$item->goods_class->id],
                    'goods_no'              =>  $item->goods_no,
                    'goods_status'          =>  $item->goods_status,
                    'goods_verify'          =>  $item->goods_verify,
                    // 'is_recommend'          =>  $item->is_recommend, // 总后台可以不要
             
                    'created_at'            =>  $item->created_at->format('Y-m-d H:i:s'),
                    'updated_at'            =>  $item->updated_at->format('Y-m-d H:i:s'),
                ];
            }),
            'total'=>$this->total(), // 数据总数
            'last_page'=>$this->lastPage(), // 最后页面
            'per_page'=>$this->perPage(), // 每页数量
            'current_page'=>$this->currentPage(), // 当前页码
            // 统计
            // 'count'=>$goods_service->getCount(),
        ];
    }
}
