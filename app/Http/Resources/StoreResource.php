<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class StoreResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $stroeClass = DB::table('store_classes')->where('store_id', $this->id)->first();
        $classId = [];
        if ($stroeClass && !empty($stroeClass->class_id)) {
            $classIds = json_decode($stroeClass->class_id, true);
            foreach ($classIds as $classKey => $classItem) {
                foreach ($classItem as $k => $v) {
                    $classId[$classKey][$k] = intval($v);
                }
            }
        }
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
            'id'                            =>  $this->id,
            'store_name'                    =>  $this->store_name,
            'store_logo'                    =>  $this->store_logo,
            'store_description'             =>  $this->store_description,
            'store_mobile'                  =>  $this->store_mobile,
            'area_info'                     =>  $this->area_info,
            'area'                          =>  $area,
            'class_id'                      =>  $classId,
            'store_slide'                   =>  !empty($this->store_slide) ? explode(',', $this->store_slide) : [],
            'store_mobile_slide'            =>  !empty($this->store_mobile_slide) ? explode(',', $this->store_mobile_slide) : [],
            'store_address'                 =>  $this->store_address,
            'store_lat'                     =>  $this->store_lat,
            'store_lng'                     =>  $this->store_lng,
            'store_face_image'              =>  $this->store_face_image,
            'business_license'              =>  $this->business_license,
            'business_license_no'           =>  $this->business_license_no,
            'legal_person'                  =>  $this->legal_person,
            'store_phone'                   =>  $this->store_phone,
            'id_card_no'                    =>  $this->id_card_no,
            'id_card_t'                     =>  $this->id_card_t,
            'id_card_b'                     =>  $this->id_card_b,
            'emergency_contact'             =>  $this->emergency_contact,
            'emergency_contact_phone'       =>  $this->emergency_contact_phone,
            'store_money'                   =>  $this->store_money,
            'store_frozen_money'            =>  $this->store_frozen_money,
            'store_status'                  =>  $this->store_status,
            'store_verify'                  =>  $this->store_verify,
            'store_refuse_info'             =>  $this->store_refuse_info,
            'store_company_name'            =>  $this->store_company_name,
            'legal_person'                  =>  $this->legal_person,
            'store_phone'                   =>  $this->store_phone,
            'after_sale_service'            =>  $this->after_sale_service,
            'store_status'                  =>  $this->store_status,
            'store_verify'                  =>  $this->store_verify,
            'store_refuse_info'             =>  $this->store_refuse_info ?? '无原因',
            'created_at'                    =>  $this->created_at->format('Y-m-d H:i:s'),
            'updated_at'                    =>  $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
