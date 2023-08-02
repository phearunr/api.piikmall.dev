<?php

namespace App\Http\Resources\DataSync\Brand;

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
           'id' => $this->brand_id,
           'name' => $this->brand_name
       ];
    }
}
