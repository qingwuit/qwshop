<?php

namespace App\Http\Resources\Seller\StoreResource;

use Illuminate\Http\Resources\Json\JsonResource;

class StoreConfigResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'store_name'                =>  $this->store_name,
            'store_logo'                =>  $this->store_logo,
            'store_face_image'          =>  $this->store_face_image,
            'store_mobile'              =>  $this->store_mobile,
            'store_description'         =>  $this->store_description,
            'store_slide'               =>  empty($this->store_slide)?[]:explode(',',$this->store_slide),
            'store_mobile_slide'        =>  empty($this->store_mobile_slide)?[]:explode(',',$this->store_mobile_slide),
            'province_id'               =>  $this->province_id,
            'city_id'                   =>  $this->city_id,
            'region_id'                 =>  $this->region_id,
            'store_lat'                 =>  $this->store_lat,
            'store_lng'                 =>  $this->store_lng,
            'store_address'             =>  $this->store_address,
            'store_money'               =>  $this->store_money,
            'store_frozen_money'        =>  $this->store_frozen_money,
            'area_id'                   =>  [$this->province_id,$this->city_id,$this->region_id],
            'after_sale_service'        =>  empty($this->after_sale_service)?'':$this->after_sale_service,
        ];
    }
}
