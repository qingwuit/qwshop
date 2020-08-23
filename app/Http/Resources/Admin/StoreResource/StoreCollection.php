<?php

namespace App\Http\Resources\Admin\StoreResource;

use App\Models\Store;
use Illuminate\Http\Resources\Json\ResourceCollection;

class StoreCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $store_model = new Store();
        $pass = $store_model->where('store_verify',3)->count();
        $wait = $store_model->where('store_verify',2)->count();
        $write = $store_model->where('store_verify',1)->count();
        $refuse = $store_model->where('store_verify',0)->count();
        return [
            'data'=>$this->collection->map(function($item){
                $verify_cn = '非法入驻';
                switch($item->store_verify){
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
                    'id'                            =>  $item->id,
                    'store_name'                    =>  $item->store_name,
                    'store_logo'                    =>  $item->store_logo,
                    'store_company_name'            =>  $item->store_company_name,
                    'legal_person'                  =>  $item->legal_person,
                    'store_phone'                   =>  $item->store_phone,
                    'store_money'                   =>  $item->store_money,
                    'store_status'                  =>  $item->store_status,
                    'store_verify'                  =>  $item->store_verify,
                    'store_verify_cn'               =>  $verify_cn,
                    'store_refuse_info'             =>  $item->store_refuse_info??'无原因',
                    'created_at'                    =>  $item->created_at->format('Y-m-d H:i:s'),
                    'updated_at'                    =>  $item->updated_at->format('Y-m-d H:i:s'),
                ];
            }),
            'total'=>$this->total(), // 数据总数
            'per_page'=>$this->perPage(), // 每页数量
            'current_page'=>$this->currentPage(), // 当前页码
            'count'=>[
                'pass'=>$pass, // 通过审核
                'wait'=>$wait, // 等待审核
                'write'=>$write, // 填写资料
                'refuse'=>$refuse, // 拒绝审核
            ]
        ];
    }
}
