<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ArticleCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data'=>$this->collection->map(function ($item) {
                return [
                    'id'                        =>  $item->id,
                    'pid'                       =>  $item->pid,
                    'class_name'                =>  empty($item->class)?'':$item->class->name,
                    'belong_id'                 =>  $item->belong_id,
                    'name'                      =>  $item->name,
                    'content'                   =>  $item->content,
                    'click'                     =>  $item->click,
                    'created_at'                =>  $item->created_at->format('Y-m-d H:i:s'),
                ];
            }),
            'total'=>$this->total(), // 数据总数
            'last_page'=>$this->lastPage(), // 最后页面
            'per_page'=>$this->perPage(), // 每页数量
            'current_page'=>$this->currentPage(), // 当前页码
        ];
    }
}
