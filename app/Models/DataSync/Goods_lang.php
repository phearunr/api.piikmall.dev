<?php

namespace App\Models\DataSync;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Goods_lang extends Model
{
    use HasFactory;
    protected $connection = 'second_mysql';
    // protected $connection = 'third_mysql';
    protected $table = 'goods_lang';
   protected $primaryKey = 'language_id';
    public $timestamps = false;
    protected $translationForeignKey = 'goods_id';
    protected $guarded = [];

    protected $attributes = [
        'goods_short_title' => '',
        'goods_jingle' => 0,
        'store_name' => '',
        'goods_param' => 0,
        'goods_spec' => ''
    ];

    public function getGoodsSpecAttribute($value)
    {
        return @unserialize($value) !== false ? unserialize($value) : $value;
    }

    public function setGoodsSpecAttribute($value)
    {
        $this->attributes['goods_spec'] = @serialize($value) ?? '';
    }
}
