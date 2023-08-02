<?php

namespace App\Http\Resources\DataSync\Orders;

use Illuminate\Http\Resources\Json\JsonResource;

class AdditionActionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
  
        $_arrgs_button = [];
        switch ($this['order_status']) {
            case 10:
                $_arrgs_button = [
                    'button_cancel' => $this['confirm_cash_pay_time'] > 0 ? true: false,
                    'button_confirm_delivery' => $this['confirm_cash_pay_time'] > 0 ? true: false,
                    'button_refund' => false,
                    'button_change_price' => ($this['payment_code']  == 'Cash on delivery' ? true: false),
                    'button_print' => false
                ];
                break;
            case 20:
                $_arrgs_button = [
                    'button_cancel' => ($this['payment_code'] == 'Cash on delivery' ? true: false),
                    'button_confirm_delivery' => true,
                    'button_refund' => ($this['payment_code'] != 'Cash on delivery' ? true: false),
                    'button_change_price' => ($this['payment_code'] == 'cash' ? true: false),
                    'button_print' => false
                ];
                break;
            case 30:
                $_arrgs_button = [
                    'button_cancel' => false,
                    'button_confirm_delivery' => false,
                    'button_refund' => false,
                    'button_change_price' => false,
                    'button_print' => true
                ];
                break;
            case 35:
                $_arrgs_button = [
                    'button_cancel' => false,
                    'button_confirm_delivery' => false,
                    'button_refund' => false,
                    'button_change_price' => false,
                    'button_print' => false
                ];
                break;
            case 40:
                $_arrgs_button = [
                    'button_cancel' => false,
                    'button_confirm_delivery' => false,
                    'button_refund' => false,
                    'button_change_price' => false,
                    'button_print' => false
                ];
                break;
            default:
                $_arrgs_button = [
                    'button_cancel' => false,
                    'button_confirm_delivery' => false,
                    'button_refund' => false,
                    'button_change_price' => false,
                    'button_print' => false
                ];
        }
      return $_arrgs_button;
     
    }
}
