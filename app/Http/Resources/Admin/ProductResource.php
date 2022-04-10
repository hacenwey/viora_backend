<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'    => $this->id,
            'sku'   => $this->sku,
            'title' => $this->title,
            'photo' => $this->image,
            'price' => $this->price,
        ];

        return parent::toArray($request);
    }
}
