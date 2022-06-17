<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Provider;
use App\Models\Product;
class ProductSupplier extends Model
{
    use HasFactory;
    protected $fillable=['provider_id','product_id'];


    public function product(){
        return $this->belongsTo(product::class);
    }

    public function provider(){
        return $this->belongsTo(provider::class);
    }
}
