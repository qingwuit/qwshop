<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class FreightAllCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($item) {
            return [
                'id'                   =>  $item->id,
                'store_id'             =>  $item->store_id,
                'name'                 =>  $item->name,
                'f_weight'             =>  $item->f_weight,
                'f_price'              =>  $item->f_price,
                'o_weight'             =>  $item->o_weight,
                'o_price'              =>  $item->o_price,
                'area_id'              =>  empty($item->area_id)?[]:explode(',',$item->area_id),
                'area_name'            =>  $item->area_name,
                'is_type'              =>  $item->is_type,
                'created_at'           =>  $item->created_at->format('Y-m-d H:i:s'),
            ];
        });
    }
}
