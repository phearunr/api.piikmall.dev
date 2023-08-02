<?php

namespace App\Http\Resources\DataSync\Goods;

use Illuminate\Http\Resources\Json\JsonResource;

class TranslatableResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'en' => $this[0]['goods_name'],
            'cn' => $this[1]['goods_name'],
            'kh' => $this[2]['goods_name'],
        ];
    }
}
