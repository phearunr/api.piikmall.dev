<?php

namespace App\Models\FinApp\OD_cn;

use App\Casts\BillCycle;
use App\Casts\OrderType;
use App\Casts\OrderStatus;
use App\Casts\PaymentCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Contracts\Database\Query\Builder;

class Order extends Model
{
    use HasFactory;
    protected $connection = 'live_mysql';
    protected $table = 'order';
    protected $primaryKey = 'order_id';

    protected $casts =[
        'order_state' => OrderStatus::class,
        'order_type' => OrderType::class,
        'payment_code' => PaymentCode::class,
        'bill_cycle' => BillCycle::class ,
        'original_order_price_detail' => 'json',
    ];

    // protected $dates = [
    //     'seen_at',
    // ];

    // public function getOrderTypeAttribute($value):string
    // {
    //     foreach($items = Constant::ORDER_TYPE as $key => $item ){
    //         if($value == $key) return $value=$item;
    //     }
    //     return $value;

    // }

    public function scopeForm($query)
    {
        return $query->join('order_common', 'order.order_id', '=', 'order_common.order_id')
        ->join('order_pay', 'order.pay_sn', '=', 'order_pay.pay_sn')
        ->join('store_extend', 'order.store_id', '=', 'store_extend.store_id')
        ->select([
            'order.order_id',
            'order.order_sn',
            'order_pay.pay_sn',
            'order_pay.apv',
            'order_pay.bank_ref',
            'order.buyer_id',
            'order.buyer_name',
            'order.store_id',
            'order.store_name',
            'store_extend.bill_cycle',
            'order.add_time',
            'order.payment_time',
            'order.delivery_time',
            'order.finnshed_time',
            'order.goods_amount',
            'order.delivery_fee',
            'order.refund_state',
            'order.shipping_fee',
            'order.refund_amount',
            'order.order_type',
            'order.order_state',
            'order.payment_code',
            'order.delete_state',
            'order_common.store_coupon_price',
            'order_common.platform_coupon_price',
            'order_common.platform_voucher_price',
            'order.order_amount',
            'order.original_order_price_detail'
        ]);
    }
}
