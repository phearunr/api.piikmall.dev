<?php

namespace App\Http\Resources\DataSync\Orders;

use App\Helper\Asset;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\DataSync\Orders\ReceiverResource;
use App\Http\Resources\DataSync\Orders\LogisticsInfoResource;
use App\Http\Resources\DataSync\Orders\AdditionActionResource;
use App\Http\Resources\DataSync\Orders\ItemsOrderDetailResource;

class OrderResource extends JsonResource
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
            'order_id' => $this->order_id,
            'order_sn' => $this->order_sn,
            'order_date' =>  $this->add_time,
            'order_state' => $this->order_state,
            'store_id' => $this->store_id,
            'custom_currency' => '$',
            'items_is_group_buying' => $this->items_is_group_buying,
            'items_total_price' => $this->goods_amount  ,
            'items_total_quantity' => $this->order_details_sum_goods_num,
            'items_discount_price' => $this->order_details_sum_discount_price,
            'items_refund_price' => $this->refund_amount, 
            'items_ajusted_price' => $this->original_order_price_detail,
            'confirm_cash_pay_time' => $this->confirm_cash_pay_time,
            'items_order_detail' => ItemsOrderDetailResource::collection( 
                $this->order_details, ['payment_code', $this->payment_code]
            ),
       
            'delivery_fee' => $this->delivery_fee,
            'payment_method' => $this->payment_code,
            'grand_total' => $this->order_amount,

            'payment' => array(
                'total_price' => $this->goods_amount ?? 0,
                'total_items' => $this->order_details_sum_goods_num,
                'items_total_discount' => $this->order_details_sum_discount_price,
                // 'discount_in_percentage' => Asset::discount_in_percentage(
                //     $this->goods_amount, 
                //     $this->order_details_sum_discount_price, 
                //     $this->original_order_price_detail 
                // ),
                'store_voucher' => $this->order_summary->store_coupon_price,
                'platform_voucher' => $this->order_summary->platform_coupon_price,
            ),
            
            'logistics_info' => new LogisticsInfoResource($this->logistics_info),
            'receiver' => new ReceiverResource($this->order_summary), 
            'addition_actions' => new AdditionActionResource([
                'order_status' => $this->order_state,
                'payment_code' => $this->payment_code,
                'confirm_cash_pay_time' => $this->confirm_cash_pay_time
            ])
       ];
    }
}
