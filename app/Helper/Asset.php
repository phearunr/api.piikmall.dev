<?php

namespace App\Helper;

use App\Models\DataSync\Store;
use App\Models\DataSync\Setting;
use App\Models\DataSync\SpecValue;
use App\Models\DataSync\Brand_lang;
use App\Models\DataSync\Spec_value_lang;
use App\Models\DataSync\Goods_class_lang;
use App\Models\DataSync\Store_goods_class_lang;

class Asset
{
    static public function store_info($store_id = 0, $langiage_id = 1): object
    {
        return Store::query()
            ->select('store_id', 'store_name')
            ->where([
                'store_id' => $store_id,
                // 'language_id' => $langiage_id
            ])
            ->first();
    }
    static public function brand($brand_id = 0, $langiage_id = 1): object
    {
        return Brand_lang::query()
            ->select('brand_id', 'brand_name')
            ->where(['brand_id' => $brand_id, 'language_id' => $langiage_id])
            ->first();
    }
    static public function goods_class($in_arrgs = [], $langiage_id = 1): array
    {
        $goos_class = Goods_class_lang::query()
            ->select('gc_id', 'gc_name')
            ->where(['language_id' => $langiage_id])
            ->whereIn('gc_id', $in_arrgs)
            ->get();
        $arrg = [];
        if (!empty($goos_class)) {
            foreach ($goos_class as $item) {
                array_push($arrg, $item['gc_name']);
            }
            $arrg = implode('>', $arrg);
        }
        return [
            'gc_name' => $arrg
        ];
    }
    static public function store_goods_class($in_arrgs = [], $langiage_id = 1): array
    {
        $goos_class = Store_goods_class_lang::query()
            ->select('stc_id', 'stc_name')
            ->where(['language_id' => $langiage_id])
            ->whereIn('stc_id', $in_arrgs)
            ->get();
        $arrg = [];
        if (!empty($goos_class)) {
            foreach ($goos_class as $item) {
                array_push($arrg, $item['stc_name']);
            }

            $arrg = implode('>', $arrg);
        }
        return [
            'stc_name' => $arrg
        ];
    }

    static public function attribute_value($in_arrgs = [], $langiage_id = 1): array
    {
        $specs = SpecValue::query()
            ->select(
                'sp_id',
                'sp_value_id'
            )
            ->with([
                'spec_value_lang' => fn ($q) => $q->where(['language_id' => $langiage_id])
            ])
            ->whereIn('sp_value_id', $in_arrgs)
            ->get();

        $arrg_specs = [];
        $arrg_spec_values = [];
        $goods_spec = [];

        if (!empty($specs)) {
            foreach ($specs as $spec) {
                array_push($arrg_spec_values, $spec['spec_value_lang']);
                array_push($arrg_specs, array(
                    $spec['sp_id'] => [$spec['sp_value_id'] => $spec['spec_value_lang']['sp_value_name']]
                ));
            }
            if (!empty($arrg_spec_values)) {
                foreach ($arrg_spec_values as $value) {
                    $goods_spec[$value['sp_value_id']] = $value['sp_value_name'];
                }
            }
        }
        return [
            'spec_value' => $arrg_specs,
            'goods_spec' => $goods_spec
        ];
    }

    static public function attribute_value_lang($in_arrgs = [], $langiage_id = 1): array
    {

        $arrgs = [];
        if (!empty($in_arrgs)) {
            $spec_value_lang =  Spec_value_lang::query()
                ->join('spec_value', 'spec_value.sp_value_id', '=', 'spec_value_lang.sp_value_id')
                ->whereIn('spec_value_lang.sp_value_name', $in_arrgs)
                ->where('spec_value.sp_value_id', '>', 0)
                ->where(['spec_value_lang.language_id' => $langiage_id])
                ->select('spec_value_lang.sp_value_id', 'spec_value_lang.sp_value_name')
                ->get()
                ->map(function ($item) {
                    return [
                        'value' => $item->sp_value_id,
                        'label' => strtoupper($item->sp_value_name)
                    ];
                })->unique('label')->values();


            if ($spec_value_lang) {
                foreach ($spec_value_lang as $items) {
                    array_push($arrgs, $items['value']);
                }
            }
        }
        return  $arrgs;
    }

    public static function pro_unserialize(string $str): ?array
    {
        if (PHP_VERSION > 7.2) {
            if (strpos($str, 'r:') !== false) {
                $arr =   explode(':{', $str);
                $text = trim($arr[1], '}');
                $arr2 = array_filter(explode(';', $text));
                $keys = [];
                $values = [];
                foreach ($arr2 as $k => $v) {
                    if ($k % 2 === 0) {
                        array_push($keys, $v);
                    } else {
                        array_push($values, $v);
                    }
                }
                $newStr = '';
                foreach ($values as  $key => $val) {
                    if (strpos($val, 'r:') !== false) {
                        $arrs =  explode('r:', $val);
                        $pos = $arrs[1] - 2;
                        $val = $values[$pos];
                    }
                    $newStr .= $keys[$key] . ';' . $val . ';';
                }
                $str = $arr[0] . ':{' . $newStr . '}';
            }
        }
        return  unserialize($str);
    }

    public static function store_audit()
    {
        $store_audit = 0;
        $query =  Setting::query()
            ->where('name', 'like', '%goods_verify%')
            ->select('value')
            ->first();

        if ($query) {
            $store_audit = $query['value'];
        }
        return $store_audit;
    }

    public static function discount_in_percentage($goods_amount = 0, $discount_amount = 0, $original_order_price_detail = 0)
    {
        $original_order_price = $original_order_price_detail ? json_decode($original_order_price_detail) : 0;
        return (!empty($discount_amount) ? round(((100 * $discount_amount) / (!empty($original_order_price->order_amount) ? $original_order_price->order_amount : $goods_amount))) : 0) . '%';
    }

    public static function mobile_body($items = [])
    {
        $mobile_body = [];
        if (!empty($items)) {
            foreach ($items as $item) {
                if (!empty($item['type']) == 'image') {
                    $mobile_body[] = [
                        'type' => $item['type'],
                        'value' => str_replace(config('app.image_upload_url'), '', $item['value'])
                    ];
                } else {
                    $mobile_body[] = $item;
                }
            }
        }
        return serialize($mobile_body);
    }
}
