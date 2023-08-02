<?php

namespace App\Http\Controllers\DataSync;

use App\Helper\Asset;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Models\DataSync\Goods;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\DataSync\Goods_common;
use App\Models\DataSync\Goods_common_lang;
use App\Http\Resources\DataSync\Goods\QuickEditResource;
use App\Http\Resources\DataSync\Goods\GoodsCommonResource;
use App\Http\Resources\DataSync\Goods\SingleGoodsDetailResource;

class GoodsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $creds = $request->validate([
            'language_id' => 'required',
            'store_id' => 'required',
            'status' => 'required',
            'page' => 'required',
            'per_page' => 'required',
        ]);

        $goods_default = Goods_common::query()
            ->with([
                'translations' => function ($q) use ($creds) {
                    $q->where('language_id', $creds['language_id']);
                },
                'goods_default',
                'category.translations' => function ($q) use ($creds) {
                    $q->where('language_id', $creds['language_id']);
                }
            ])
            ->category(request('category_id'))
            ->status(request('status'))
            ->where(['store_id' => $creds['store_id']])
            ->orderBy('goods_commonid', 'DESC')
            ->paginate($creds['per_page']);

        return GoodsCommonResource::collection($goods_default);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'languages.en' => 'required|max:255',
            'languages.cn' => 'required|max:255',
            'languages.kh' => 'required|max:255',
            'store_id' => 'required',
            'category_id' => 'array|min:3',
            'languages' => 'array|min:1',
            'price' => 'required|numeric|min:2',
            'stock_quantity' => 'required|numeric|min:1',
            'images' => 'array|min:1'
        ]);

        $_en_store = Asset::store_info($request['store_id'], 1);
        $_cn_store = Asset::store_info($request['store_id'], 2);
        $_kh_store = Asset::store_info($request['store_id'], 5);

        $_en_brand = Asset::brand($request['brand_id'], 1);
        $_cn_brand = Asset::brand($request['brand_id'], 2);
        $_kh_brand = Asset::brand($request['brand_id'], 5);

        $_en_goods_class = Asset::goods_class($request['category_id'], 1);
        $_cn_goods_class = Asset::goods_class($request['category_id'], 2);
        $_kh_goods_class = Asset::goods_class($request['category_id'], 5);

        $arrgs_default =
            [
                'store_id' => $request['store_id'],
                'brand_id' => $request['brand_id'],
                'bar_code' => $request['bar_code'] ?? 0,
                'gc_id' => $request['category_id'][2],
                'gc_id_1' => $request['category_id'][0],
                'gc_id_2' => $request['category_id'][1],
                'gc_id_3' => $request['category_id'][2],
                'goods_stcids' => @implode(',', $request['store_category_id']) ?? '',
                'goods_price' => $request['price'],
                'goods_storage' => $request['stock_quantity'],
                'goods_storage_alarm' => $request['stock_warning'] ?? 0,
                'goods_promotion_price' => $request['price'],
                'goods_marketprice' => ($request['price'] * 2),
                'goods_costprice' => $request['price'],
                'goods_image' => $request['images'][0] ?? '',
                'goods_video' => $request['video_path'] ?? '',
                'video_cover_path' => $request['video_cover_path'] ?? '',
                'video_duration' => $request['video_duration'] ?? 0.00,
                'goods_verify' => Asset::store_audit() == 1 ? 10 : 1,

                '1' => [
                    'goods_name' => $request['languages']['en'],
                    'mobile_body' =>  Asset::mobile_body($request['description']) ?? '',
                    'store_name' => $_en_store['store_name'] ?? '',
                    'brand_name' => $_en_brand['brand_name'] ?? '',
                    'gc_name' => $_en_goods_class['gc_name'] ?? '',
                    'spec_name' => '',
                    'spec_value' => ''

                ],
                '2' => [
                    'goods_name' => $request['languages']['cn'],
                    'mobile_body' =>  Asset::mobile_body($request['description']) ?? '',
                    'store_name' => $_cn_store['store_name'] ?? '',
                    'brand_name' => $_cn_brand['brand_name'] ?? '',
                    'gc_name' => $_cn_goods_class['gc_name'] ?? '',
                    'spec_name' => '',
                    'spec_value' => '',

                ],
                '5' => [
                    'goods_name' => $request['languages']['kh'],
                    'mobile_body' =>  Asset::mobile_body($request['description']) ?? '',
                    'store_name' => $_kh_store['store_name'] ?? '',
                    'brand_name' => $_kh_brand['brand_name'] ?? '',
                    'gc_name' => $_kh_goods_class['gc_name'] ?? '',
                    'spec_name' => '',
                    'spec_value' => '',
                ],
                'goods_addtime' => strtotime(now()),
                'time_approval' => strtotime(now())

            ];

        $created = DB::transaction(function () use ($arrgs_default, $request, $_en_store) {

            $goods_common =  Goods_common::create(
                Arr::except($arrgs_default, [
                    'goods_storage', 'goods_promotion_price'
                ])
            );

            // UPDOAD Images
            $goods_common->ImageGallery($request['images']);

            // Case Simple
            if (empty($request['variations'])) {
                $goods_common->TypeOfSimple([
                    'action'  => 'created',
                    'variations' => $request['variations'],
                    'arrgs_default' => $arrgs_default,
                    'languages'  => $request['languages']
                ]);
            }

            // Case variations
            $goods_common->TypeOfVariable([
                'action'  => 'created',
                'variations' => $request['variations'],
                'arrgs_default' => $arrgs_default,
                'languages'  => [
                    'en' => [
                        'goods_name' => $request['languages']['en'],
                        'store_name' => $_en_store['store_name']
                    ],
                    'cn' => [
                        'goods_name' => $request['languages']['cn'],
                        'store_name' => $_en_store['store_name']
                    ],
                    'kh' => [
                        'goods_name' => $request['languages']['kh'],
                        'store_name' => $_en_store['store_name']
                    ]
                ]
            ]);
            return $goods_common;
        });

        if ($created) {
            return response([
                'error' => 0,
                'data' => ['audit' => $arrgs_default['goods_verify']],
                'msg' => 'Created success.'
            ], 201);
        }
        return response([
            'error' => 1,
            'msg' => 'Created fail.'
        ], 401);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DataSync\Goods_common  $goods_common
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $goods_default = Goods_common::query()
            ->with([
                'translations',
                'goods_default',
                'goods_listings',
                'category.translations' => function ($q) use ($request) {
                    // $q->where('language_id', $request['language_id'] ?? 1);
                },
                'goods_images' => fn ($q) => $q->select('goods_commonid', 'goods_image', 'goods_image_id', 'is_default')
            ])
            ->withSum('goods_listings', 'goods_storage')
            ->findOrFail($id);

        return response([
            'data' => new SingleGoodsDetailResource($goods_default),
            'error' => 0
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DataSync\Goods_common  $goods_common
     * @return \Illuminate\Http\Response
     */
    public function QuickEdit($id)
    {
        $goodsCommon = Goods_common::query()
            ->with('goods_listings')
            ->findOrFail($id);

        return response([
            'data' => QuickEditResource::collection($goodsCommon['goods_listings']),
            'error' => 0
        ], 200);
    }

    public function QuickUpdate(Request $request, $id)
    {
        $quick_update = DB::transaction(function () use ($id, $request) {

            $goods_common =  Goods_common::query()->findOrFail($id);

            if ($items = $request->data) {
                foreach ($items as $item) {
                    $goods = Goods::query()->find($item['id']);
                    if ($goods) {
                        $goods->update([
                            'goods_storage' => $item['stock_quantity'],
                            'goods_storage_alarm' => $item['stock_warning'],
                            'goods_costprice' => $item['price'],
                            'goods_promotion_price' => $item['price'] ?? 0,
                            'goods_price' => $item['price'],
                            'goods_marketprice' => ($item['price'] * 2),
                        ]);
                    }
                }

                if (!empty($_item = $items[0])) {
                    $goods_common->update([
                        'goods_price' => $_item['price'],
                        'goods_costprice' => $_item['price'],
                        'goods_marketprice' => ($_item['price'] * 2),
                        'goods_storage_alarm' => $_item['stock_warning']
                    ]);
                    return true;
                }
            }
        });

        if (!$quick_update) {
            return response([
                'error' => 1,
                'msg' => 'Updated fail.'
            ], 402);
        }
        return response([
            'error' => 0,
            'msg' => 'Updated success.'
        ], 202);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DataSync\Goods_common  $goods_common
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'languages.en' => 'required|max:255',
            'languages.cn' => 'required|max:255',
            'languages.kh' => 'required|max:255',
            'store_id' => 'required',
            'category_id' => 'array|min:3',
            'languages' => 'array|min:1',
            'price' => 'required|numeric|min:2',
            'stock_quantity' => 'required|numeric|min:1',
            'images' => 'array|min:1'
        ]);

        $_en_store = Asset::store_info($request['store_id'], 1);
        $_cn_store = Asset::store_info($request['store_id'], 2);
        $_kh_store = Asset::store_info($request['store_id'], 5);

        $_en_brand = Asset::brand($request['brand_id'], 1);
        $_cn_brand = Asset::brand($request['brand_id'], 2);
        $_kh_brand = Asset::brand($request['brand_id'], 5);

        $_en_goods_class = Asset::goods_class($request['category_id'], 1);
        $_cn_goods_class = Asset::goods_class($request['category_id'], 2);
        $_kh_goods_class = Asset::goods_class($request['category_id'], 5);

        $arrgs_default =
            [
                'store_id' => $request['store_id'],
                'brand_id' => $request['brand_id'] ?? 0,
                'bar_code' => $request['bar_code'] ?? 0,
                'gc_id' => $request['category_id'][2],
                'gc_id_1' => $request['category_id'][0],
                'gc_id_2' => $request['category_id'][1],
                'gc_id_3' => $request['category_id'][2],
                'goods_stcids' => @implode(',', $request['store_category_id']) ?? '',
                'goods_price' => $request['price'],
                'goods_storage' => $request['stock_quantity'],
                'goods_storage_alarm' => $request['stock_warning'] ?? 0,
                'goods_promotion_price' => $request['price'],
                'goods_marketprice' => ($request['price'] * 2),
                'goods_costprice' => $request['price'],
                'goods_image' => $request['images'][0] ?? '',
                'goods_video' => $request['video_path'] ?? '',
                'video_cover_path' => $request['video_cover_path'] ?? '',
                'video_duration' => $request['video_duration'] ?? 0.00,
                'goods_addtime' => strtotime(now()),
                'time_approval' => strtotime(now()),
                'goods_verify' => Asset::store_audit() == 1 ? 10 : 1,

            ];

        $goods_comon_langs = [
            '1' => [
                'goods_name' => $request['languages']['en'],
                'mobile_body' =>  Asset::mobile_body($request['description']) ?? '',
                'store_name' => $_en_store['store_name'] ?? '',
                'brand_name' => $_en_brand['brand_name'] ?? '',
                'gc_name' => $_en_goods_class['gc_name'] ?? '',
                // 'spec_name' => '',
                // 'spec_value' => ''
            ],
            '2' => [
                'goods_name' => $request['languages']['cn'],
                'mobile_body' =>  Asset::mobile_body($request['description']) ?? '',
                'store_name' => $_cn_store['store_name'] ?? '',
                'brand_name' => $_cn_brand['brand_name'] ?? '',
                'gc_name' => $_cn_goods_class['gc_name'] ?? '',
                // 'spec_name' => '',
                // 'spec_value' => ''

            ],
            '5' => [
                'goods_name' => $request['languages']['kh'],
                'mobile_body' =>  Asset::mobile_body($request['description']) ?? '',
                'store_name' => $_kh_store['store_name'] ?? '',
                'brand_name' => $_kh_brand['brand_name'] ?? '',
                'gc_name' => $_kh_goods_class['gc_name'] ?? '',
                // 'spec_name' => '',
                // 'spec_value' => ''
            ]
        ];

        $updated = DB::transaction(function () use ($id, $arrgs_default, $goods_comon_langs, $request,  $_en_store) {

            $goods_common = Goods_common::query()->findOrFail($id);

            $goods_common->update(
                Arr::except($arrgs_default, [
                    'goods_storage', 'goods_promotion_price'
                ])
            );

            $goods_common->translations->map(function ($item) use ($goods_comon_langs) {
                if (!empty($item['language_id'])) {
                    Goods_common_lang::query()
                        ->where([
                            'language_id' => $item['language_id'],
                            'goods_commonid' => $item['goods_commonid']
                        ])
                        ->update($goods_comon_langs[$item['language_id']]);
                    return true;
                }
            });

            // UPDOAD Images
            $goods_common->ImageGallery($request['images']);

            // Case Simple
            if (empty($request['variations'])) {
                $goods_common->TypeOfSimple([
                    'action'  => 'updated',
                    'arrgs_default' => $arrgs_default,
                    'variations' => $request['variations'],
                    'languages'  => [
                        'en' => [
                            'goods_name' => $request['languages']['en'],
                            'store_name' => $_en_store['store_name']
                        ],
                        'cn' => [
                            'goods_name' => $request['languages']['cn'],
                            'store_name' => $_en_store['store_name']
                        ],
                        'kh' => [
                            'goods_name' => $request['languages']['kh'],
                            'store_name' => $_en_store['store_name']
                        ]
                    ]
                ]);
            }

            // Case Variations
            $goods_common->TypeOfVariable([
                'action'  => 'updated',
                'variations' => $request['variations'],
                'arrgs_default' => $arrgs_default,
                'languages'  => [
                    'en' => [
                        'goods_name' => $request['languages']['en'],
                        'store_name' => $_en_store['store_name']
                    ],
                    'cn' => [
                        'goods_name' => $request['languages']['cn'],
                        'store_name' => $_en_store['store_name']
                    ],
                    'kh' => [
                        'goods_name' => $request['languages']['kh'],
                        'store_name' => $_en_store['store_name']
                    ]
                ]
            ]);
            return true;
        });

        if ($updated) {
            return response([
                'error' => 0,
                'data' => ['audit' => $arrgs_default['goods_verify']],
                'msg' => 'Updated success.'
            ], 202);
        }

        return response([
            'error' => 1,
            'msg' => 'Updated fail.'
        ], 402);
    }
}
