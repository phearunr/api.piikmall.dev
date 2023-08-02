<?php

namespace App\Models\DataSync;

use App\Models\DataSync\Goods;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order_goods extends Model
{
    use HasFactory;
    protected $connection = 'second_mysql';
    protected $table = 'order_goods';
   
    public function goods()
    {
        return $this->belongsTo(Goods::class, 'goods_id', 'goods_id')
            ->with(['translations' => function($q){
                $q->select([
                    'goods_id',
                    'language_id',
                    'goods_name',
                    'goods_spec'
                ]);
            }])
            ->select([
                'goods_id', 'goods_price','goods_image'
            ]);
    }
    public function transtatable(){
      return $this->hasMany(Goods_lang::class, 'goods_id', 'goods_id')
        ->select(
            'goods_id',
            'goods_name',
            'language_id',
            'goods_spec'
        );
    }

    public function refund_return()
    {
        return  $this->hasOne(Refund_return::class,'order_id', 'order_id')
            ->select(
                'order_id',
                'order_goods_id',
                'refund_amount',
                DB::raw('ifnull(refund_state,0) as is_refunded')
            );
    }
    
    public function getGoodsImageAttribute($value)
    {
        return empty($value) ? '' : config('app.image_url') . $value;
    }
}
