<?php

namespace App\Http\Resources\DataSync\Orders;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\DataSync\Orders\TranstatableResource;


class ItemsOrderDetailResource extends JsonResource
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
            'id' => $this->goods_id,
            'rec_id' => $this->rec_id,
            'name' => $this->goods_name,
            'translations' => TranstatableResource::collection($this->transtatable),
            'price' => $this->goods_price,
            'qautity' => $this->goods_num,
            'image' => $this->goods_image,
            'discount_price' => $this->discount_price,
            'discount_in_percentage' => $this->item_discount_in_percentage,
            'item_platform_voucher_discount' => $this->platform_voucher_discount,
            'item_store_voucher_discount' => $this->store_voucher_discount,
            'item_subtotal_price' => $this->item_sub_total,
            'refund_state' => $this->refund_state ?? 0,
            'refund_applicant' => $this->applicant ?? 0,
            'is_refunded' => ($this->payment_code == 'Cash on delivery'?'cancelled':$this->item_payment_status),
            'buyer_cancle_time' => $this->buyer_cancle_time,
           ];
    }
}
