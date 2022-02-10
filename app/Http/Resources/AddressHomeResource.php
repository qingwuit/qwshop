<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressHomeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $area = [];
        if (!empty($this->province_id)) {
            $area[] = $this->province_id;
        }
        if (!empty($this->city_id)) {
            $area[] = $this->city_id;
        }
        if (!empty($this->region_id)) {
            $area[] = $this->region_id;
        }
        return [
            'id'                    =>  $this->id,
            'receive_name'          =>  $this->receive_name,
            'receive_tel'           =>  $this->receive_tel,
            'area_info'             =>  $this->area_info,
            'area'                  =>  $area,
            'address'               =>  $this->address,
            'province_id'           =>  $this->province_id,
            'city_id'               =>  $this->city_id,
            'region_id'             =>  $this->region_id,
            'is_default'            =>  $this->is_default==1?true:false,
            'created_at'            =>  $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
