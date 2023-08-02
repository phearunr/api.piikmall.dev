<?php

namespace App\Models\DataSync;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use App\Models\DataSync\Goods_images;
use App\Models\DataSync\Goods_class;
use App\Models\DataSync\Store_goods_class;

class Goods_common extends Model implements TranslatableContract
{
    use HasFactory;
    use Translatable;
    protected $connection = 'second_mysql';
    // protected $connection = 'third_mysql';
    protected $table = 'goods_common';
    protected $primaryKey = 'goods_commonid';
    public $timestamps = false;
    protected $translationForeignKey = 'goods_commonid';
    protected $guarded = [];

    public $translatedAttributes = [
        'goods_name',
        'mobile_body',
        'brand_name',
        'gc_name',
        'spec_name',
        'spec_value'
    ];

    protected $casts = [
        'goods_image' => 'string'
    ];
    protected $fillable = [
        'goods_commonid',
        'store_id',
        'bar_code',
        'brand_id',
        'goods_stcids',
        'goods_image',
        'goods_video',
        'video_cover_path',
        'video_duration',
        'gc_id',
        'gc_id_1',
        'gc_id_2',
        'gc_id_3',
        'goods_state',
        'goods_verify',
        'goods_costprice',
        'goods_price',
        'goods_marketprice',
        'goods_storage_alarm',
        'goods_discount',
        'goods_serial',
        'goods_addtime'
    ];

    protected $attributes = [
        'bar_code' => 0,
        'brand_id' => 0,
        'goods_state' => 1,
      //  'goods_verify' => 1,
        'type_id' => 0,
        'goods_selltime' => 0,
        'goods_specname' => '',
        'goods_discount' => 0,
        'goods_storage_alarm' => 0,
        'goods_image' => '',
        'areaid_1' => 0,
        'areaid_2' => 0,
        'appoint_satedate' => 0,
        'presell_deliverdate' => 0,
        'installment_money' => 0,
        'third_party_url' => '',
        'goods_serial' => ''

    ];
    public function setGoodsAddtimeAttribute($value)
    {
        $this->attributes['goods_addtime'] =  strtotime(now());
    }
    public function setTimeApprovalAttribute($value)
    {
        $this->attributes['time_approval'] =  strtotime(now());
    }
    public function getGoodsVideoAttribute($value)
    {
        return empty($value) ? '' : config('app.video_url') . $this->store_id. '/' . $value;
    }

    public function setGoodsVideoAttribute($value)
    {
        $_str_replace = str_replace(config('app.video_url'). $this->store_id . '/', '', $value);

        if($_str_replace){
            $this->attributes['goods_video'] = $_str_replace;
        }else{
            $this->attributes['goods_video'] = $value;
        }
    }
    public function getVideoCoverPathAttribute($value)
    {
        return empty($value) ? '' : config('app.video_cover_path') . $this->store_id . '/' . $value;
    } 
    public function setVideoCoverPathAttribute($value)
    {
        $_str_replace = str_replace(config('app.video_cover_path'). $this->store_id . '/', '', $value);

        if($_str_replace){
            $this->attributes['video_cover_path'] = $_str_replace;
        }else{
            $this->attributes['video_cover_path'] = $value;
        }
    }
    public function getGoodsImageAttribute($value)
    {
        return empty($value) ? '' : config('app.image_url') . $value;
    }
    public function setGoodsImageAttribute($value)
    {
        $_str_replace = str_replace(config('app.image_url'), '', $value);

        if($_str_replace){
            $this->attributes['goods_image'] = $_str_replace;
        }else{
            $this->attributes['goods_image'] = $value;
        }
    }
    public function scopeImageGallery(Builder $builder, $images)
    {
        if (!is_null($images)) {
            // Images existed, and removed
            $goods_images = Goods_images::query()
                ->where([
                    'goods_commonid' => $this->goods_commonid
                ]);

            if ($goods_images) {
                $goods_images->delete();
            }

            // unset($images[0]);
            // Updoad new images base on goods_commonid

            $i = 0;
            foreach ($images as $key => $image) {
                Goods_images::query()->create([
                    'store_id' => $this->store_id,
                    'goods_commonid' => $this->goods_commonid,
                    'goods_image' => $image,
                    'is_default' => $i == 0 ? 1 : 0
                ]);
                $i++;
            }
        }
        return $builder;
    }
    public function goods(): object
    {
        return $this->hasMany(Goods::class, 'goods_commonid', 'goods_commonid');
    }
    public function goods_default()
    {
        return $this->hasOne(Goods::class, 'goods_commonid', 'goods_commonid')
            ->select('goods_commonid', 'goods_storage', 'goods_id', 'goods_promotion_type', 'goods_type');
    }

    public function goods_listings()
    {
        return $this->hasMany(Goods::class, 'goods_commonid', 'goods_commonid')
            ->where('is_deleted', 0);
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'brand_id');
    }

    public function category()
    {
        return $this->belongsTo(Goods_class::class, 'gc_id', 'gc_id');
    }
    public function store_category()
    {
        return $this->belongsTo(Store_goods_class::class, 'goods_stcids', 'stc_id');
    }

    public function scopeStatus(Builder $builder, $status)
    {
        if (!is_null($status)) {
            switch ($status) {
                case 'on-self':
                    $builder->where(['goods_state' => 1, 'goods_verify' => 1]);
                    break;
                case 'low-stock':
                    $builder->where('goods_state', 1);
                    $builder->orWhere('goods_storage_alarm', '>=', 'goods_storage');
                    $builder->orWhere(['goods_verify' => 1, 'goods_storage_alarm' => 0]);
                    break;
                case 'voilating':
                    $builder->where(['goods_state' => 10, 'goods_verify' => 1]);
                    break;
                case 'in-reviews':
                    $builder->where(['goods_state' => 1]);
                    $builder->whereIn('goods_verify', [0, 10]);
                    break;
                default:
                    $builder->where(['goods_state' => 0, 'goods_verify' => 1]);
            }
        }
        return $builder;
    }
    public function scopeCategory(Builder $builder, $category_id)
    {
        if (!is_null($category_id)) {
            $builder->where('gc_id', $category_id);
        }
        return $builder;
    }

    public function Goods_images(): object
    {
        return $this->hasMany(Goods_images::class, 'goods_commonid', 'goods_commonid');
    }

    // TYPE of simple
    public function scopeTypeOfSimple(Builder $builder, $data = [])
    {
        if (!empty($data)) {

            // Action = 'created'
            if ($data['action'] == 'created') {
            
                $goods_default = Arr::except($data['arrgs_default'], [
                    '1', '2', '5', 'store_category_id', 'goods_video'
                ]);
    
                $goods_default += [
                    'goods_commonid' => $this->goods_commonid,
                    '1' => [
                        'goods_name' => $data['languages']['en'],
                        'store_name' => $data['arrgs_default'][1]['store_name']
                    ],
                    '2' => [
                        'goods_name' => $data['languages']['cn'],
                        'store_name' => $data['arrgs_default'][2]['store_name']
                    ],
                    '5' => [
                        'goods_name' => $data['languages']['kh'],
                        'store_name' => $data['arrgs_default'][5]['store_name']
                    ]
                ];

                Goods::query()->create($goods_default);

            } else {

                $goods_default = Arr::except($data['arrgs_default'], [
                    '1', '2', '5', 'store_category_id', 'goods_video'
                ]);
    
                $goods_langs = [
                    '1' => [
                        'goods_name' => $data['languages']['en']['goods_name'],
                        'store_name' =>  $data['languages']['en']['store_name']
                    ],
                    '2' => [
                        'goods_name' => $data['languages']['cn']['goods_name'],
                        'store_name' => $data['languages']['cn']['store_name']
                    ],
                    '5' => [
                        'goods_name' => $data['languages']['kh']['goods_name'],
                        'store_name' => $data['languages']['kh']['store_name']
                    ]
                ];

                $this->goods->map(function ($query) use ($goods_default, $goods_langs) {
                    $query->update($goods_default);
                    return $query->translations->map(function ($item) use ($goods_langs) {
                        if(isset($item['language_id'])){
                        Goods_lang::query()
                            ->where([
                                'goods_id' => $item['goods_id'],
                                'language_id' => $item['language_id']
                            ])
                            ->update($goods_langs[$item['language_id']]);
                        }
                        return true;
                    });
                });
            }
        }
        return $builder;
    }

    // TYPE of variable
    public function scopeTypeOfVariable(Builder $builder, $data = [])
    {
        if (!empty($data['variations'])) {

            $spec_default = [];

            foreach ($data['variations'] as $variation) {

                $goods_spec = [
                    // 'en' => Asset::attribute_value($variation['attribute_id'], 1),
                    // 'cn' => Asset::attribute_value($variation['attribute_id'], 2),
                    // 'kh' => Asset::attribute_value($variation['attribute_id'], 5)
                ];;
                array_push($spec_default, [
                    // 'en' => $goods_spec['en'],
                    // 'cn' => $goods_spec['cn'],
                    // 'kh' => $goods_spec['kh']
                ]);

                $goods_default = [

                    'store_id' => $this->store_id,
                    'brand_id' => $this->brand_id,
                    'gc_id' => $this->gc_id,
                    'gc_id_1' => $this->gc_id_1,
                    'gc_id_2' => $this->gc_id_2,
                    'gc_id_3' => $this->gc_id_3,
                    'goods_addtime' => strtotime(now()),

                    // 'goods_storage' => $variation['stock_quantity'],
                    // 'goods_storage_alarm' => $variation['stock_warning'],
                    // 'goods_price' => $variation['price'],
                    // 'goods_costprice' => $variation['price'],
                    // 'goods_promotion_price' => $variation['price'],
                    // 'goods_marketprice' => ($variation['price'] * 2),
                    
                    'goods_commonid' => $this->goods_commonid,
                    // 'goods_image' => $variation['image'] ?? '',
                   // 'is_deleted' => $variation['is_deleted'] ?? 0,

                    '1' => [
                        'goods_name' => $data['languages']['en']['goods_name'],
                        'store_name' => $data['languages']['en']['store_name'],
                        // 'goods_spec' => $goods_spec['en']['goods_spec']
                    ],
                    '2' => [
                        'goods_name' => $data['languages']['cn']['goods_name'],
                        'store_name' => $data['languages']['en']['store_name'],
                        // 'goods_spec' =>  $goods_spec['cn']['goods_spec']
                    ],
                    '5' => [
                        'goods_name' => $data['languages']['kh']['goods_name'],
                        'store_name' => $data['languages']['en']['store_name'],
                        // 'goods_spec' => $goods_spec['kh']['goods_spec']
                    ]
                ];


                // Action = 'created'
                if ($data['action'] == 'created') {

                    Goods::query()->create($goods_default);

                } else if ($data['action'] == 'updated') {

                    // Action = 'updated'

                    if (isset($variation['id'])) {
                        
                        $this->goods->map(function ($query) use ($data ,$variation, $goods_spec){
                            
                            $query->update([

                                'store_id' => $this->store_id,
                                'brand_id' => $this->brand_id,
                                'gc_id' => $this->gc_id,
                                'gc_id_1' => $this->gc_id_1,
                                'gc_id_2' => $this->gc_id_2,
                                'gc_id_3' => $this->gc_id_3,
                                'goods_addtime' => strtotime(now()),
                               // 'goods_storage' => $variation['stock_quantity'],
                                //'goods_storage_alarm' => $variation['stock_warning'],
                                //'goods_price' => $variation['price'],
                                //'goods_costprice' => $variation['price'],
                                //'goods_promotion_price' => $variation['price'],
                                //'goods_marketprice' => ($variation['price'] * 2),
                                'goods_commonid' => $this->goods_commonid,
                               // 'goods_image' => $variation['image'] ?? '',
                              //  'is_deleted' => $variation['is_deleted'] ?? 0

                            ]);

                            $goods_langs = [
                                '1' => [
                                    'goods_name' => $data['languages']['en']['goods_name'],
                                    'store_name' => $data['languages']['en']['store_name'],
                                   // 'goods_spec' => $goods_spec['en']['goods_spec']
                                ],
                                '2' => [
                                    'goods_name' => $data['languages']['cn']['goods_name'],
                                    'store_name' => $data['languages']['cn']['store_name'],
                                    // 'goods_spec' =>  $goods_spec['cn']['goods_spec']
                                ],
                                '5' => [
                                    'goods_name' => $data['languages']['kh']['goods_name'],
                                    'store_name' => $data['languages']['kh']['store_name'],
                                    // 'goods_spec' => $goods_spec['kh']['goods_spec']
                                ]
                            ];

                            $query->translations->map(function ($item) use ($goods_langs){

                                if(isset($item['language_id'])){
                                    Goods_lang::query()
                                    ->where([
                                        'goods_id' => $item['goods_id'],
                                        'language_id' => $item['language_id']
                                    ])
                                    ->update($goods_langs[$item['language_id']]);
                                }     
                            });
                        });

                    } else {
                        Goods::query()->create($goods_default);
                    }
                }
            }
        }
        return $builder;
    }
}
