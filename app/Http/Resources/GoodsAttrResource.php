<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GoodsAttrResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'                    =>  $this->id,
            'name'                  =>  $this->name,
            'specs'                 =>  $this->specs->map(function ($specsItem) {
                return $specsItem->name;
            }),
            'created_at'            =>  empty($this->created_at)?now()->format('Y-m-d H:i:s'):$this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
