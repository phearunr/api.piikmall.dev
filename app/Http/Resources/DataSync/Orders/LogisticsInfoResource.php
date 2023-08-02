<?php

namespace App\Http\Resources\DataSync\Orders;

use Illuminate\Http\Resources\Json\JsonResource;

class LogisticsInfoResource extends JsonResource
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
        'name' => $this->store_name,
        'phone' => $this->store_phone,
        'address' => $this->store_address,
      ];
    }
}
