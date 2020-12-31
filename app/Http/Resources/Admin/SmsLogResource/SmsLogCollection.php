<?php

namespace App\Http\Resources\Admin\SmsLogResource;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SmsLogCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data'=>$this->collection->map(function($item){
                return [
                    'id'                    =>  $item->id,
                    'name'                  =>  $item->name,
                    'phone'                 =>  $item->phone,
                    'content'               =>  $item->content,
                    'status'                =>  $item->status==0?false:true,
                    'error_msg'             =>  empty($item->error_msg)?'-':$item->error_msg,
                    'created_at'            =>  $item->created_at->format('Y-m-d H:i:s'),
                    'updated_at'            =>  $item->updated_at->format('Y-m-d H:i:s'),
                ];
            }),
            'total'=>$this->total(), // 数据总数
            'per_page'=>$this->perPage(), // 每页数量
            'current_page'=>$this->currentPage(), // 当前页码
        ];
    }
}
