<?php

namespace App\Http\Resources\DataSync\Goods;
use App\Helper\Asset;
use Illuminate\Http\Resources\Json\JsonResource;

class QuickEditResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return[
            'id' => $this->goods_id,
            'price' => $this->goods_price,
            'stock_quantity' => $this->goods_storage,
            'stock_warning' => $this->goods_storage_alarm,
            'attribute_id' => Asset::attribute_value_lang($this->goods_spec),
            'attribute' => $this->goods_spec,
            'is_deleted' => $this->is_deleted,
            'image' => $this->goods_image ?? ''   
        ];
    }
}
