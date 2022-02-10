<?php

namespace App\Http\Resources;

use App\Qingwuit\Services\ToolService;
use Illuminate\Http\Resources\Json\JsonResource;

class IntegralGoodsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $tool = new ToolService;
        return [
            'id'                    =>  $this->id,
            'goods_name'            =>  $this->goods_name,
            'goods_subname'         =>  $this->goods_subname,
            'goods_price'           =>  $this->goods_price,
            'goods_market_price'    =>  $this->goods_market_price,
            'goods_stock'           =>  $this->goods_stock,
            'cid'                   =>  $this->cid,
            'goods_sale'            =>  $this->goods_sale,
            'goods_master_image'    =>  $this->goods_master_image,
            'goods_content'         =>  $this->goods_content,
            'is_recommend'          =>  $this->is_recommend,
            'goods_status'          =>  empty($this->goods_status)?false:true,
            'goods_images'          =>  empty($this->goods_images)?[]:explode(',', $this->goods_images),
            'goods_images_thumb_150'=>  $tool->thumb_array(empty($this->goods_images)?[]:explode(',', $this->goods_images), 150),
            'goods_images_thumb_400'=>  $tool->thumb_array(empty($this->goods_images)?[]:explode(',', $this->goods_images), 400),
            'created_at'            =>  $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
