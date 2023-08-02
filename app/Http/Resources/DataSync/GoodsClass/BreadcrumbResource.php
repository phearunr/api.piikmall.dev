<?php

namespace App\Http\Resources\DataSync\GoodsClass;

use Illuminate\Http\Resources\Json\JsonResource;

class BreadcrumbResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'parent' => new BreadcrumbKeyRenameResource($this['parent']['parent']['translations'][0]),
            'child' => new BreadcrumbKeyRenameResource($this['parent']['translations'][0]),
            'children' => new BreadcrumbKeyRenameResource($this['translations'][0])
        ];
    }
}
