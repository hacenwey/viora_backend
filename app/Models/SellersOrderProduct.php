<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class SellersOrderProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'sellers_order_id',
        'product_id',
        'price',
        'quantity',
        'sub_total',
        'commission',
        'gain'
    ];


    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
