<?php

namespace App\Http\Resources\Admin\AdminResource;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AdminListCollection extends ResourceCollection
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
                    'username'              =>  $item->username,
                    'nickname'              =>  $item->nickname,
                    'avatar'                =>  $item->avatar,
                    'ip'                    =>  $item->ip,
                    'role_name'             =>  $item->roles->map(function($roleItem){return $roleItem->name;})->first(),
                    'login_time'            =>  $item->login_time,
                    'last_login_time'       =>  $item->last_login_time,
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
