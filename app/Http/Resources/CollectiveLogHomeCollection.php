<?php

namespace App\Http\Resources;

use App\Qingwuit\Services\ToolService;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CollectiveLogHomeCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $tool_service = new ToolService();
        return $this->collection->map(function ($item) use ($tool_service) {
            return [
                'id'                        =>  $item->id,
                'need'                      =>  $item->need,
                'nickname'                  =>  $tool_service->formatTrueName2($item->user->nickname),
                'avatar'                    =>  $item->user->avatar,
                'orders_count'              =>  $item->orders_count,
            ];
        });
    }
}
