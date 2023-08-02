<?php

namespace App\Http\Resources\DataSync\Goods;

use App\Helper\Asset;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\DataSync\Goods\ImageGalleryResource;
use App\Http\Resources\DataSync\Goods\TranslatableResource;

class SingleGoodsDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $store_category_id = Asset::store_goods_class(@explode(',', $this->goods_stcids), 1);
        return [
            'id' => $this->goods_commonid,
            'store' => $this->store_id,
            'brand' => [
                'id' => $this->brand_id,
                'name' => $this->brand_name
            ],
            'category' => [
                'id' => [$this->gc_id_1, $this->gc_id_2, $this->gc_id_3],
                'name' => $this->gc_name
            ],
            'store_category' => [
                'id' => @explode(',', $this->goods_stcids),
                'name' => $store_category_id['stc_name'] ?? ''
            ],
            'commodity_grade' => $this->commodity_grade,
            'bar_code' => $this->bar_code,
            'name' => $this->goods_name,
            'languages' =>  new TranslatableResource($this->translations),
            'price' => $this->goods_price,
            'stock_quantity' => $this->goods_listings_sum_goods_storage ?? 0,
            'stock_warning' => $this->goods_storage_alarm,
            'image_feature' => $this->goods_image,
            'image_gallery' => ImageGalleryResource::collection($this->goods_images),
            'video_path' => $this->goods_video,
            'video_cover_path' => $this->video_cover_path,
            'video_duration' => $this->video_duration,
            'number_of_sold' => $this->goods_salenum,
            'sold_at' => $this->goods_selltime,
            'number_of_view' => $this->goods_click,
            'rattings' => $this->evaluation_good_star,
            'time_approval' => $this->time_approval,
            'is_deleted' => $this->is_deleted,
            'is_default_id' => $this->goods_default->goods_id,
            'variations' => empty($this->spec_name) ? [] : QuickEditResource::collection($this->goods_listings),
            'description' => $this->mobile_body
        ];
    }
}
