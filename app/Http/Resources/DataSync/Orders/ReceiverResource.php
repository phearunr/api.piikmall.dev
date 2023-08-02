<?php

namespace App\Http\Resources\DataSync\Orders;

use Illuminate\Http\Resources\Json\JsonResource;

class ReceiverResource extends JsonResource
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
            'name' => $this->reciver_name,
            'phone' =>$this->reciver_info['phone'] ?? null,
            'reciver_area_id' => $this->reciver_area_id,
            'address' => $this->reciver_info['address'] ?? null,
            'street' => $this->reciver_info['street'] ?? null,
            'area' => $this->reciver_info['area'] ?? null,
            'daddress_id' => $this->daddress_id == 0 ? $this->store_address['address_id'] : $this->daddress_id
        ];
    }
}
