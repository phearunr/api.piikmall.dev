<?php

namespace App\Http\Resources\DataSync\GoodsClass;

use Illuminate\Http\Resources\Json\JsonResource;

class BreadcrumbKeyRenameResource extends JsonResource
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
            'id' => $this->gc_id,
            'name' => $this->gc_name 
        ];
    }
}
