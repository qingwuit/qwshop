<?php

namespace App\Http\Resources\Admin\StoreResource;

use Illuminate\Http\Resources\Json\JsonResource;

class StoreResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $verify_cn = '非法入驻';
        switch($this->store_verify){
            case 0:
                $verify_cn = '审核失败';
                break;
            case 1:
                $verify_cn = '填写入驻资料';
                break;
            case 2:
                $verify_cn = '等待审核';
                break;
            case 3:
                $verify_cn = '审核通过';
                break;
        }
        return [
            'id'                            =>  $this->id,
            'store_name'                    =>  $this->store_name,
            'store_logo'                    =>  $this->store_logo,
            'store_description'             =>  $this->store_description,
            'store_mobile'                  =>  $this->store_mobile,
            'area_info'                     =>  $this->area_info,
            'store_address'                 =>  $this->store_address,
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
            'store_status'                  =>  $this->store_status,
            'store_verify'                  =>  $this->store_verify,
            'store_refuse_info'             =>  $this->store_refuse_info,
            'store_company_name'            =>  $this->store_company_name,
            'legal_person'                  =>  $this->legal_person,
            'store_phone'                   =>  $this->store_phone,
            'store_status'                  =>  $this->store_status,
            'store_verify'                  =>  $this->store_verify,
            'store_verify_cn'               =>  $verify_cn,
            'store_refuse_info'             =>  $this->store_refuse_info??'无原因',
            'created_at'                    =>  $this->created_at->format('Y-m-d H:i:s'),
            'updated_at'                    =>  $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
