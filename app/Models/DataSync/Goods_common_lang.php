<?php

namespace App\Models\DataSync;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Goods_common_lang extends Model
{
    use HasFactory;
    protected $connection = 'second_mysql';
    // protected $connection = 'third_mysql';
    protected $table = 'goods_common_lang';
    protected $primaryKey = 'language_id';
    public $timestamps = false;

    protected $translationForeignKey = 'goods_commonid';
    protected $fillable = [
        'language_id',
        'store_name',
        'goods_name',
        'brand_name',
        'gc_name',
        'spec_name',
        'spec_value',
        'mobile_body',
    ];
    protected $casts = [
        'store_name' => 'string',
        'brand_name' => 'string',
        'gc_name' => 'string',
        'goods_name' => 'string'
    ];
    protected $attributes = [
        'goods_short_title' => '',
        'goods_jingle' => '',
        'store_name' => '',
        'goods_attr' => '',
        'goods_body' => ''
    ];
    protected $hidden = [
        'goods_short_title',
        'goods_unit',
        'store_name',
        'goods_attr',
        'mobile_body_json',
        'goods_jingle',
        'goods_body'
    ];

    public function getMobileBodyAttribute($value)
    {
        $mobile_body = [];
        $items = @unserialize($value) !== false ? unserialize($value) :[];
        if (!empty($items)) {
            foreach ($items as $item) {
                if ($item['type'] == 'image') {
                    $mobile_body[] = [
                        'type' => $item['type'],
                        'value' => config('app.image_upload_url') . $item['value']
                    ];
                } else {
                    $mobile_body[] = $item;
                }
            }
        }
        return $mobile_body;
    }

    // public function setMobileBodyAttribute($value)
    // {
    //     $mobile_body = [];
    //     if (!empty($value)) {
    //         foreach ($value as $item) {
    //             if (!empty($item['type']) == 'image') {
    //                 $mobile_body[] = [
    //                     'type' => $item['type'],
    //                     'value' => str_replace(config('app.image_upload_url'), '', $item['value'])
    //                 ];
    //             } else {
    //                 $mobile_body[] = $item;
    //             }
    //         }
    //         $this->attributes['mobile_body'] = serialize($mobile_body);
    //     }
    // }

    public function getSpecNameAttribute($value)
    {
        return @unserialize($value) !== false ? unserialize($value) : $value;
    }

    public function setSpecNameAttribute($value)
    {
        $this->attributes['spec_name'] = serialize($value);
    }

    public function getSpecValueAttribute($value)
    {
        return @unserialize($value) !== false ? unserialize($value) : $value;
    }

    public function setSpecValueAttribute($value)
    {
        $this->attributes['spec_value'] = @serialize($value) ?? '';
    }
}
