<?php

namespace App\Models\DataSync;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Goods_images extends Model
{
    use HasFactory;
    protected $connection = 'second_mysql';
    protected $table = 'goods_images';
    protected $primaryKey = 'goods_image_id';
    protected $fillable =[
        'goods_commonid',
        'store_id',
        'goods_image',
        'is_default'
    ];
    public $timestamps = false;

    protected $hidden = [];
    protected $attributes =[
        'color_id' => 0,
        'goods_image_sort' => 0
    ];

    public function setGoodsImageAttribute($value)
    {
        $this->attributes['goods_image'] =  @Str::remove(config("app.image_url"), $value) ?? $value;
    }
   
    public function getGoodsImageAttribute($value)
    {
        return empty($value) ? '' : config('app.image_url') . $value;
    }
}
