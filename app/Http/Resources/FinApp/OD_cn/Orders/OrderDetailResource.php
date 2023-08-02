<?php

namespace App\Http\Resources\FinApp\OD_cn\Orders;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailResource extends JsonResource
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
            "order_id"=> $this->order_id,
            "order_sn" => $this->order_sn,
            "pay_sn" => $this->pay_sn,
            "apv" =>  $this->apv,
            "bank_ref" => $this->bank_ref,
            "store" => $this->store_name,
            "buyer_id" => $this->buyer_id,
            "buyer_name" => $this->buyer_name,
            "ordering_time" => $this->add_time,
            "payment_time" => $this->payment_time,
            "order_status" => $this->order_state,
            "total_original_goods_price" => $this->original_order_price_detail['goods_amount'],
            "adjustment_amount" => $this->original_order_price_detail['order_amount'],
            "adjust_price_remark" => $this->original_order_price_detail['remarks'],
            "store_discount" => 0,
            "store_voucher" => $this->store_coupon_price,
            "total_goods_price" => $this->order_amount,
            "domestic_freight" => $this->shipping_fee,
            "delivery_fee" => $this->delivery_fee,
            "platform_voucher" => $this->platform_voucher_price,
            "user_payment" => $this->order_amount,
            "user_bear_cost" => "0",
            "user_final_payment" => $this->order_amount,
            "aba_pay" => 0,
            "khqr" => "0",
            "debit_credit_card" => "0",
            "pipay" => "0",
            "wallet" => "0",
            "payment_code" => $this->payment_code,
            "partner_service_charge" => "0",
            "platform_received" => 0
        ];

      // return parent::toArray($request);
    }
}
