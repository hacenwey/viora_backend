<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    public $timestamps = false;

    protected $with = ['product'];

    protected $fillable=[
        'id',
        'order_id',
        'product_id',
        'product_name',
        'price',
        'quantity',
        'sub_total',
        'attributes',
    ];

    public function order(){
        return $this->belongsTo('App\Models\Order');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function getAttributesAttribute($value)
    {
        return json_decode($value, true);
    }

}
