<?php

namespace App\Models\DataSync;

use Illuminate\Support\Facades\DB;
use App\Models\DataSync\Order_goods;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Helper\Asset;


class Order extends Model
{
    use HasFactory;
    protected $connection = 'second_mysql';
    protected $table = 'order';
    protected $primaryKey = 'order_id';

    protected $hidden = [
        'order_state2',
        'order_cancel_type',
        'chop_success_time',
        'purchase_time',
        'store_points_offset',
        'platform_points_offset',
        'store_points_offset_amount',
        'platform_points_offset_amount',
        'first_comm',
        'second_comm',
        'three_comm',
        'transfer_remark',
        'confirm_address',
        'is_third',
        'print_status',
        'is_in_storage_all',
        'oversea_discount_amount',
        'warehouse_id_t',
        'warehouse_id_j',
        'shipping_code',
        'hotel_checked',
        'picktype',
        'pay_voucher_img',
        'rcb_amount',
        'seller_handle_reminder_flag',
        'sign_time',
        'sign_confirmer',
        'handling_fee',
        'handling_fee_ratio',
        'bill_id',
        'bill_state',
        'bill_store_confirm_time',
        'bill_system_check_time',
        'bill_success_time',
    ];

    protected $casts = [];

    public function scopeForm(Builder $builder)
    {
        return $builder->select([
            'order_id',
            'order_sn',
            'add_time',
            'order_state',
            'store_id',
            'refund_amount',
            'delivery_fee',
            'delivery_distance',
            'shipping_fee',
            'goods_amount',
            'order_amount',
            'pd_amount',
            'original_order_price_detail',
            'confirm_cash_pay_time',
            DB::raw('
                CASE 
                WHEN  payment_code = "cash" THEN "Cash on delivery" 
                WHEN  payment_code = "predeposit" THEN "Wallet"
                when  payment_code = "abapay" then "ABA Pay"
                ELSE  payment_code 
                END AS payment_code
            ')
        ])
        ->selectRaw('
        IFNULL((
		    SELECT
			IF(ago.team_ok_time > 0 AND ago.team_status = 3, TRUE, FALSE) AS team_ok_time
			FROM (
			SELECT
				spo.team_order_sn AS order_sn,
				spo.team_status AS order_status,
				spo.team_pay_time AS pay_time,
				spg.group_end_time AS activity_end_time,
				spt.team_closing_date,
				spt.team_ok_time,
				spt.team_id,
				spt.team_status,
				spt.team_people_number AS max_people_num,
				spt.team_partake_people_number AS partake_people_num
			FROM
				aiteshop_spellgroup_order spo
				JOIN aiteshop_spellgroup_team spt ON spo.team_id = spt.team_id
				JOIN aiteshop_spellgroup spg ON spo.team_group_id = spg.group_id
			WHERE
				spo.team_order_sn = aiteshop_order.order_sn) AS ago
			),NULL) AS items_is_group_buying
        ')
        ->with([
                'order_details' => function ($query) {
                    $query->with('transtatable')
                        ->leftJoin('refund_return', function ($join) {
                            $join->on('order_goods.rec_id', '=', 'refund_return.order_goods_id');
                        })
                        ->select(
                            'order_goods.goods_id',
                            'order_goods.order_id',
                            'order_goods.rec_id',
                            'order_goods.goods_image',
                            'order_goods.goods_name',
                            'order_goods.goods_num',
                            'order_goods.goods_price',
                            'order_goods.discount_price',
                            'order_goods.store_voucher_discount',
                            'order_goods.platform_voucher_discount',
                            'order_goods.goods_pay_price',
                            'order_goods.goods_pay_price_s',
                            'order_goods.exchange_rate',
                            'order_goods.buyer_cancle_time',
                            'refund_return.refund_amount',
                            'refund_return.refund_state',
                            'refund_return.applicant',
                            DB::raw('
                            CASE 
                                WHEN ifnull(aiteshop_refund_return.refund_state,0) = 1 THEN "refunding"
                                WHEN ifnull(aiteshop_refund_return.refund_state,0) = 2 THEN "refunding"
                                WHEN ifnull(aiteshop_refund_return.refund_state,0) = 3 THEN "refunded"
                                WHEN aiteshop_order_goods.buyer_cancle_time > 0 THEN "cancelled"
                                ELSE null
                            END item_payment_status
                        '),
                        DB::raw('if(aiteshop_order_goods.buyer_cancle_time=0,((aiteshop_order_goods.goods_num * aiteshop_order_goods.goods_price) - aiteshop_order_goods.discount_price), 0) AS item_sub_total'),
                            DB::raw('concat(round(100 - (100 * ((aiteshop_order_goods.goods_price - aiteshop_order_goods.discount_price) / aiteshop_order_goods.goods_price)), 2), "%") as item_discount_in_percentage')
                        );
                },
                'logistics_info',
                'order_summary.store_address',
                'delivery_man'
            ])
            ->withSum('order_details', 'discount_price', function ($q) {
                $q->where('buyer_cancle_time', '>', 0);
                $q->join('refund_return', 'refund_return.order_goods_id', '<>', 'order_goods.rec_id');
            })
            ->withSum('order_details', 'goods_num');
    }
    public function order_details()
    {
        return $this->hasMany(Order_goods::class, 'order_id', 'order_id')
            ->select(
                'order_id',
                'rec_id',
                'goods_id',
                'goods_name',
                'goods_price',
                'goods_num',
                'goods_image',
                'discount_price',
                'goods_pay_price',
                'platform_voucher_discount',
                'store_voucher_discount',
                DB::raw('if(aiteshop_order_goods.buyer_cancle_time=0,((aiteshop_order_goods.goods_num * aiteshop_order_goods.goods_price) - aiteshop_order_goods.discount_price), 0) AS item_sub_total'),
                DB::raw('(goods_price * goods_num) - (discount_price * goods_num) as item_subtotal_price'),
            );
    }

    public function logistics_info()
    {
        return $this->belongsTo(Store::class, 'store_id')
            ->select(
                'store_id',
                'store_name',
                'store_phone',
                'store_address'
            );
    }

    public function order_summary()
    {
        return $this->hasOne(Order_common::class, 'order_id', 'order_id')
            ->select(
                'order_id',
                'reciver_name',
                'reciver_info',
                'reciver_points',
                'platform_coupon_price',
                'store_coupon_price',
                'daddress_id',
                'reciver_area_id',
                'store_id'
            );
    }

    public function delivery_man()
    {
        return $this->hasOne(Delivery_staff_order::class, 'order_id', 'order_id')
            ->select(
                'order_id',
                'pickup_name',
                'pickup_phone',
                'package_sn',
                'business_name'
            );
    }
    public function scopeTerm(Builder $builder, $term)
    {
        if (!is_null($term)) {
            $builder->where('order_sn', $term);
            $builder->orWhereHas('order_details', function ($q) use ($term) {
                $q->where('goods_name', 'like', '%' . $term . '%');
            });
        }
        return $builder;
    }
    public function scopeOrderBy(Builder $builder, $data =[])
    {
        switch ($data['status_id']) {
            case 30:
                $field_nane = 'delay_time';
                break;
            case 20:
                $field_nane = 'add_time';
                break;
            case 10:
                if($this->confirm_cash_pay_time == 0){
                    $field_nane = 'add_time';
                }
                break;
            default:
                $field_nane = 'order_id';
                
        }

        $builder->orderByDesc($field_nane);
        return $builder;
    }
}
