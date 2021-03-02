<?php

namespace App\Http\Resources\Home\UserCheckResource;

use App\Services\ToolService;
use Illuminate\Http\Resources\Json\JsonResource;

class UserCheckResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $tool_service = new ToolService;
        return [
            'name'              =>  $tool_service->formatTrueName($this->name),
            'card_no'           =>  $tool_service->formatIdCard($this->card_no),
            'bank_no'           =>  $tool_service->formatBankCardNo($this->bank_no),
            'bank_name'         =>  $this->bank_name,
        ];
    }
}
