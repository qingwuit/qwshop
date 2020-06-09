<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PermissionCollection extends ResourceCollection
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
            'data'=>$this->collection,
            'tatal'=>$this->count(), // 总页码
            'per_page'=>$this->count(), // 每页数量
            'current_page'=>$this->count(), // 当前页码
        ];
    }
}
