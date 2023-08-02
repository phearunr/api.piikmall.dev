<?php

namespace App\Http\Resources\DataSync\Goods;

use Illuminate\Http\Resources\Json\JsonResource;

class ImageGalleryResource extends JsonResource
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
            'id' => $this->goods_image_id,
            'image_url' => $this->goods_image,
            'is_default' => $this->is_default
        ];
    }
}
