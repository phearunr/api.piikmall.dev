<?php

namespace App\Http\Resources\DataSync\Goods;

use Illuminate\Http\Resources\Json\JsonResource;

class GoodsCommonResource extends JsonResource
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
            'id' => $this->goods_commonid,
            'state' => $this->goods_state,
            'verify' => $this->goods_verify,
            'name' => $this->translations[0]['goods_name'] ?? '',
            'price' => $this->goods_price,
            'stock' => $this->goods_default['goods_storage'] ?? 0,
            'category' => $this->category->translations[0]['gc_name'] ?? '',
            'image' => $this->goods_image
        ];
    }
}
