<?php

namespace App\Models\DataSync;

use Illuminate\Database\Eloquent\Model;
use App\Models\DataSync\GoodsTranslation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;


class Goods extends Model implements TranslatableContract
{
    use HasFactory;
    use Translatable;
    protected $connection = 'second_mysql';
    //protected $connection = 'third_mysql';
    protected $table = 'goods';
    protected $primaryKey = 'goods_id';
    protected $translationForeignKey = 'goods_id';
    public $translatedAttributes = [
        'goods_name',
        'brand_name',
        'goods_spec'
    ];
    public $timestamps = false;
    protected $fillable =[

        'store_id',
        'brand_id',
        'gc_id',
        'gc_id_1',
        'gc_id_2',
        'gc_id_3',
        'goods_addtime',
        'goods_state',
        'goods_verify',
        'goods_commonid',
        'goods_image',
        'goods_promotion_price',
        'goods_storage',
        'goods_storage_alarm',
        'goods_price',
        'goods_image',
        'goods_costprice',
        'goods_marketprice',
        'is_deleted'
    ];


    protected $attributes = [
        'goods_state' => 1,
      //  'goods_verify' => 1,
        'goods_type' => 0,
        'goods_promotion_type' => 0,
        'brand_id' => 0,
        'goods_price' => 0,
        'goods_promotion_price' => 0,
        'goods_costprice' => 0,
        'goods_image' => '',
        'goods_short_title_bak' => '',
        'goods_jingle_bak' => '',
        'goods_addtime' => 0,
        'goods_edittime' => 0,
        'virtual_indate' => 0,
        'areaid_1' => 0,
        'areaid_2' => 0,
        'transport_id' => 0,
        'virtual_limit' => 0,
        'wholesale_price' => 0,
        'installment_money' => 0,
        'goods_serial' => '',
        'is_deleted' => 0

    ];

    public function attribute()
    {
        return $this->hasOne(GoodsTranslation::class, 'goods_id', 'goods_id')
            ->where('language_id', 1)
            ->select(
                'goods_id',
                'goods_name',
                'goods_spec'
            );
    }


    public function getGoodsImageAttribute($value)
    {
        return empty($value)  ? '': config('app.image_url') . $value;
    }
 
}
